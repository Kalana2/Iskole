(function () {
  const section = document.querySelector(".mp-academic");
  if (!section) return;

  // Chart.js default configuration
  Chart.defaults.font.family =
    "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif";
  Chart.defaults.color = "#6b7280";

  // Initialize Overview Chart
  const initOverviewChart = () => {
    const ctx = document.getElementById("gradeOverviewChart");
    if (!ctx) return;

    const data = {
      labels: ["Grade 9", "Grade 8", "Grade 7", "Grade 6"],
      datasets: [
        {
          label: "Average Score",
          data: [82.3, 82.3, 82.3, 82.3],
          backgroundColor: "rgba(102, 126, 234, 0.2)",
          borderColor: "#667eea",
          borderWidth: 2,
          tension: 0.4,
          fill: true,
          pointBackgroundColor: "#667eea",
          pointBorderColor: "#fff",
          pointBorderWidth: 2,
          pointRadius: 5,
          pointHoverRadius: 7,
        },
        {
          label: "75%+ Students",
          data: [71.2, 85.1, 59.1, 60.5],
          backgroundColor: "rgba(118, 75, 162, 0.2)",
          borderColor: "#764ba2",
          borderWidth: 2,
          tension: 0.4,
          fill: true,
          pointBackgroundColor: "#764ba2",
          pointBorderColor: "#fff",
          pointBorderWidth: 2,
          pointRadius: 5,
          pointHoverRadius: 7,
        },
        {
          label: "30%- Students",
          data: [8.7, 1.3, 3.4, 10.7],
          backgroundColor: "rgba(220, 53, 69, 0.2)",
          borderColor: "#dc3545",
          borderWidth: 2,
          tension: 0.4,
          fill: true,
          pointBackgroundColor: "#dc3545",
          pointBorderColor: "#fff",
          pointBorderWidth: 2,
          pointRadius: 5,
          pointHoverRadius: 7,
        },
      ],
    };

    new Chart(ctx, {
      type: "line",
      data: data,
      options: {
        responsive: true,
        maintainAspectRatio: true,
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
            backgroundColor: "rgba(31, 37, 67, 0.95)",
            padding: 12,
            borderColor: "#667eea",
            borderWidth: 1,
            titleFont: {
              size: 13,
              weight: "600",
            },
            bodyFont: {
              size: 12,
            },
            displayColors: true,
            callbacks: {
              label: function (context) {
                return context.dataset.label + ": " + context.parsed.y + "%";
              },
            },
          },
        },
        scales: {
          y: {
            beginAtZero: true,
            max: 100,
            ticks: {
              callback: function (value) {
                return value + "%";
              },
              font: {
                size: 11,
              },
            },
            grid: {
              color: "rgba(0, 0, 0, 0.05)",
              drawBorder: false,
            },
          },
          x: {
            grid: {
              display: false,
            },
            ticks: {
              font: {
                size: 11,
                weight: "600",
              },
            },
          },
        },
        interaction: {
          intersect: false,
          mode: "index",
        },
      },
    });
  };

  // Initialize Grade Summary Charts
  const initGradeSummaryCharts = () => {
    const summaryCanvases = section.querySelectorAll('[id$="-summary-chart"]');

    summaryCanvases.forEach((canvas) => {
      const avg = parseFloat(canvas.dataset.avg) || 0;
      const high = parseFloat(canvas.dataset.high) || 0;
      const low = parseFloat(canvas.dataset.low) || 0;
      const mid = 100 - high - low;

      new Chart(canvas, {
        type: "doughnut",
        data: {
          labels: ["75%+ Students", "30-75% Students", "30%- Students"],
          datasets: [
            {
              data: [high, mid, low],
              backgroundColor: ["#667eea", "#e5e7eb", "#dc3545"],
              borderWidth: 0,
              hoverOffset: 8,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: true,
              position: "bottom",
              labels: {
                usePointStyle: true,
                padding: 12,
                font: {
                  size: 11,
                  weight: "600",
                },
                generateLabels: function (chart) {
                  const data = chart.data;
                  if (data.labels.length && data.datasets.length) {
                    return data.labels.map((label, i) => {
                      const value = data.datasets[0].data[i];
                      return {
                        text: `${label}: ${value.toFixed(1)}%`,
                        fillStyle: data.datasets[0].backgroundColor[i],
                        hidden: false,
                        index: i,
                      };
                    });
                  }
                  return [];
                },
              },
            },
            tooltip: {
              backgroundColor: "rgba(31, 37, 67, 0.95)",
              padding: 10,
              borderColor: "#667eea",
              borderWidth: 1,
              callbacks: {
                label: function (context) {
                  return context.label + ": " + context.parsed.toFixed(1) + "%";
                },
              },
            },
          },
          cutout: "65%",
        },
      });
    });
  };

  // Initialize subject charts for each grade
  const initSubjectCharts = (gradeId, subjects) => {
    subjects.forEach((subject, index) => {
      const canvasId = `${gradeId}-subject-${index}`;
      const canvas = document.getElementById(canvasId);
      if (!canvas) return;

      const ctx = canvas.getContext("2d");
      new Chart(ctx, {
        type: "doughnut",
        data: {
          labels: ["75%+ Students", "30-75% Students", "30%- Students"],
          datasets: [
            {
              data: [
                subject.high,
                100 - subject.high - subject.low,
                subject.low,
              ],
              backgroundColor: ["#667eea", "#e5e7eb", "#dc3545"],
              borderWidth: 0,
              hoverOffset: 8,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: true,
          plugins: {
            legend: {
              display: false,
            },
            tooltip: {
              backgroundColor: "rgba(31, 37, 67, 0.95)",
              padding: 10,
              borderColor: "#667eea",
              borderWidth: 1,
              callbacks: {
                label: function (context) {
                  return context.label + ": " + context.parsed + "%";
                },
              },
            },
          },
          cutout: "70%",
        },
      });
    });
  };

  // Hide detailed boxes initially
  section.querySelectorAll('[class^="hide-box-"]').forEach((el) => {
    el.style.display = "none";
    el.classList.remove("expanded");
  });

  // Hide all grades except the first one initially
  const allGrades = section.querySelectorAll(".grade");
  allGrades.forEach((grade, index) => {
    if (index === 0) {
      grade.style.display = "block";
      grade.classList.add("active-grade");
    } else {
      grade.style.display = "none";
      grade.classList.remove("active-grade");
    }
  });

  // Set first button as active
  const firstBtn = section.querySelector(".grade-nav-btn");
  if (firstBtn) {
    firstBtn.classList.add("active");
  }

  // Toggle handler with smooth animations
  window.toggleSeeMore = function (id) {
    const target = section.querySelector(".hide-box-" + id);
    const btn = section.querySelector(".see-more-" + id);
    if (!target || !btn) return;

    const isHidden = !target.classList.contains("expanded");

    if (isHidden) {
      // Expand
      target.style.display = "block";
      setTimeout(() => {
        target.classList.add("expanded");
        btn.classList.add("expanded");
        btn.textContent = "See Less ";

        // Smooth scroll to show expanded content
        setTimeout(() => {
          const rect = target.getBoundingClientRect();
          const scrollTop =
            window.pageYOffset || document.documentElement.scrollTop;
          const targetTop = rect.top + scrollTop - 100;

          window.scrollTo({
            top: targetTop,
            behavior: "smooth",
          });
        }, 300);
      }, 10);
    } else {
      // Collapse
      target.classList.remove("expanded");
      btn.classList.remove("expanded");
      btn.textContent = "See More ...";

      setTimeout(() => {
        target.style.display = "none";
      }, 400);

      // Scroll to grade heading
      const gradeElement = section.querySelector("#" + id);
      if (gradeElement) {
        setTimeout(() => {
          gradeElement.scrollIntoView({ behavior: "smooth", block: "start" });
        }, 100);
      }
    }
  };

  // Initialize linear progress widths if not provided
  section.querySelectorAll(".subject-avg .progress-container").forEach((pc) => {
    const bar = pc.querySelector(".progress-bar");
    const textEl = pc.querySelector(".progress-text");
    if (bar && textEl && !bar.style.width) {
      const m = (textEl.textContent || "").match(/([\d.]+)%/);
      if (m) bar.style.width = m[1] + "%";
    }
  });

  // Initialize circular progress backgrounds if not provided
  section.querySelectorAll(".comparison .circular-progress").forEach((cp) => {
    if (cp.getAttribute("style")) return; // skip if inline style already set
    const txt = cp.textContent || "";
    const m = txt.match(/([\d.]+)%/);
    if (!m) return;
    const val = parseFloat(m[1]);
    const isRed = !!cp.closest(".lower-than-30");
    const color = isRed ? "#dc3545" : "#667eea";
    cp.style.background = `conic-gradient(${color} 0% ${val}%, #e5e7eb ${val}% 100%)`;
  });

  // Initialize charts when DOM is ready
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
      setTimeout(() => {
        initOverviewChart();
        initGradeSummaryCharts();
      }, 100);
    });
  } else {
    setTimeout(() => {
      initOverviewChart();
      initGradeSummaryCharts();
    }, 100);
  }

  // Add click animations to grade boxes
  section.querySelectorAll(".grade-box").forEach((box) => {
    box.addEventListener("mouseenter", function () {
      this.style.transform = "translateY(-4px)";
      this.style.transition = "all 0.3s cubic-bezier(0.4, 0, 0.2, 1)";
    });
    box.addEventListener("mouseleave", function () {
      this.style.transform = "translateY(0)";
    });
  });

  // Make grade headings clickable to scroll to grade
  section.querySelectorAll(".grade-heading").forEach((heading) => {
    heading.addEventListener("click", function () {
      const gradeElement = this.closest(".grade");
      if (gradeElement) {
        gradeElement.scrollIntoView({
          behavior: "smooth",
          block: "start",
        });

        // Add pulse animation
        gradeElement.style.animation = "none";
        setTimeout(() => {
          gradeElement.style.animation = "pulse 0.6s ease";
        }, 10);
      }
    });
  });

  // Add pulse animation
  const style = document.createElement("style");
  style.textContent = `
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.01); }
        }
        
        .grade-box {
            animation: fadeInUp 0.5s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .row {
            animation: fadeInUp 0.6s ease-out;
        }
        
        .row:nth-child(2) { animation-delay: 0.1s; }
        .row:nth-child(3) { animation-delay: 0.2s; }
        .row:nth-child(4) { animation-delay: 0.3s; }
        .row:nth-child(5) { animation-delay: 0.4s; }
    `;
  document.head.appendChild(style);

  // Animate progress bars on scroll
  const animateProgressBars = () => {
    const progressBars = section.querySelectorAll(".progress-bar");
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (
            entry.isIntersecting &&
            !entry.target.classList.contains("animated")
          ) {
            const width = entry.target.style.width || "0%";
            entry.target.style.width = "0%";
            setTimeout(() => {
              entry.target.style.width = width;
              entry.target.classList.add("animated");
            }, 100);
          }
        });
      },
      { threshold: 0.5 }
    );

    progressBars.forEach((bar) => observer.observe(bar));
  };

  animateProgressBars();

  // Animate circular progress on scroll
  const animateCircularProgress = () => {
    const circles = section.querySelectorAll(".circular-progress");
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (
            entry.isIntersecting &&
            !entry.target.classList.contains("animated")
          ) {
            const bg = entry.target.style.background;
            entry.target.style.background = "conic-gradient(#e5e7eb 0% 100%)";
            setTimeout(() => {
              entry.target.style.background = bg;
              entry.target.classList.add("animated");
            }, 150);
          }
        });
      },
      { threshold: 0.5 }
    );

    circles.forEach((circle) => observer.observe(circle));
  };

  animateCircularProgress();

  // Scroll to grade function with smooth animation and show only selected grade
  window.scrollToGrade = function (gradeId) {
    const gradeElement = document.getElementById(gradeId);
    if (!gradeElement) return;

    // Update active button
    section.querySelectorAll(".grade-nav-btn").forEach((btn) => {
      btn.classList.remove("active");
    });

    const activeBtn = Array.from(
      section.querySelectorAll(".grade-nav-btn")
    ).find((btn) =>
      btn.textContent.toLowerCase().includes(gradeId.replace("grade-", ""))
    );
    if (activeBtn) {
      activeBtn.classList.add("active");
    }

    // Hide all grades with fade out
    const allGrades = section.querySelectorAll(".grade");
    allGrades.forEach((grade) => {
      if (grade.id !== gradeId) {
        grade.style.animation = "fadeOut 0.3s ease-out";
        grade.classList.remove("active-grade");
        setTimeout(() => {
          grade.style.display = "none";
        }, 300);
      }
    });

    // Show selected grade with fade in
    setTimeout(() => {
      gradeElement.style.display = "block";
      gradeElement.style.animation = "fadeInScale 0.5s ease-out";
      gradeElement.classList.add("active-grade");

      // Smooth scroll to top of card section
      setTimeout(() => {
        const cardElement = section.querySelector(".card");
        if (cardElement) {
          const offset = 20;
          const elementPosition = cardElement.getBoundingClientRect().top;
          const offsetPosition = elementPosition + window.pageYOffset - offset;

          window.scrollTo({
            top: offsetPosition,
            behavior: "smooth",
          });
        }
      }, 100);
    }, 350);
  };

  // Intersection Observer for active grade highlighting
  const observeGrades = () => {
    const options = {
      root: null,
      rootMargin: "-100px 0px -60% 0px",
      threshold: 0,
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const gradeId = entry.target.id;
          section.querySelectorAll(".grade-nav-btn").forEach((btn) => {
            btn.classList.remove("active");
          });

          const activeBtn = Array.from(
            section.querySelectorAll(".grade-nav-btn")
          ).find((btn) =>
            btn.textContent
              .toLowerCase()
              .includes(gradeId.replace("grade-", ""))
          );
          if (activeBtn) {
            activeBtn.classList.add("active");
          }
        }
      });
    }, options);

    section.querySelectorAll(".grade").forEach((grade) => {
      observer.observe(grade);
    });
  };

  observeGrades();

  // Add highlight animation style
  const highlightStyle = document.createElement("style");
  highlightStyle.textContent = `
        @keyframes highlightGrade {
            0%, 100% { 
                box-shadow: 0 2px 8px rgba(0,0,0,.06);
            }
            50% { 
                box-shadow: 0 0 0 4px rgba(102,126,234,.2),
                           0 8px 24px rgba(102,126,234,.2);
            }
        }
    `;
  document.head.appendChild(highlightStyle);

  // Add smooth scroll behavior to entire page
  document.documentElement.style.scrollBehavior = "smooth";

  // Stagger animation for grade boxes
  const staggerGradeBoxes = () => {
    const boxes = section.querySelectorAll(".grade-box");
    boxes.forEach((box, index) => {
      box.style.animationDelay = `${index * 0.1}s`;
    });
  };

  staggerGradeBoxes();

  // Scroll to Top functionality
  const scrollToTopBtn = document.getElementById("scrollToTopBtn");
  if (scrollToTopBtn) {
    window.addEventListener("scroll", () => {
      if (window.pageYOffset > 300) {
        scrollToTopBtn.classList.add("visible");
      } else {
        scrollToTopBtn.classList.remove("visible");
      }
    });

    scrollToTopBtn.addEventListener("click", () => {
      window.scrollTo({
        top: 0,
        behavior: "smooth",
      });
    });
  }

  // Add entrance animations for cards on scroll
  const animateOnScroll = () => {
    const elements = section.querySelectorAll(".grade, .chart-container");
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (
            entry.isIntersecting &&
            !entry.target.classList.contains("aos-animated")
          ) {
            entry.target.style.animation = "fadeInUp 0.6s ease-out forwards";
            entry.target.classList.add("aos-animated");
          }
        });
      },
      {
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px",
      }
    );

    elements.forEach((el) => observer.observe(el));
  };

  animateOnScroll();

  // Keyboard navigation support
  document.addEventListener("keydown", (e) => {
    if (e.key === "ArrowUp" && e.ctrlKey) {
      e.preventDefault();
      window.scrollTo({ top: 0, behavior: "smooth" });
    }
  });

  // Add loading complete animation
  section.style.opacity = "0";
  setTimeout(() => {
    section.style.transition = "opacity 0.5s ease";
    section.style.opacity = "1";
  }, 100);
})();
