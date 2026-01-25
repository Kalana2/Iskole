// Fallback/dummy data (used on dashboards that don't yet fetch marks)
const fallbackStudentData = {
  subjects: [
    { name: "Religion", score: 89, grade: "A" },
    { name: "Sinhala", score: 77, grade: "A" },
    { name: "Mathematics", score: 92, grade: "A" },
    { name: "Science", score: 88, grade: "A" },
    { name: "English", score: 85, grade: "A" },
    { name: "History", score: 60, grade: "C" },
    { name: "Geography", score: 73, grade: "B" },
    { name: "Health & PE", score: 74, grade: "B" },
    { name: "Tamil", score: 99, grade: "A" },
    { name: "Aesthetics", score: 55, grade: "C" },
    { name: "Citizenship", score: 30, grade: "W" },
    { name: "Practical Skills", score: 45, grade: "S" },
  ],
  terms: {
    term1: [85, 70, 88, 82, 80, 55, 68, 70, 95, 50, 25, 40],
    term2: [87, 74, 90, 85, 83, 58, 70, 72, 97, 53, 28, 42],
    term3: [89, 77, 92, 88, 85, 60, 73, 74, 99, 55, 30, 45],
  },
};

// Live student marks data (loaded from DB for My Marks)
let studentData = null;

// Chart.js configuration
let performanceChart = null;
let currentChartType = "bar";

// Color scheme
const colorScheme = {
  primary: "rgba(102, 126, 234, 0.8)",
  secondary: "rgba(118, 75, 162, 0.8)",
  success: "rgba(16, 185, 129, 0.8)",
  warning: "rgba(245, 158, 11, 0.8)",
  danger: "rgba(239, 68, 68, 0.8)",
  info: "rgba(59, 130, 246, 0.8)",
};

// Initialize chart
function initChart() {
  const ctx = document.getElementById("performanceChart");
  if (!ctx) return;

  if (
    !studentData ||
    !studentData.subjects ||
    studentData.subjects.length === 0
  ) {
    // No data to draw yet
    return;
  }

  // Destroy existing chart if it exists
  if (performanceChart) {
    performanceChart.destroy();
  }

  const labels = studentData.subjects.map((s) => s.name);

  function setRgbaAlpha(color, alpha) {
    if (typeof color !== "string") return color;
    return color.replace(
      /rgba\((\s*\d+\s*),(\s*\d+\s*),(\s*\d+\s*),\s*[\d.]+\s*\)/i,
      `rgba($1,$2,$3, ${alpha})`,
    );
  }

  // Define datasets for all three terms
  const termDatasets = [
    {
      key: "term1",
      label: "Term 1",
      data: studentData.terms.term1,
      termKey: "term1",
      borderColor: "rgba(239, 68, 68, 0.8)",
      backgroundColor:
        currentChartType === "bar"
          ? "rgba(239, 68, 68, 0.55)"
          : "rgba(239, 68, 68, 0.1)",
    },
    {
      key: "term2",
      label: "Term 2",
      data: studentData.terms.term2,
      termKey: "term2",
      borderColor: "rgba(245, 158, 11, 0.8)",
      backgroundColor:
        currentChartType === "bar"
          ? "rgba(245, 158, 11, 0.55)"
          : "rgba(245, 158, 11, 0.1)",
    },
    {
      key: "term3",
      label: "Term 3",
      data: studentData.terms.term3,
      termKey: "term3",
      borderColor: "rgba(16, 185, 129, 0.8)",
      backgroundColor:
        currentChartType === "bar"
          ? "rgba(16, 185, 129, 0.55)"
          : "rgba(16, 185, 129, 0.1)",
    },
  ];

  // Always show datasets for all three terms
  const datasets = termDatasets.map((dataset) => ({
    label: dataset.label,
    data: dataset.data,
    termKey: dataset.termKey,
    borderColor: dataset.borderColor,
    backgroundColor: dataset.backgroundColor,
    borderWidth: 2,
    pointBackgroundColor: dataset.borderColor,
    pointBorderColor: "#fff",
    pointHoverBackgroundColor: "#fff",
    pointHoverBorderColor: dataset.borderColor,
    pointRadius: currentChartType === "line" ? 4 : 0,
    tension: 0.4,
    fill: currentChartType === "line",
  }));

  const config = {
    type: currentChartType,
    data: {
      labels: labels,
      datasets: datasets,
    },
    options: {
      responsive: true,
      maintainAspectRatio: true,
      aspectRatio: 2,
      plugins: {
        legend: {
          // Show legend so users can map colors -> terms
          display: true,
          position: "top",
          labels: {
            usePointStyle: true,
            padding: 15,
            font: {
              size: 12,
              weight: "600",
            },
          },
        },
        tooltip: {
          backgroundColor: "rgba(0, 0, 0, 0.8)",
          padding: 12,
          cornerRadius: 8,
          titleFont: {
            size: 14,
            weight: "bold",
          },
          bodyFont: {
            size: 13,
          },
          callbacks: {
            label: function (context) {
              const termLabel = context?.dataset?.label || "";
              const parsed = context.parsed;
              const score =
                parsed && typeof parsed.y !== "undefined" ? parsed.y : null;
              const subject = studentData.subjects[context.dataIndex];
              const termKey = context.dataset.termKey;
              const grade =
                (subject &&
                  subject.grades &&
                  termKey &&
                  subject.grades[termKey]) ||
                (subject && subject.grade) ||
                "-";

              if (score === null || typeof score === "undefined") {
                return `${termLabel}: No mark (Grade: ${grade})`;
              }
              return `${termLabel}: ${score}/100 (Grade: ${grade})`;
            },
          },
        },
      },
      scales:
        currentChartType === "line" || currentChartType === "bar"
          ? {
              x: {
                grid: {
                  display: false,
                },
                ticks: {
                  font: {
                    size: 11,
                    weight: "600",
                  },
                  maxRotation: 45,
                  minRotation: 45,
                },
              },
              y: {
                beginAtZero: true,
                max: 100,
                grid: {
                  color: "rgba(0, 0, 0, 0.05)",
                },
                ticks: {
                  font: {
                    size: 11,
                  },
                  callback: function (value) {
                    return value + "%";
                  },
                },
              },
            }
          : {},
    },
  };

  performanceChart = new Chart(ctx, config);
}

async function loadMyMarksData() {
  const studentIdEl = document.getElementById("studentId");
  // Only fetch for the student "My Marks" template (it includes #studentId)
  if (!studentIdEl) {
    studentData = fallbackStudentData;
    return true;
  }

  try {
    const res = await fetch("/marksReport/myMarks", {
      method: "GET",
      headers: { Accept: "application/json" },
    });
    const json = await res.json();
    if (!res.ok || !json || !json.success) {
      console.error("Failed to load marks:", json);
      studentData = fallbackStudentData;
      return false;
    }

    studentData = buildStudentDataFromMarks(
      json.marks || [],
      json.subjects || [],
    );
    return true;
  } catch (e) {
    console.error("Error loading marks:", e);
    studentData = fallbackStudentData;
    return false;
  }
}

function buildStudentDataFromMarks(rows, allSubjects) {
  const subjectMap = new Map();
  const termScores = { term1: new Map(), term2: new Map(), term3: new Map() };
  const termGrades = { term1: new Map(), term2: new Map(), term3: new Map() };

  // Always start with the full subjects list (so subjects with no marks still show)
  if (Array.isArray(allSubjects) && allSubjects.length > 0) {
    allSubjects.forEach((s) => {
      const subjectId = s.subjectID;
      const subjectName = s.subjectName || "";
      if (subjectId === null || typeof subjectId === "undefined") return;
      if (!subjectMap.has(subjectId)) {
        subjectMap.set(subjectId, {
          id: subjectId,
          name: subjectName,
          grades: {},
        });
      }
    });
  }

  rows.forEach((row) => {
    const subjectId = row.subjectID;
    const subjectName = row.subjectName || "";
    if (subjectId === null || typeof subjectId === "undefined") return;

    if (!subjectMap.has(subjectId)) {
      subjectMap.set(subjectId, {
        id: subjectId,
        name: subjectName,
        grades: {},
      });
    }

    const term = String(row.term || "");
    const termKey =
      term === "1"
        ? "term1"
        : term === "2"
          ? "term2"
          : term === "3"
            ? "term3"
            : null;
    if (!termKey) return;

    const markVal = row.marks;
    const gradeLetter = row.gradeLetter || null;
    if (markVal !== null && typeof markVal !== "undefined") {
      termScores[termKey].set(subjectId, Number(markVal));
    }
    if (gradeLetter) {
      termGrades[termKey].set(subjectId, gradeLetter);
    }
  });

  const subjects = Array.from(subjectMap.values()).sort((a, b) =>
    String(a.name).localeCompare(String(b.name)),
  );

  // Build term arrays aligned with subject order
  const terms = { term1: [], term2: [], term3: [] };
  subjects.forEach((s) => {
    terms.term1.push(
      termScores.term1.has(s.id) ? termScores.term1.get(s.id) : 0,
    );
    terms.term2.push(
      termScores.term2.has(s.id) ? termScores.term2.get(s.id) : 0,
    );
    terms.term3.push(
      termScores.term3.has(s.id) ? termScores.term3.get(s.id) : 0,
    );

    s.grades = {
      term1: termGrades.term1.get(s.id) || null,
      term2: termGrades.term2.get(s.id) || null,
      term3: termGrades.term3.get(s.id) || null,
    };

    // keep a default grade for any legacy UI usage
    s.grade = s.grades.term3 || s.grades.term2 || s.grades.term1 || "-";
  });

  return { subjects, terms };
}

// Chart toggle functionality
function setupChartToggle() {
  const toggleButtons = document.querySelectorAll(".toggle-btn");

  toggleButtons.forEach((btn) => {
    btn.addEventListener("click", function () {
      toggleButtons.forEach((b) => b.classList.remove("active"));
      this.classList.add("active");
      currentChartType = this.dataset.chart;
      initChart();
    });
  });
}

// Search functionality
function setupSearch() {
  const searchInput = document.getElementById("searchInput");

  if (searchInput) {
    searchInput.addEventListener("input", function (e) {
      const searchTerm = e.target.value.toLowerCase();
      // Add your search logic here
      console.log("Searching for:", searchTerm);
    });
  }
}

// Behavior form handling
function setupBehaviorForm() {
  const behaviorForm = document.getElementById("behaviorForm");

  if (behaviorForm) {
    behaviorForm.addEventListener("submit", function (e) {
      e.preventDefault();

      // Fields now exclude teacher name/subject
      const reportType =
        document.getElementById("report_type")?.value || "neutral";
      const category =
        document.getElementById("category")?.value.trim() || "General";
      const title = document.getElementById("title")?.value.trim() || "";
      const description =
        document.getElementById("description")?.value.trim() || "";

      if (!title || !description) return;

      addBehaviorReportCard({
        teacher_name: "Teacher",
        teacher_subject: "",
        report_type: reportType,
        category,
        title,
        description,
      });

      behaviorForm.reset();
    });
  }
}

// Create and prepend a behavior report card matching parentBehavior styles
function addBehaviorReportCard(report) {
  const list = document.querySelector(".behavior-report-list");
  if (!list) return;

  const typeIcons = { positive: "✓", neutral: "◉", concern: "⚠" };
  const dateStr = new Date().toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });

  const card = document.createElement("div");
  card.className = `behavior-report ${report.report_type}`;
  card.setAttribute("data-type", report.report_type);
  card.style.animation = "fadeIn 0.3s ease";

  card.innerHTML = `
    <div class="report-header">
      <div class="report-info">
        <div class="teacher-details">
          <span class="reporter">${escapeHtml(report.teacher_name)}</span>
        </div>
        <div class="report-meta">
          <span class="repo-date">${dateStr}</span>
          <span class="category-badge">${escapeHtml(report.category)}</span>
        </div>
      </div>
      <div class="report-type-indicator">
        <span class="type-badge ${report.report_type}">
          ${typeIcons[report.report_type] || "◉"}
          ${capitalize(report.report_type)}
        </span>
      </div>
    </div>
    ${
      report.title
        ? `<div class="report-title">${escapeHtml(report.title)}</div>`
        : ""
    }
    <div class="report-content">
      <p>${escapeHtml(report.description)}</p>
    </div>
  `;

  const heading = list.querySelector("h3.report-title");
  if (heading && heading.nextSibling) {
    list.insertBefore(card, heading.nextSibling);
  } else {
    list.appendChild(card);
  }
}

function escapeHtml(str) {
  if (str === null || str === undefined) return "";
  if (typeof str === "object") {
    console.error("escapeHtml received object:", str);
    return String(str);
  }
  const text = String(str);
  return text
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}

function capitalize(str) {
  return (str || "").charAt(0).toUpperCase() + (str || "").slice(1);
}

// Add behavior update to timeline
function addBehaviorUpdate(text) {
  const timeline = document.querySelector(".timeline");
  if (!timeline) return;

  const today = new Date();
  const dateStr = today.toLocaleDateString("en-US", {
    month: "short",
    day: "numeric",
    year: "numeric",
  });

  const newItem = document.createElement("div");
  newItem.className = "timeline-item";
  newItem.style.opacity = "0";
  newItem.style.transform = "translateY(-10px)";

  newItem.innerHTML = `
    <div class="timeline-marker positive"></div>
    <div class="timeline-content">
      <div class="timeline-date">${dateStr}</div>
      <p>${text}</p>
    </div>
  `;

  timeline.insertBefore(newItem, timeline.firstChild);

  // Animate in
  setTimeout(() => {
    newItem.style.transition = "all 0.3s ease";
    newItem.style.opacity = "1";
    newItem.style.transform = "translateY(0)";
  }, 10);
}

// Animate progress bars on load
function animateProgressBars() {
  const progressBars = document.querySelectorAll(".progress-fill");

  progressBars.forEach((bar, index) => {
    const width = bar.style.width;
    bar.style.width = "0";

    setTimeout(() => {
      bar.style.width = width;
    }, index * 50);
  });
}

// Initialize everything when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
  // Check if Chart.js is loaded
  if (typeof Chart === "undefined") {
    console.error(
      "Chart.js is not loaded. Please include Chart.js in your HTML.",
    );
    setupSearch();
    setupBehaviorForm();
    animateProgressBars();
    return;
  }

  loadMyMarksData().finally(() => {
    initChart();
    setupChartToggle();
  });

  setupSearch();
  setupBehaviorForm();
  animateProgressBars();
});

// Export functions for external use
window.reportModule = {
  initChart,
  addBehaviorUpdate,
};
