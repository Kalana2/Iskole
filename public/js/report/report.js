// Student data
let studentData = {
  subjects: [],
  terms: { term1: [], term2: [], term3: [] },
};

async function loadStudentData(studentId) {
  try {
    const response = await fetch(`/Report/getStudentMarks/${studentId}`);
    const data = await response.json();

    // Extract subjects from the model (these become chart labels)
    studentData.subjects = data.subjects.map((s) => s.name);

    // Convert PHP structure into term arrays
    studentData.terms = { term1: [], term2: [], term3: [] };

    for (const subjectName in data.terms) {
      const marks = data.terms[subjectName]; // [t1, t2, t3]
      studentData.terms.term1.push(marks[0] ?? null);
      studentData.terms.term2.push(marks[1] ?? null);
      studentData.terms.term3.push(marks[2] ?? null);
    }

    initChart(); // Draw chart with new data
  } catch (error) {
    console.error("Error loading student data:", error);
  }
}

// Chart.js configuration
let performanceChart = null;
let currentChartType = "line";
let currentTerm = "term3";

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
  const ctx = document.getElementById("performanceChart").getContext("2d");

  if (chartInstance) {
    chartInstance.destroy();
  }

  chartInstance = new Chart(ctx, {
    type: currentChartType === "radar" ? "radar" : "line",
    data: {
      labels: studentData.subjects, // <-- SUBJECT NAMES HERE
      datasets: [
        {
          label: "Term 1",
          data: studentData.terms.term1,
          borderWidth: 2,
          fill: false,
        },
        {
          label: "Term 2",
          data: studentData.terms.term2,
          borderWidth: 2,
          fill: false,
        },
        {
          label: "Term 3",
          data: studentData.terms.term3,
          borderWidth: 2,
          fill: false,
        },
      ],
    },
    options: {
      responsive: true,
      scales:
        currentChartType === "radar"
          ? {}
          : {
              y: {
                beginAtZero: true,
                suggestedMax: 100,
              },
            },
    },
  });
}

// Chart toggle functionality
function setupChartToggle() {
  const toggleButtons = document.querySelectorAll(".toggle-btn");
  // Term selector removed in My Marks view; keep null-safe code
  const termSelector = document.getElementById("termSelector");

  toggleButtons.forEach((btn) => {
    btn.addEventListener("click", function () {
      toggleButtons.forEach((b) => b.classList.remove("active"));
      this.classList.add("active");
      currentChartType = this.dataset.chart;
      initChart();
    });
  });
}

// Term selector functionality (retained for pages that still have it, safe no-op otherwise)
function setupTermSelector() {
  const termSelect = document.getElementById("term-select");
  if (termSelect) {
    termSelect.addEventListener("change", function (e) {
      currentTerm = e.target.value;
      initChart();
    });
  }
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
  const studentId = document.getElementById("studentId")?.value;

  if (studentId) {
    loadStudentData(studentId); // This will call initChart() internally
  } else {
    console.error("Student ID not found in DOM");
  }

  setupChartToggle();
  setupTermSelector();
  setupSearch();
  setupBehaviorForm();
  animateProgressBars();
});

// Export functions for external use
window.reportModule = {
  initChart,
  addBehaviorUpdate,
};
