/* academicOverview.js
	 - Reads data attributes from canvases created in the PHP template
	 - Initializes a pass/fail pie chart and a subjects bar chart per grade
	 - Handles grade switching UI and scroll-to-top button
*/

document.addEventListener("DOMContentLoaded", function () {
  // collect grade nav buttons and normalize dataset
  const gradeButtons = Array.from(document.querySelectorAll(".grade-nav-btn"));

  gradeButtons.forEach((btn) => {
    const onclick = btn.getAttribute("onclick") || "";
    const match = onclick.match(/showGrade\('([^']+)'\)/);
    if (match) btn.dataset.grade = match[1];
    // allow data-grade override
    btn.addEventListener("click", (e) => {
      const g = btn.dataset.grade;
      if (g) showGrade(g);
    });
  });

  // store initialized Chart instances by canvas id
  const charts = {};

  // helpers: read CSS vars and create gradients/colors
  function getCSSVar(name, fallback) {
    try {
      const v = getComputedStyle(document.documentElement).getPropertyValue(
        name
      );
      return v ? v.trim() : fallback;
    } catch (e) {
      return fallback;
    }
  }

  function hexToRgba(hex, a = 1) {
    if (!hex) return `rgba(37,99,235,${a})`;
    const h = hex.replace("#", "").trim();
    const full =
      h.length === 3
        ? h
            .split("")
            .map((c) => c + c)
            .join("")
        : h;
    const bigint = parseInt(full, 16);
    const r = (bigint >> 16) & 255;
    const g = (bigint >> 8) & 255;
    const b = bigint & 255;
    return `rgba(${r},${g},${b},${a})`;
  }

  function createBarGradient(ctx, area) {
    const primary = getCSSVar("--primary-color", "#2563eb");
    const g = ctx.createLinearGradient(0, area.top, 0, area.bottom);
    g.addColorStop(0, hexToRgba(primary, 0.95));
    g.addColorStop(1, hexToRgba(primary, 0.7));
    return g;
  }

  // center text plugin for pass/fail pie to show pass %%
  const centerTextPlugin = {
    id: "centerText",
    afterDraw: (chart) => {
      if (chart.config.type !== "pie") return;
      const datasets = chart.data.datasets[0];
      const passVal = datasets.data[0];
      const ctx = chart.ctx;
      ctx.save();
      const centerX = (chart.chartArea.left + chart.chartArea.right) / 2;
      const centerY = (chart.chartArea.top + chart.chartArea.bottom) / 2;
      // derive theme colors and font from CSS
      const textColor = getCSSVar("--text-color", "#0f172a");
      const mutedColor = hexToRgba(getCSSVar("--text-color", "#6b7280"), 0.6);
      const fontFamily =
        getComputedStyle(document.body).fontFamily || "Inter, system-ui, Arial";
      ctx.fillStyle = textColor;
      ctx.textAlign = "center";
      ctx.textBaseline = "middle";
      ctx.font = `700 18px ${fontFamily}`;
      ctx.fillText(passVal + "%", centerX, centerY - 6);
      ctx.font = `400 12px ${fontFamily}`;
      ctx.fillStyle = mutedColor;
      ctx.fillText("Pass rate", centerX, centerY + 16);
      ctx.restore();
    },
  };

  // register plugin globally if Chart is available
  if (typeof Chart !== "undefined" && Chart.register) {
    try {
      Chart.register(centerTextPlugin);
    } catch (e) {
      /* ignore duplicates */
    }
  }

  function initChartsForGrade(gradeId) {
    // Pass/Fail pie
    const passCanvas = document.querySelector(`#${gradeId}-pass-fail-chart`);
    if (passCanvas && !charts[passCanvas.id]) {
      const pass = parseFloat(passCanvas.dataset.pass) || 0;
      const fail = parseFloat(passCanvas.dataset.fail);
      const failVal = isNaN(fail) ? Math.max(0, 100 - pass) : fail;
      try {
        const ctx = passCanvas.getContext("2d");
        // use primary color for pass slice (blue) to match theme
        const successColor = getCSSVar("--primary-color", "#2563eb");
        const dangerColor = getCSSVar("--danger", "#ef4444");
        charts[passCanvas.id] = new Chart(ctx, {
          type: "pie",
          data: {
            labels: ["Pass", "Fail"],
            datasets: [
              {
                data: [pass, failVal],
                backgroundColor: [
                  hexToRgba(successColor, 1),
                  hexToRgba(dangerColor, 1),
                ],
                hoverOffset: 10,
                borderWidth: 0,
              },
            ],
          },
          options: {
            maintainAspectRatio: false,
            animation: { duration: 900, easing: "easeOutQuart" },
            plugins: {
              legend: {
                position: "bottom",
                labels: { boxWidth: 12, padding: 12 },
              },
              tooltip: {
                callbacks: {
                  label: function (ctx) {
                    return ctx.label + ": " + ctx.formattedValue + "%";
                  },
                },
              },
            },
          },
          plugins: [centerTextPlugin],
        });
      } catch (e) {
        console.warn("Chart init failed (pass/fail):", e);
      }
    }

    // Subjects bar (or radar) chart
    const subjectsCanvas = document.querySelector(`#${gradeId}-subjects-chart`);
    if (subjectsCanvas && !charts[subjectsCanvas.id]) {
      let subjects = {};
      try {
        subjects = JSON.parse(subjectsCanvas.dataset.subjects || "{}");
      } catch (e) {
        subjects = {};
      }
      const labels = Object.keys(subjects);
      const values = labels.map((l) => parseFloat(subjects[l]) || 0);
      try {
        const ctx2 = subjectsCanvas.getContext("2d");
        // build gradient after layout
        const dataset = {
          label: "Average (%)",
          data: values,
          borderColor: getCSSVar("--primary-color", "rgba(37,99,235,0.95)"),
          borderWidth: 0,
          borderRadius: 6,
          // make bars slimmer and responsive
          barThickness: 70, // preferred thickness in pixels
          maxBarThickness: 80, // do not exceed on large screens
          categoryPercentage: 0.75,
          barPercentage: 0.85,
        };

        charts[subjectsCanvas.id] = new Chart(ctx2, {
          type: "bar",
          data: { labels, datasets: [dataset] },
          options: {
            maintainAspectRatio: false,
            animation: { duration: 900, easing: "easeOutQuart" },
            plugins: {
              legend: { display: false },
              tooltip: {
                callbacks: { label: (ctx) => ctx.formattedValue + "%" },
              },
            },
            scales: {
              y: { beginAtZero: true, max: 100, ticks: { stepSize: 10 } },
              x: { grid: { display: false } },
            },
          },
        });

        // apply gradient fill once area available
        const chartInstance = charts[subjectsCanvas.id];
        const area = chartInstance.chartArea;
        if (area) {
          dataset.backgroundColor = createBarGradient(ctx2, area);
          chartInstance.update();
        } else {
          // fallback: wait briefly then set gradient
          setTimeout(() => {
            const a = chartInstance.chartArea;
            if (a) {
              dataset.backgroundColor = createBarGradient(ctx2, a);
              chartInstance.update();
            }
          }, 250);
        }
      } catch (e) {
        console.warn("Chart init failed (subjects):", e);
      }
    }
  }

  // Show selected grade section and make sure charts are initialized
  function showGrade(gradeId) {
    document
      .querySelectorAll(".grade-section")
      .forEach((s) => (s.style.display = "none"));
    const node = document.getElementById(gradeId);
    if (!node) {
      console.warn("Grade not found:", gradeId);
      return;
    }
    node.style.display = "block";

    // update active button
    gradeButtons.forEach((b) =>
      b.classList.toggle("active", b.dataset.grade === gradeId)
    );

    // initialize charts for this grade lazily
    initChartsForGrade(gradeId);

    // reveal chart containers with animation
    node
      .querySelectorAll(".chart-container")
      .forEach((c, i) =>
        setTimeout(() => c.classList.add("visible"), 80 + i * 80)
      );
  }

  // initial grade to show (first active or first button)
  const first =
    document.querySelector(".grade-nav-btn.active")?.dataset.grade ||
    gradeButtons[0]?.dataset.grade ||
    "grade-9";
  if (first) showGrade(first);

  // scroll-to-top behavior
  const scrollBtn = document.getElementById("scrollToTopBtn");
  if (scrollBtn) {
    window.addEventListener("scroll", () => {
      scrollBtn.classList.toggle("visible", window.scrollY > 300);
    });
    scrollBtn.addEventListener("click", () =>
      window.scrollTo({ top: 0, behavior: "smooth" })
    );
  }

  // resize handling to update charts
  let resizeTimer = null;
  window.addEventListener("resize", () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
      Object.values(charts).forEach(
        (c) => c && typeof c.resize === "function" && c.resize()
      );
    }, 200);
  });
});
