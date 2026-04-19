// Student Attendance Chart Module
// Handles visualization of attendance data using Chart.js

let attendanceChart = null;
let distributionChart = null;

// Color scheme
const colorScheme = {
  primary: "rgba(102, 126, 234, 0.8)",
  primaryLight: "rgba(102, 126, 234, 0.2)",
  secondary: "rgba(118, 75, 162, 0.8)",
  success: "rgba(16, 185, 129, 0.8)",
  successLight: "rgba(16, 185, 129, 0.2)",
  warning: "rgba(245, 158, 11, 0.8)",
  warningLight: "rgba(245, 158, 11, 0.2)",
  danger: "rgba(239, 68, 68, 0.8)",
  dangerLight: "rgba(239, 68, 68, 0.2)",
  info: "rgba(59, 130, 246, 0.8)",
};

// Initialize monthly attendance overview chart for parents and students view
function initAttendanceChart() {
  const ctx = document.getElementById("attendanceChart");
  if (!ctx || typeof attendanceData === "undefined") {
    console.error("Chart canvas or attendance data not found");
    return;
  }

  // Destroy existing chart if it exists
  if (attendanceChart) {
    attendanceChart.destroy();
  }

  // Use monthly data provided by backend: array of { month, present, absent, total }
  const monthlyData = attendanceData.monthly || [];
  const labels = monthlyData.map((d) => d.month);
  const presentData = monthlyData.map((d) => d.present);
  const absentData = monthlyData.map((d) => d.absent);
  const maxDays = monthlyData.length
    ? Math.max(...monthlyData.map((m) => m.total || m.present + m.absent))
    : 0;

  const config = {
    type: "bar",
    data: {
      labels: labels,
      datasets: [
        {
          label: "Present",
          data: presentData,
          backgroundColor: colorScheme.success,
          borderColor: "rgba(16, 185, 129, 1)",
          borderWidth: 2,
          borderRadius: 8,
        },
        {
          label: "Absent",
          data: absentData,
          backgroundColor: colorScheme.danger,
          borderColor: "rgba(239, 68, 68, 1)",
          borderWidth: 2,
          borderRadius: 8,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      interaction: {
        mode: "index",
        intersect: false,
      },
      plugins: {
        legend: {
          display: true,
          position: "top",
          align: "end",
          labels: {
            usePointStyle: true,
            padding: 15,
            font: {
              size: 12,
              weight: "600",
              family: "'Inter', sans-serif",
            },
            color: "#1f2543",
          },
        },
        tooltip: {
          backgroundColor: "rgba(0, 0, 0, 0.85)",
          padding: 12,
          cornerRadius: 8,
          titleFont: {
            size: 14,
            weight: "bold",
            family: "'Inter', sans-serif",
          },
          bodyFont: {
            size: 13,
            family: "'Inter', sans-serif",
          },
          callbacks: {
            title: function (context) {
              return context[0].label;
            },
            label: function (context) {
              const label = context.dataset.label || "";
              const value = context.parsed.y;
              const dataIndex = context.dataIndex;
              const total = weeklyData[dataIndex].total;
              const percentage =
                total > 0 ? ((value / total) * 100).toFixed(1) : 0;
              return `${label}: ${value} days (${percentage}%)`;
            },
          },
        },
      },
      scales: {
        x: {
          stacked: true,
          grid: {
            display: false,
          },
          ticks: {
            font: {
              size: 10,
              weight: "600",
              family: "'Inter', sans-serif",
            },
            color: "#6b7280",
          },
        },
        y: {
          stacked: true,
          beginAtZero: true,
          // Dynamically set max based on the largest total days in the monthly dataset
          max: maxDays > 0 ? Math.ceil(maxDays + 1) : undefined,
          grid: {
            color: "rgba(0, 0, 0, 0.05)",
            drawBorder: false,
          },
          ticks: {
            stepSize: 1,
            font: {
              size: 11,
              family: "'Inter', sans-serif",
            },
            color: "#6b7280",
            callback: function (value) {
              return value + " days";
            },
          },
        },
      },
    },
  };

  attendanceChart = new Chart(ctx, config);
}

// Initialize distribution pie chart
function initDistributionChart() {
  const ctx = document.getElementById("distributionChart");

  // attendanceData got from templates/studentAttendance.php scripts section
  if (!ctx || typeof attendanceData === "undefined") {
    console.error("Chart canvas or distribution data not found");
    return;
  }

  // Destroy existing chart if it exists
  if (distributionChart) {
    distributionChart.destroy();
  }

  const distribution = attendanceData.distribution;
  const total = distribution.present + distribution.absent;

  const config = {
    type: "doughnut",
    data: {
      labels: ["Present", "Absent"],
      datasets: [
        {
          data: [distribution.present, distribution.absent],
          backgroundColor: [colorScheme.success, colorScheme.danger],
          borderColor: "#ffffff",
          borderWidth: 3,
          hoverOffset: 8,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      cutout: "65%",
      plugins: {
        legend: {
          display: false,
        },
        tooltip: {
          backgroundColor: "rgba(0, 0, 0, 0.85)",
          padding: 12,
          cornerRadius: 8,
          titleFont: {
            size: 14,
            weight: "bold",
            family: "'Inter', sans-serif",
          },
          bodyFont: {
            size: 13,
            family: "'Inter', sans-serif",
          },
          callbacks: {
            label: function (context) {
              const label = context.label || "";
              const value = context.parsed;
              const percentage = ((value / total) * 100).toFixed(1);
              return `${label}: ${value} days (${percentage}%)`;
            },
          },
        },
      },
      animation: {
        animateScale: true,
        animateRotate: true,
      },
    },
  };

  distributionChart = new Chart(ctx, config);
}

// Animate progress bars on load
function animateProgressBars() {
  const progressBars = document.querySelectorAll(".progress-fill");

  progressBars.forEach((bar, index) => {
    const targetWidth = bar.style.width;
    bar.style.width = "0";

    setTimeout(() => {
      bar.style.transition = "width 0.8s ease";
      bar.style.width = targetWidth;
    }, index * 100);
  });
}

// Initialize everything when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
  // Check if Chart.js is loaded
  if (typeof Chart === "undefined") {
    console.error(
      "Chart.js is not loaded. Please include Chart.js in your HTML.",
    );
    return;
  }

  // Check if attendance data is available
  if (typeof attendanceData === "undefined") {
    console.error("Attendance data is not available.");
    return;
  }

  // Initialize charts
  initAttendanceChart();
  initDistributionChart();

  // Setup interactive features
  animateProgressBars();

  // Log successful initialization
  console.log("Student Attendance charts initialized successfully");
});
