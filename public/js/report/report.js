// Student data
const studentData = {
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
  const ctx = document.getElementById("performanceChart");
  if (!ctx) return;

  // Destroy existing chart if it exists
  if (performanceChart) {
    performanceChart.destroy();
  }

  const labels = studentData.subjects.map((s) => s.name);

  // Define datasets for all three terms
  const termDatasets = [
    {
      key: "term1",
      label: "Term 1",
      data: studentData.terms.term1,
      borderColor: "rgba(239, 68, 68, 0.8)",
      backgroundColor:
        currentChartType === "radar"
          ? "rgba(239, 68, 68, 0.15)"
          : "rgba(239, 68, 68, 0.1)",
    },
    {
      key: "term2",
      label: "Term 2",
      data: studentData.terms.term2,
      borderColor: "rgba(245, 158, 11, 0.8)",
      backgroundColor:
        currentChartType === "radar"
          ? "rgba(245, 158, 11, 0.15)"
          : "rgba(245, 158, 11, 0.1)",
    },
    {
      key: "term3",
      label: "Term 3",
      data: studentData.terms.term3,
      borderColor: "rgba(16, 185, 129, 0.8)",
      backgroundColor:
        currentChartType === "radar"
          ? "rgba(16, 185, 129, 0.15)"
          : "rgba(16, 185, 129, 0.1)",
    },
  ];

  // Apply highlighting based on selected term
  const datasets = termDatasets.map((dataset) => {
    const isSelected = dataset.key === currentTerm;
    return {
      label: dataset.label,
      data: dataset.data,
      borderColor: isSelected
        ? dataset.borderColor
        : dataset.borderColor.replace("0.8", "0.3"),
      backgroundColor: isSelected
        ? dataset.backgroundColor
        : dataset.backgroundColor.replace(/0\.1\d?/, "0.05"),
      borderWidth: isSelected ? 3 : 1,
      pointBackgroundColor: isSelected
        ? dataset.borderColor
        : dataset.borderColor.replace("0.8", "0.3"),
      pointBorderColor: "#fff",
      pointHoverBackgroundColor: "#fff",
      pointHoverBorderColor: dataset.borderColor,
      pointRadius: isSelected ? 4 : 3,
      tension: 0.4,
      fill: currentChartType === "line",
    };
  });

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
              const score = context.parsed.y || context.parsed.r;
              const subject = studentData.subjects[context.dataIndex];
              return `${context.dataset.label}: ${score}/100 (Grade: ${subject.grade})`;
            },
          },
        },
      },
      scales:
        currentChartType === "radar"
          ? {
              r: {
                beginAtZero: true,
                max: 100,
                ticks: {
                  stepSize: 20,
                  font: {
                    size: 11,
                  },
                },
                pointLabels: {
                  font: {
                    size: 11,
                    weight: "600",
                  },
                },
                grid: {
                  color: "rgba(0, 0, 0, 0.05)",
                },
              },
            }
          : currentChartType === "line"
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

      const textarea = document.getElementById("behavior-update");
      const behaviorText = textarea.value.trim();

      if (behaviorText) {
        // Add behavior update to timeline
        addBehaviorUpdate(behaviorText);
        textarea.value = "";
      }
    });
  }
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
  if (typeof Chart !== "undefined") {
    initChart();
    setupChartToggle();
    setupTermSelector();
  } else {
    console.error(
      "Chart.js is not loaded. Please include Chart.js in your HTML."
    );
  }

  setupSearch();
  setupBehaviorForm();
  animateProgressBars();
});

// Export functions for external use
window.reportModule = {
  initChart,
  addBehaviorUpdate,
};
