<?php
// filepath: /home/snake/Projects/Iskole/app/Views/templates/academicOverview.php
?>
<link rel="stylesheet" href="/css/academicOverview/academicOverview.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<section class="mp-academic" aria-labelledby="ao-title">
    <header class="mgmt-header">
        <div class="title-wrap">
            <h2 id="ao-title">Academic Overview</h2>
            <p class="subtitle">School-wide academic statistics</p>
        </div>
    </header>

    <!-- Quick Grade Navigation -->
    <div class="grade-nav">
        <button class="grade-nav-btn" onclick="scrollToGrade('grade-9')">Grade 9</button>
        <button class="grade-nav-btn" onclick="scrollToGrade('grade-8')">Grade 8</button>
        <button class="grade-nav-btn" onclick="scrollToGrade('grade-7')">Grade 7</button>
        <button class="grade-nav-btn" onclick="scrollToGrade('grade-6')">Grade 6</button>
    </div>

    <div class="card">
        <!-- Overview Chart -->
        <div class="chart-container" style="margin-bottom: 2rem;">
            <h3 style="margin-bottom: 1rem; color: var(--text); font-size: 1.1rem;">Grade Performance Overview</h3>
            <canvas id="gradeOverviewChart" style="max-height: 300px;"></canvas>
        </div>                <div class="grade" id="grade-9">
                    <div class="grade-heading">Grade 9</div>
                    <div class="grade-info">
                        <div class="grade-sum">
                            <div class="grade-box grade-summary-box">
                                <div class="heading">Grade Summary</div>
                                <div class="data summary-data">
                                    <div class="summary-stats">
                                        <div class="stat-item">
                                            <span class="stat-label">Average Score</span>
                                            <span class="stat-value">82.3%</span>
                                        </div>
                                        <div class="stat-item">
                                            <span class="stat-label">75%+ Students</span>
                                            <span class="stat-value success">71.2%</span>
                                        </div>
                                        <div class="stat-item">
                                            <span class="stat-label">30%- Students</span>
                                            <span class="stat-value danger">8.7%</span>
                                        </div>
                                    </div>
                                    <div class="summary-chart">
                                        <canvas id="grade-9-summary-chart" data-avg="82.3" data-high="71.2" data-low="8.7"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="hide-box-grade-9">
                            <div class="row">
                                <div class="grade-box">
                                    <div class="heading">Mathematics</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="grade-box">
                                    <div class="heading">Science</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="grade-box">
                                    <div class="heading">History</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="grade-box">
                                    <div class="heading">Religion</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="grade-box">
                                    <div class="heading">Tamil</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="grade-box">
                                    <div class="heading">Geography</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="grade-box">
                                    <div class="heading">Aesthetics</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="grade-box">
                                    <div class="heading">Health &amp; Physical Education</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="grade-box">
                                    <div class="heading">Practical and Technical Skills</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="grade-box">
                                    <div class="heading">English</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="see-more-grade-9 see-more" onclick="toggleSeeMore('grade-9')">See More ...</div>
                    </div>
                </div>

                <div class="grade" id="grade-8">
                    <div class="grade-heading">Grade 8</div>
                    <div class="grade-info">
                        <div class="grade-sum">
                            <div class="grade-box grade-summary-box">
                                <div class="heading">Grade Summary</div>
                                <div class="data summary-data">
                                    <div class="summary-stats">
                                        <div class="stat-item">
                                            <span class="stat-label">Average Score</span>
                                            <span class="stat-value">82.3%</span>
                                        </div>
                                        <div class="stat-item">
                                            <span class="stat-label">75%+ Students</span>
                                            <span class="stat-value success">85.1%</span>
                                        </div>
                                        <div class="stat-item">
                                            <span class="stat-label">30%- Students</span>
                                            <span class="stat-value danger">1.3%</span>
                                        </div>
                                    </div>
                                    <div class="summary-chart">
                                        <canvas id="grade-8-summary-chart" data-avg="82.3" data-high="85.1" data-low="1.3"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="hide-box-grade-8">
                            <div class="row">
                                <div class="grade-box">
                                    <div class="heading">Mathematics</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="grade-box">
                                    <div class="heading">Science</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="grade-box">
                                    <div class="heading">History</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="grade-box">
                                    <div class="heading">Religion</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="grade-box">
                                    <div class="heading">Tamil</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="grade-box">
                                    <div class="heading">Geography</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="grade-box">
                                    <div class="heading">Aesthetics</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="grade-box">
                                    <div class="heading">Health &amp; Physical Education</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="grade-box">
                                    <div class="heading">Practical and Technical Skills</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="grade-box">
                                    <div class="heading">English</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="see-more-grade-8 see-more" onclick="toggleSeeMore('grade-8')">See More ...</div>
                    </div>
                </div>

                <div class="grade" id="grade-7">
                    <div class="grade-heading">Grade 7</div>
                    <div class="grade-info">
                        <div class="grade-sum">
                            <div class="grade-box grade-summary-box">
                                <div class="heading">Grade Summary</div>
                                <div class="data summary-data">
                                    <div class="summary-stats">
                                        <div class="stat-item">
                                            <span class="stat-label">Average Score</span>
                                            <span class="stat-value">82.3%</span>
                                        </div>
                                        <div class="stat-item">
                                            <span class="stat-label">75%+ Students</span>
                                            <span class="stat-value success">59.1%</span>
                                        </div>
                                        <div class="stat-item">
                                            <span class="stat-label">30%- Students</span>
                                            <span class="stat-value danger">3.4%</span>
                                        </div>
                                    </div>
                                    <div class="summary-chart">
                                        <canvas id="grade-7-summary-chart" data-avg="82.3" data-high="59.1" data-low="3.4"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="hide-box-grade-7">
                            <div class="row">
                                <div class="grade-box">
                                    <div class="heading">Mathematics</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="grade-box">
                                    <div class="heading">Science</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="grade-box">
                                    <div class="heading">History</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="grade-box">
                                    <div class="heading">Religion</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="grade-box">
                                    <div class="heading">Tamil</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="grade-box">
                                    <div class="heading">Geography</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="grade-box">
                                    <div class="heading">Aesthetics</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="grade-box">
                                    <div class="heading">Health &amp; Physical Education</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="grade-box">
                                    <div class="heading">Practical and Technical Skills</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="grade-box">
                                    <div class="heading">English</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="see-more-grade-7 see-more" onclick="toggleSeeMore('grade-7')">See More ...</div>
                    </div>
                </div>

                <div class="grade" id="grade-6">
                    <div class="grade-heading">Grade 6</div>
                    <div class="grade-info">
                        <div class="grade-sum">
                            <div class="grade-box grade-summary-box">
                                <div class="heading">Grade Summary</div>
                                <div class="data summary-data">
                                    <div class="summary-stats">
                                        <div class="stat-item">
                                            <span class="stat-label">Average Score</span>
                                            <span class="stat-value">82.3%</span>
                                        </div>
                                        <div class="stat-item">
                                            <span class="stat-label">75%+ Students</span>
                                            <span class="stat-value success">60.5%</span>
                                        </div>
                                        <div class="stat-item">
                                            <span class="stat-label">30%- Students</span>
                                            <span class="stat-value danger">10.7%</span>
                                        </div>
                                    </div>
                                    <div class="summary-chart">
                                        <canvas id="grade-6-summary-chart" data-avg="82.3" data-high="60.5" data-low="10.7"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="hide-box-grade-6">
                            <div class="row">
                                <div class="grade-box">
                                    <div class="heading">Mathematics</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="grade-box">
                                    <div class="heading">Science</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="grade-box">
                                    <div class="heading">History</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="grade-box">
                                    <div class="heading">Religion</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="grade-box">
                                    <div class="heading">Tamil</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="grade-box">
                                    <div class="heading">Geography</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="grade-box">
                                    <div class="heading">Aesthetics</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="grade-box">
                                    <div class="heading">Health &amp; Physical Education</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="grade-box">
                                    <div class="heading">Practical and Technical Skills</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="grade-box">
                                    <div class="heading">English</div>
                                    <div class="data">
                                        <div class="subject-avg">
                                            <span>Average</span>
                                            <div class="progress-container">
                                                <div class="progress-bar"></div>
                                                <div class="progress-text">86.5%</div>
                                            </div>
                                        </div>
                                        <div class="comparison">
                                            <div class="higher-than-75">
                                                <span>75% and higher students</span>
                                                <div class="circular-progress">
                                                    <span>78.6%</span>
                                                </div>
                                            </div>
                                            <div class="lower-than-30">
                                                <span>30% and lower students</span>
                                                <div class="circular-progress">
                                                    <span>5.3%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="see-more-grade-6 see-more" onclick="toggleSeeMore('grade-6')">See More ...</div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Scroll to Top Button -->
    <button class="scroll-to-top" id="scrollToTopBtn" aria-label="Scroll to top"></button>
</section>

<script src="/js/academicOverview/academicOverview.js"></script>