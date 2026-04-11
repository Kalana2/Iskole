/* academicOverview.js
   - Loads grade, class, and term options from DB-backed endpoints
   - Uses dropdowns (markEntry-style) and defaults to first available values
   - Updates subject performance chart based on selected filters
*/

document.addEventListener("DOMContentLoaded", function () {
  const gradeSelect = document.getElementById("aoGradeSelect");
  const classSelect = document.getElementById("aoClassSelect");
  const termSelect = document.getElementById("aoTermSelect");
  const gradeSectionsRoot = document.getElementById("aoGradeSections");

  let selectedClassId = "";
  let selectedGradeId = "";
  let selectedTerm = "";

  const charts = {};

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

  if (typeof Chart !== "undefined" && Chart.register) {
    try {
      Chart.register(centerTextPlugin);
    } catch (e) {
      // ignore duplicate plugin registration
    }
  }

  function initChartsForGrade(gradeId) {
    const subjectsCanvas = document.querySelector(`#${gradeId}-subjects-chart`);
    if (!subjectsCanvas || charts[subjectsCanvas.id]) {
      return;
    }

    let subjects = {};
    try {
      subjects = JSON.parse(subjectsCanvas.dataset.subjects || "{}");
    } catch (e) {
      subjects = {};
    }

    const labels = Object.keys(subjects);
    const values = labels.map((l) => parseFloat(subjects[l]) || 0);

    try {
      const ctx = subjectsCanvas.getContext("2d");
      const dataset = {
        label: "Average (%)",
        data: values,
        borderColor: getCSSVar("--primary-color", "rgba(37,99,235,0.95)"),
        borderWidth: 0,
        borderRadius: 6,
        barThickness: 70,
        maxBarThickness: 80,
        categoryPercentage: 0.75,
        barPercentage: 0.85,
      };

      charts[subjectsCanvas.id] = new Chart(ctx, {
        type: "bar",
        data: { labels, datasets: [dataset] },
        options: {
          maintainAspectRatio: false,
          animation: { duration: 900, easing: "easeOutQuart" },
          plugins: {
            legend: { display: false },
            tooltip: {
              callbacks: { label: (chartCtx) => chartCtx.formattedValue + "%" },
            },
          },
          scales: {
            y: { beginAtZero: true, max: 100, ticks: { stepSize: 10 } },
            x: {
              grid: { display: false },
              ticks: { maxRotation: 45, minRotation: 45 },
            },
          },
        },
      });

      const chartInstance = charts[subjectsCanvas.id];
      const area = chartInstance.chartArea;
      if (area) {
        dataset.backgroundColor = createBarGradient(ctx, area);
        chartInstance.update();
      } else {
        setTimeout(() => {
          const delayedArea = chartInstance.chartArea;
          if (delayedArea) {
            dataset.backgroundColor = createBarGradient(ctx, delayedArea);
            chartInstance.update();
          }
        }, 250);
      }
    } catch (e) {
      console.warn("Chart init failed (subjects):", e);
    }
  }

  function ensureGradeSection(gradeId) {
    if (!gradeSectionsRoot || document.getElementById(gradeId)) {
      return;
    }

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
      ) {
        return;
      }
      ensureGradeSection(`grade-${gradeVal}`);
    });
  }

  function showGradeSection(gradeId) {
    selectedGradeId = gradeId;
    document.querySelectorAll(".grade-section").forEach((s) => {
      s.style.display = "none";
    });

    const node = document.getElementById(gradeId);
    if (!node) return;

    node.style.display = "block";
    initChartsForGrade(gradeId);
    node.querySelectorAll(".chart-container").forEach((c, i) => {
      setTimeout(() => c.classList.add("visible"), 80 + i * 80);
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
        const area = chart.chartArea;
        if (chart.ctx && area) {
          chart.data.datasets[0].backgroundColor = createBarGradient(
            chart.ctx,
            area,
          );
        }
      }

      chart.update();
    } catch (e) {
      console.warn("Failed to load subject averages:", e);
    }
  }

  function setSelectOptions(selectEl, options, valueKey, labelBuilder) {
    if (!selectEl) return;
    selectEl.innerHTML = "";
    (options || []).forEach((item) => {
      const value = item?.[valueKey];
      if (
        value === undefined ||
        value === null ||
        String(value).trim() === ""
      ) {
        return;
      }
      const option = document.createElement("option");
      option.value = String(value);
      option.textContent = labelBuilder(item);
      selectEl.appendChild(option);
    });
  }

  async function loadClassesForGrade(gradeNum) {
    if (!classSelect) return [];
    classSelect.disabled = true;
    classSelect.innerHTML = '<option value="">Loading classes...</option>';

    try {
      const payload = await fetchJson(
        `/academicOverview/getClasses?grade=${encodeURIComponent(gradeNum)}`,
      );
      const classes = payload?.data ?? [];
      setSelectOptions(
        classSelect,
        classes,
        "classID",
        (item) => `Class ${item.class}`,
      );
      classSelect.disabled = classes.length === 0;
      return classes;
    } catch (e) {
      console.warn("Failed to load classes:", e);
      classSelect.innerHTML =
        '<option value="">Failed to load classes</option>';
      classSelect.disabled = true;
      return [];
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

  async function handleGradeChange() {
    const gradeValue = gradeSelect?.value || "";
    if (!gradeValue) {
      selectedGradeId = "";
      return;
    }

    const gradeId = `grade-${gradeValue}`;
    showGradeSection(gradeId);

    const classes = await loadClassesForGrade(gradeValue);
    if (classes.length === 0) {
      selectedClassId = "";
      return;
    }

    classSelect.value = String(classes[0].classID);
    selectedClassId = classSelect.value;
    await updateSubjectsChartForClass(gradeId, selectedClassId);
  }

  if (gradeSelect) {
    gradeSelect.addEventListener("change", handleGradeChange);
  }

  if (classSelect) {
    classSelect.addEventListener("change", async () => {
      selectedClassId = classSelect.value || "";
      if (selectedGradeId && selectedClassId) {
        await updateSubjectsChartForClass(selectedGradeId, selectedClassId);
      }
    });
  }

  if (termSelect) {
    termSelect.addEventListener("change", async () => {
      selectedTerm = termSelect.value || "";
      if (selectedGradeId && selectedClassId) {
        await updateSubjectsChartForClass(selectedGradeId, selectedClassId);
      }
    });
  }

  (async () => {
    try {
      const [gradesPayload, termsPayload] = await Promise.all([
        fetchJson("/academicOverview/getGrades"),
        fetchJson("/academicOverview/getTerms"),
      ]);

      const grades = gradesPayload?.data ?? [];
      const terms = termsPayload?.data ?? [];

      renderGradeSections(grades);

      setSelectOptions(
        gradeSelect,
        grades,
        "value",
        (item) => `Grade ${item.label ?? item.value}`,
      );

      setSelectOptions(
        termSelect,
        terms,
        "value",
        (item) => item.label ?? item.value,
      );

      if (terms.length > 0 && termSelect) {
        termSelect.value = String(terms[0].value);
        selectedTerm = termSelect.value;
      }

      if (grades.length > 0 && gradeSelect) {
        gradeSelect.value = String(grades[0].value);
        await handleGradeChange();
      } else {
        gradeSelect.innerHTML = '<option value="">No grades found</option>';
        classSelect.innerHTML = '<option value="">No classes found</option>';
      }

      if (!terms.length && termSelect) {
        termSelect.innerHTML = '<option value="">No terms found</option>';
      }
    } catch (e) {
      console.warn("Failed to initialize academic overview filters:", e);
      if (gradeSelect) {
        gradeSelect.innerHTML =
          '<option value="">Failed to load grades</option>';
      }
      if (classSelect) {
        classSelect.innerHTML =
          '<option value="">Failed to load classes</option>';
      }
      if (termSelect) {
        termSelect.innerHTML = '<option value="">Failed to load terms</option>';
      }
    }
  })();

  const scrollBtn = document.getElementById("scrollToTopBtn");
  if (scrollBtn) {
    window.addEventListener("scroll", () => {
      scrollBtn.classList.toggle("visible", window.scrollY > 300);
    });
    scrollBtn.addEventListener("click", () =>
      window.scrollTo({ top: 0, behavior: "smooth" }),
    );
  }

  let resizeTimer = null;
  window.addEventListener("resize", () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
      Object.values(charts).forEach((c) => {
        if (c && typeof c.resize === "function") {
          c.resize();
        }
      });
    }, 200);
  });
});
