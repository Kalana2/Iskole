<!-- filepath: /home/snake/Projects/Iskole/app/Views/templates/report.php -->
<section class="reports-entry tab-panel mp-management">
  <div class="reports-section">
    <header class="mgmt-header">
      <div class="title-wrap">
        <h2 id="report-title">Student Reports</h2>
        <p class="subtitle">View student progress reports & Behavior reports</p>
      </div>
    </header>

    <div class="center-container card">
      <div class="search-container">
        <input type="text" placeholder="Search student..." id="searchInput">
        <button type="submit" class="search-btn">
          <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
            <path d="M9 17A8 8 0 1 0 9 1a8 8 0 0 0 0 16zM18 18l-4.35-4.35" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          Search
        </button>
      </div>

      <div class="student-container">
        <!-- Student Details Card -->
        <div class="student-info-card">
          <div class="student-avatar">
            <div class="avatar-circle">
              <span>SS</span>
            </div>
          </div>
          <div class="details">
            <h2 class="student-name">Seniru Senaweera</h2>
            <div class="info-grid">
              <div class="info-item">
                <span class="label">Grade:</span>
                <span class="value">06</span>
              </div>
              <div class="info-item">
                <span class="label">Class:</span>
                <span class="value">A</span>
              </div>
              <div class="info-item">
                <span class="label">Student ID:</span>
                <span class="value">101</span>
              </div>
              <div class="info-item">
                <span class="label">Email:</span>
                <span class="value">seniru@gmail.com</span>
              </div>
              <div class="info-item">
                <span class="label">Phone:</span>
                <span class="value">+94702222676</span>
              </div>
              <div class="info-item">
                <span class="label">DOB:</span>
                <span class="value">2013-06-01</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Performance Overview Cards -->
        <div class="stats-overview">
          <div class="stat-card">
            <div class="stat-icon">📊</div>
            <div class="stat-content">
              <h4>Overall Average</h4>
              <p class="stat-value">72.3%</p>
            </div>
          </div>
          <div class="stat-card">
            <div class="stat-icon">🏆</div>
            <div class="stat-content">
              <h4>Section Rank</h4>
              <p class="stat-value">#5</p>
            </div>
          </div>
          <div class="stat-card">
            <div class="stat-icon">⭐</div>
            <div class="stat-content">
              <h4>Class Rank</h4>
              <p class="stat-value">#1</p>
            </div>
          </div>
        </div>

        <!-- Performance Report with Charts -->
        <div class="performance-report">
          <h3 class="report-title">
            <span>Performance Report</span>
            <div class="chart-toggle">
              <button class="toggle-btn active" data-chart="line">Trend</button>
              <button class="toggle-btn" data-chart="radar">Radar Chart</button>
            </div>
          </h3>

          <!-- <div class="chart-controls">
            <div class="term-selector" id="termSelector">
              <label for="term-select">Select Term:</label>
              <select id="term-select" name="term">
                <option value="term1">Term 1</option>
                <option value="term2">Term 2</option>
                <option value="term3" selected>Term 3</option>
              </select>
            </div>
          </div> -->

          <div class="chart-container">
            <canvas id="performanceChart"></canvas>
          </div>

          <!-- Subject Details Table -->
          <!-- <div class="subject-details">
            <h4>Detailed Marks</h4>
            <div class="subjects-grid">
              <div class="subject-card" data-grade="A">
                <div class="subject-header">
                  <span class="subject-name">Religion</span>
                  <span class="badge A">A</span>
                </div>
                <div class="subject-score">89/100</div>
                <div class="progress-bar-mini">
                  <div class="progress-fill" style="width: 89%"></div>
                </div>
              </div>

              <div class="subject-card" data-grade="A">
                <div class="subject-header">
                  <span class="subject-name">Sinhala</span>
                  <span class="badge A">A</span>
                </div>
                <div class="subject-score">77/100</div>
                <div class="progress-bar-mini">
                  <div class="progress-fill" style="width: 77%"></div>
                </div>
              </div>

              <div class="subject-card" data-grade="A">
                <div class="subject-header">
                  <span class="subject-name">Mathematics</span>
                  <span class="badge A">A</span>
                </div>
                <div class="subject-score">92/100</div>
                <div class="progress-bar-mini">
                  <div class="progress-fill" style="width: 92%"></div>
                </div>
              </div>

              <div class="subject-card" data-grade="A">
                <div class="subject-header">
                  <span class="subject-name">Science</span>
                  <span class="badge A">A</span>
                </div>
                <div class="subject-score">88/100</div>
                <div class="progress-bar-mini">
                  <div class="progress-fill" style="width: 88%"></div>
                </div>
              </div>

              <div class="subject-card" data-grade="A">
                <div class="subject-header">
                  <span class="subject-name">English</span>
                  <span class="badge A">A</span>
                </div>
                <div class="subject-score">85/100</div>
                <div class="progress-bar-mini">
                  <div class="progress-fill" style="width: 85%"></div>
                </div>
              </div>

              <div class="subject-card" data-grade="C">
                <div class="subject-header">
                  <span class="subject-name">History</span>
                  <span class="badge C">C</span>
                </div>
                <div class="subject-score">60/100</div>
                <div class="progress-bar-mini">
                  <div class="progress-fill" style="width: 60%"></div>
                </div>
              </div>

              <div class="subject-card" data-grade="B">
                <div class="subject-header">
                  <span class="subject-name">Geography</span>
                  <span class="badge B">B</span>
                </div>
                <div class="subject-score">73/100</div>
                <div class="progress-bar-mini">
                  <div class="progress-fill" style="width: 73%"></div>
                </div>
              </div>

              <div class="subject-card" data-grade="B">
                <div class="subject-header">
                  <span class="subject-name">Health & PE</span>
                  <span class="badge B">B</span>
                </div>
                <div class="subject-score">74/100</div>
                <div class="progress-bar-mini">
                  <div class="progress-fill" style="width: 74%"></div>
                </div>
              </div>

              <div class="subject-card" data-grade="A">
                <div class="subject-header">
                  <span class="subject-name">Tamil</span>
                  <span class="badge A">A</span>
                </div>
                <div class="subject-score">99/100</div>
                <div class="progress-bar-mini">
                  <div class="progress-fill" style="width: 99%"></div>
                </div>
              </div>

              <div class="subject-card" data-grade="C">
                <div class="subject-header">
                  <span class="subject-name">Aesthetics</span>
                  <span class="badge C">C</span>
                </div>
                <div class="subject-score">55/100</div>
                <div class="progress-bar-mini">
                  <div class="progress-fill" style="width: 55%"></div>
                </div>
              </div>

              <div class="subject-card" data-grade="W">
                <div class="subject-header">
                  <span class="subject-name">Citizenship</span>
                  <span class="badge W">W</span>
                </div>
                <div class="subject-score">30/100</div>
                <div class="progress-bar-mini">
                  <div class="progress-fill" style="width: 30%"></div>
                </div>
              </div>

              <div class="subject-card" data-grade="S">
                <div class="subject-header">
                  <span class="subject-name">Practical Skills</span>
                  <span class="badge S">S</span>
                </div>
                <div class="subject-score">45/100</div>
                <div class="progress-bar-mini">
                  <div class="progress-fill" style="width: 45%"></div>
                </div>
              </div>
            </div>
          </div> -->
        </div>

        <!-- Behavior Section -->
        <div class="behavior-section">
          <div class="behavior-report">
            <h3 class="report-title">Behavior Report</h3>
            <div class="behavior-update">
              <form action="" id="behaviorForm">
                <label for="behavior-update">Add Behavior Update:</label>
                <textarea id="behavior-update" name="behavior-update" rows="4" placeholder="Enter behavior observation..."></textarea>
                <button type="submit" class="update-behavior-btn">
                  <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path d="M8 2v12M2 8h12" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                  </svg>
                  Add Update
                </button>
              </form>
            </div>
          </div>

          <div class="recent-behavior-updates">
            <h3 class="report-title">Recent Behavior Updates</h3>
            <div class="timeline">
              <div class="timeline-item">
                <div class="timeline-marker positive"></div>
                <div class="timeline-content">
                  <div class="timeline-date">Nov 1, 2025</div>
                  <p>Improved participation in class discussions.</p>
                </div>
              </div>
              <div class="timeline-item">
                <div class="timeline-marker positive"></div>
                <div class="timeline-content">
                  <div class="timeline-date">Oct 28, 2025</div>
                  <p>Completed all homework assignments on time.</p>
                </div>
              </div>
              <div class="timeline-item">
                <div class="timeline-marker positive"></div>
                <div class="timeline-content">
                  <div class="timeline-date">Oct 25, 2025</div>
                  <p>Helped classmates with difficult subjects.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>