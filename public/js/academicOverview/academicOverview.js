/* academicOverview.js
	 - Reads data attributes from canvases created in the PHP template
	 - Initializes a pass/fail pie chart and a subjects bar chart per grade
	 - Handles grade switching UI and scroll-to-top button
*/

document.addEventListener("DOMContentLoaded", function () {
  const gradeNav = document.getElementById("aoGradeNav");
  const classNav = document.getElementById("aoClassNav");
  const termNav = document.getElementById("aoTermNav");
  const gradeSectionsRoot = document.getElementById("aoGradeSections");

  let selectedClassId = null;
  let selectedGradeId = null;
  let selectedTerm = "1";

  // collect grade nav buttons and normalize dataset
  let gradeButtons = Array.from(document.querySelectorAll(".grade-nav-btn"));

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
        name,
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
              x: {
                grid: { display: false },
                ticks: {
                  maxRotation: 45,
                  minRotation: 45,
                },
              },
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

  function ensureGradeSection(gradeId, gradeValLabel) {
    if (!gradeSectionsRoot) return;
    if (document.getElementById(gradeId)) return;

    const section = document.createElement("div");
    section.className = "grade-section";
    section.id = gradeId;
    section.style.display = "none";
    section.innerHTML = `
      <div class="card">
        <div class="charts-grid">
          <div class="chart-container chart-bottom">
            <h3>Subject Performance</h3>
            <p class="chart-subtitle">Average scores across all subjects</p>
            <div class="chart-wrapper">
              <canvas id="${gradeId}-subjects-chart" data-subjects="{}"></canvas>
            </div>
          </div>
        </div>
      </div>
    `;

    gradeSectionsRoot.appendChild(section);
  }

  function renderGradeSections(grades) {
    if (!gradeSectionsRoot) return;
    gradeSectionsRoot.innerHTML = "";
    (grades || []).forEach((g) => {
      const gradeVal = g?.value ?? g?.label;
      if (
        gradeVal === undefined ||
        gradeVal === null ||
        String(gradeVal).trim() === ""
      )
        return;
      const gradeId = `grade-${gradeVal}`;
      ensureGradeSection(gradeId, String(gradeVal));
    });
  }

  async function updateSubjectsChartForClass(gradeId, classId) {
    if (!gradeId || !classId) return;

    initChartsForGrade(gradeId);
    const subjectsCanvasId = `${gradeId}-subjects-chart`;
    const chart = charts[subjectsCanvasId];
    if (!chart) return;

    try {
      const termPart = selectedTerm
        ? `&term=${encodeURIComponent(selectedTerm)}`
        : "";
      const payload = await fetchJson(
        `/academicOverview/getSubjectAverages?classID=${encodeURIComponent(classId)}${termPart}`,
      );
      const rows = payload?.data ?? [];
      const labels = rows.map((r) => r?.subjectName).filter(Boolean);
      const values = rows.map((r) => {
        const v = r?.averageMarks;
        const n = v === null || v === undefined || v === "" ? 0 : Number(v);
        return Number.isFinite(n) ? n : 0;
      });

      chart.data.labels = labels;
      if (chart.data.datasets?.[0]) {
        chart.data.datasets[0].data = values;

        // Re-apply gradient fill if needed.
        const ctx = chart.ctx;
        const area = chart.chartArea;
        if (ctx && area) {
          chart.data.datasets[0].backgroundColor = createBarGradient(ctx, area);
        }
      }

      chart.update();
    } catch (e) {
      console.warn("Failed to load subject averages:", e);
    }
  }

  function renderTermButtons() {
    if (!termNav) return;

    termNav.innerHTML = "";
    const terms = [
      { value: "1", label: "Term 1" },
      { value: "2", label: "Term 2" },
      { value: "3", label: "Term 3" },
    ];

    terms.forEach((t, idx) => {
      const btn = document.createElement("button");
      btn.type = "button";
      btn.className = "term-nav-btn";
      btn.dataset.term = t.value;
      btn.textContent = t.label;

      if (
        String(selectedTerm) === String(t.value) ||
        (!selectedTerm && idx === 0)
      ) {
        btn.classList.add("active");
        selectedTerm = String(t.value);
      }

      btn.addEventListener("click", () => {
        Array.from(termNav.querySelectorAll(".term-nav-btn")).forEach((b) =>
          b.classList.toggle("active", b === btn),
        );
        selectedTerm = String(t.value);

        if (selectedGradeId && selectedClassId) {
          updateSubjectsChartForClass(selectedGradeId, selectedClassId);
        }
      });

      termNav.appendChild(btn);
    });
  }

  // Show selected grade section and make sure charts are initialized
  async function showGrade(gradeId) {
    selectedGradeId = gradeId;
    document
      .querySelectorAll(".grade-section")
      .forEach((s) => (s.style.display = "none"));
    const node = document.getElementById(gradeId);
    if (node) {
      node.style.display = "block";
    } else {
      // Allow grades from DB even if template doesn't have a matching section.
      console.warn("Grade section not found:", gradeId);
    }

    // update active button
    gradeButtons.forEach((b) =>
      b.classList.toggle("active", b.dataset.grade === gradeId),
    );

    // initialize charts for this grade lazily (only if section exists)
    if (node) {
      initChartsForGrade(gradeId);

      // reveal chart containers with animation
      node
        .querySelectorAll(".chart-container")
        .forEach((c, i) =>
          setTimeout(() => c.classList.add("visible"), 80 + i * 80),
        );
    }

    // load class buttons for this grade
    const gradeNumMatch = String(gradeId).match(/grade-(\d+)/);
    const gradeNum = gradeNumMatch ? parseInt(gradeNumMatch[1], 10) : null;
    if (gradeNum) {
      await loadClassesForGrade(gradeNum);
      if (selectedClassId) {
        await updateSubjectsChartForClass(gradeId, selectedClassId);
      }
    } else {
      renderClassButtons([], null);
    }
  }

  function renderClassButtons(classes, gradeNum) {
    if (!classNav) return;
    classNav.innerHTML = "";
    selectedClassId = null;

    if (!gradeNum) {
      classNav.innerHTML =
        '<span class="ao-loading">Select a grade to load classes…</span>';
      return;
    }

    if (!Array.isArray(classes) || classes.length === 0) {
      classNav.innerHTML = '<span class="ao-loading">No classes found.</span>';
      return;
    }

    classes.forEach((c, idx) => {
      const classId = c?.classID;
      const section = c?.class;
      if (!classId || section === undefined || section === null) return;

      const btn = document.createElement("button");
      btn.type = "button";
      btn.className = "class-nav-btn";
      btn.dataset.classId = String(classId);
      btn.textContent = `${gradeNum}${String(section)}`;
      if (idx === 0) {
        btn.classList.add("active");
        selectedClassId = String(classId);
      }
      btn.addEventListener("click", () => {
        Array.from(classNav.querySelectorAll(".class-nav-btn")).forEach((b) =>
          b.classList.toggle("active", b === btn),
        );
        selectedClassId = String(classId);

        if (selectedGradeId && selectedClassId) {
          updateSubjectsChartForClass(selectedGradeId, selectedClassId);
        }
      });
      classNav.appendChild(btn);
    });

    if (selectedGradeId && selectedClassId) {
      updateSubjectsChartForClass(selectedGradeId, selectedClassId);
    }
  }

  async function loadClassesForGrade(gradeNum) {
    if (!classNav) return;
    classNav.innerHTML = '<span class="ao-loading">Loading classes…</span>';
    try {
      const payload = await fetchJson(
        `/academicOverview/getClasses?grade=${encodeURIComponent(gradeNum)}`,
      );
      const classes = payload?.data ?? [];
      renderClassButtons(classes, gradeNum);
    } catch (e) {
      console.warn("Failed to load classes:", e);
      classNav.innerHTML =
        '<span class="ao-loading">Failed to load classes.</span>';
    }
  }

  async function fetchJson(url) {
    const res = await fetch(url, {
      headers: { Accept: "application/json" },
      credentials: "same-origin",
    });

    const contentType = res.headers.get("content-type") || "";
    const text = await res.text();
    if (!res.ok) {
      throw new Error(
        `Request failed: ${res.status} ${res.statusText} (url=${res.url})`,
      );
    }

    try {
      return JSON.parse(text);
    } catch (e) {
      console.warn("Non-JSON response received", {
        url: res.url,
        redirected: res.redirected,
        contentType,
        preview: text.slice(0, 250),
      });
      throw new Error("Invalid JSON response");
    }
  }

  function renderGradeButtons(grades) {
    if (!gradeNav) return;
    gradeNav.innerHTML = "";

    if (!Array.isArray(grades) || grades.length === 0) {
      gradeNav.innerHTML = '<span class="ao-loading">No grades found.</span>';
      gradeButtons = [];
      return;
    }

    grades.forEach((g, idx) => {
      const gradeVal = g?.value ?? g?.label;
      if (
        gradeVal === undefined ||
        gradeVal === null ||
        String(gradeVal).trim() === ""
      )
        return;
      const gradeId = `grade-${gradeVal}`;

      ensureGradeSection(gradeId, String(gradeVal));

      const btn = document.createElement("button");
      btn.type = "button";
      btn.className = "grade-nav-btn";
      btn.dataset.grade = gradeId;
      btn.textContent = `Grade ${gradeVal}`;
      if (idx === 0) btn.classList.add("active");
      btn.addEventListener("click", () => showGrade(gradeId));
      gradeNav.appendChild(btn);
    });

    gradeButtons = Array.from(gradeNav.querySelectorAll(".grade-nav-btn"));
  }

  // initial grade to show
  (async () => {
    renderTermButtons();

    if (gradeNav) {
      try {
        const payload = await fetchJson("/academicOverview/getGrades");
        const grades = payload?.data ?? [];
        renderGradeSections(grades);
        renderGradeButtons(grades);

        const first = gradeButtons[0]?.dataset?.grade;
        if (first) showGrade(first);
      } catch (e) {
        console.warn("Failed to load grades:", e);
        gradeNav.innerHTML =
          '<span class="ao-loading">Failed to load grades.</span>';

        // fallback to existing markup/sections
        gradeButtons = Array.from(document.querySelectorAll(".grade-nav-btn"));
        const fallbackFirst =
          document.querySelector(".grade-nav-btn.active")?.dataset.grade ||
          gradeButtons[0]?.dataset.grade ||
          "grade-9";
        if (fallbackFirst) showGrade(fallbackFirst);
      }
      return;
    }

    const first =
      document.querySelector(".grade-nav-btn.active")?.dataset.grade ||
      gradeButtons[0]?.dataset.grade ||
      "grade-9";
    if (first) showGrade(first);
  })();

  // scroll-to-top behavior
  const scrollBtn = document.getElementById("scrollToTopBtn");
  if (scrollBtn) {
    window.addEventListener("scroll", () => {
      scrollBtn.classList.toggle("visible", window.scrollY > 300);
    });
    scrollBtn.addEventListener("click", () =>
      window.scrollTo({ top: 0, behavior: "smooth" }),
    );
  }

  // resize handling to update charts
  let resizeTimer = null;
  window.addEventListener("resize", () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
      Object.values(charts).forEach(
        (c) => c && typeof c.resize === "function" && c.resize(),
      );
    }, 200);
  });
});
