<?php
$localization = new BillingPages\Core\Localization();
$session = new BillingPages\Core\Session();
?>

<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="bi bi-graph-up me-2"></i>
                <?= $localization->get('work') ?> <?= $localization->get('reports') ?>
            </h1>
            <p class="text-muted"><?= $localization->get('detailed') ?> <?= $localization->get('analysis') ?> <?= $localization->get('and') ?> <?= $localization->get('statistics') ?> <?= $localization->get('for') ?> <?= $localization->get('work') ?> <?= $localization->get('entries') ?></p>
        </div>
        <div>
            <button class="btn btn-outline-primary me-2" onclick="exportReport()">
                <i class="bi bi-download me-1"></i>
                <?= $localization->get('export') ?> <?= $localization->get('report') ?>
            </button>
            <button class="btn btn-primary" onclick="printReport()">
                <i class="bi bi-printer me-1"></i>
                <?= $localization->get('print') ?> <?= $localization->get('report') ?>
            </button>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="from_date" class="form-label"><?= $localization->get('from_date') ?></label>
                    <input type="date" class="form-control" id="from_date" name="from_date" 
                           value="<?= $filters['from_date'] ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="to_date" class="form-label"><?= $localization->get('to_date') ?></label>
                    <input type="date" class="form-control" id="to_date" name="to_date" 
                           value="<?= $filters['to_date'] ?>" required>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search me-1"></i>
                        <?= $localization->get('generate') ?> <?= $localization->get('report') ?>
                    </button>
                    <a href="/work/reports" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise me-1"></i>
                        <?= $localization->get('reset') ?>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                <?= $localization->get('total') ?> <?= $localization->get('hours') ?>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($stats['total_hours'], 1) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                <?= $localization->get('total') ?> <?= $localization->get('earnings') ?>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($stats['total_earnings'], 2) ?> €
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-cash-coin fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                <?= $localization->get('avg_hourly_rate') ?>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($stats['avg_hourly_rate'], 2) ?> €
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-graph-up fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                <?= $localization->get('total_entries') ?>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($stats['total_entries']) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-list-ul fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <?= $localization->get('daily') ?> <?= $localization->get('earnings') ?>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="dailyEarningsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <?= $localization->get('work_by_type') ?>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="workTypeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Charts -->
    <div class="row mb-4">
        <div class="col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <?= $localization->get('hours_by_project') ?>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="projectHoursChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <?= $localization->get('weekly') ?> <?= $localization->get('trends') ?>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-line">
                        <canvas id="weeklyTrendsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Statistics Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <?= $localization->get('detailed') ?> <?= $localization->get('statistics') ?>
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th><?= $localization->get('metric') ?></th>
                            <th><?= $localization->get('value') ?></th>
                            <th><?= $localization->get('percentage') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= $localization->get('total_work_days') ?></td>
                            <td><?= $stats['total_work_days'] ?></td>
                            <td>100%</td>
                        </tr>
                        <tr>
                            <td><?= $localization->get('avg_hours_per_day') ?></td>
                            <td><?= number_format($stats['avg_hours_per_day'], 2) ?></td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td><?= $localization->get('highest_daily_earnings') ?></td>
                            <td><?= number_format($stats['highest_daily_earnings'], 2) ?> €</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td><?= $localization->get('lowest_daily_earnings') ?></td>
                            <td><?= number_format($stats['lowest_daily_earnings'], 2) ?> €</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td><?= $localization->get('completed_entries') ?></td>
                            <td><?= $stats['completed_entries'] ?></td>
                            <td><?= number_format(($stats['completed_entries'] / $stats['total_entries']) * 100, 1) ?>%</td>
                        </tr>
                        <tr>
                            <td><?= $localization->get('billed_entries') ?></td>
                            <td><?= $stats['billed_entries'] ?></td>
                            <td><?= number_format(($stats['billed_entries'] / $stats['total_entries']) * 100, 1) ?>%</td>
                        </tr>
                        <tr>
                            <td><?= $localization->get('pending_entries') ?></td>
                            <td><?= $stats['pending_entries'] ?></td>
                            <td><?= number_format(($stats['pending_entries'] / $stats['total_entries']) * 100, 1) ?>%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Top Projects -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <?= $localization->get('top') ?> <?= $localization->get('projects') ?>
            </h6>
        </div>
        <div class="card-body">
            <?php if (empty($topProjects)): ?>
                <div class="text-center py-4">
                    <i class="bi bi-inbox fa-2x text-muted mb-3"></i>
                    <p class="text-muted"><?= $localization->get('no_project_data') ?></p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th><?= $localization->get('project') ?></th>
                                <th><?= $localization->get('total_hours') ?></th>
                                <th><?= $localization->get('total_earnings') ?></th>
                                <th><?= $localization->get('avg_rate') ?></th>
                                <th><?= $localization->get('entries_count') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($topProjects as $project): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($project['work_project']) ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary"><?= number_format($project['total_hours'], 1) ?> <?= $localization->get('hours') ?></span>
                                    </td>
                                    <td>
                                        <strong class="text-success"><?= number_format($project['total_earnings'], 2) ?> €</strong>
                                    </td>
                                    <td><?= number_format($project['avg_rate'], 2) ?> €/<?= $localization->get('hour') ?></td>
                                    <td><?= number_format($project['entries_count']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Initialize charts when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
});

function initializeCharts() {
    // Daily earnings chart
    const dailyEarningsCtx = document.getElementById('dailyEarningsChart').getContext('2d');
    const dailyEarningsData = <?= json_encode($chartData['daily_earnings'] ?? []) ?>;
    
    new Chart(dailyEarningsCtx, {
        type: 'line',
        data: {
            labels: dailyEarningsData.map(item => item.date),
            datasets: [{
                label: '<?= $localization->get('earnings') ?> (€)',
                data: dailyEarningsData.map(item => item.total),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value + ' €';
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Work by type chart
    const workTypeCtx = document.getElementById('workTypeChart').getContext('2d');
    const workTypeData = <?= json_encode($chartData['work_by_type'] ?? []) ?>;
    
    new Chart(workTypeCtx, {
        type: 'doughnut',
        data: {
            labels: workTypeData.map(item => item.work_type),
            datasets: [{
                data: workTypeData.map(item => item.total),
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF',
                    '#FF9F40'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Project hours chart
    const projectHoursCtx = document.getElementById('projectHoursChart').getContext('2d');
    const projectHoursData = <?= json_encode($chartData['project_hours'] ?? []) ?>;
    
    new Chart(projectHoursCtx, {
        type: 'bar',
        data: {
            labels: projectHoursData.map(item => item.project),
            datasets: [{
                label: '<?= $localization->get('hours') ?>',
                data: projectHoursData.map(item => item.hours),
                backgroundColor: 'rgba(54, 162, 235, 0.8)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value + ' <?= $localization->get('hours') ?>';
                        }
                    }
                }
            }
        }
    });

    // Weekly trends chart
    const weeklyTrendsCtx = document.getElementById('weeklyTrendsChart').getContext('2d');
    const weeklyTrendsData = <?= json_encode($chartData['weekly_trends'] ?? []) ?>;
    
    new Chart(weeklyTrendsCtx, {
        type: 'line',
        data: {
            labels: weeklyTrendsData.map(item => item.week),
            datasets: [{
                label: '<?= $localization->get('hours') ?>',
                data: weeklyTrendsData.map(item => item.hours),
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                tension: 0.1
            }, {
                label: '<?= $localization->get('earnings') ?> (€)',
                data: weeklyTrendsData.map(item => item.earnings),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value + ' <?= $localization->get('hours') ?>';
                        }
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    beginAtZero: true,
                    grid: {
                        drawOnChartArea: false,
                    },
                    ticks: {
                        callback: function(value) {
                            return value + ' €';
                        }
                    }
                }
            }
        }
    });
}

// Export report functionality
function exportReport() {
    const reportData = {
        title: '<?= $localization->get('work') ?> <?= $localization->get('report') ?>',
        dateRange: '<?= $filters['from_date'] ?> - <?= $filters['to_date'] ?>',
        stats: <?= json_encode($stats) ?>,
        generatedAt: new Date().toISOString()
    };
    
    const blob = new Blob([JSON.stringify(reportData, null, 2)], { type: 'application/json' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `work_report_${new Date().toISOString().split('T')[0]}.json`;
    a.click();
    window.URL.revokeObjectURL(url);
}

// Print report functionality
function printReport() {
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title><?= $localization->get('work') ?> <?= $localization->get('report') ?> - <?= date('Y-m-d') ?></title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    .header { text-align: center; margin-bottom: 30px; }
                    .stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 30px; }
                    .stat-card { border: 1px solid #ddd; padding: 15px; text-align: center; }
                    .stat-value { font-size: 24px; font-weight: bold; color: #333; }
                    .stat-label { color: #666; margin-top: 5px; }
                    table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f2f2f2; }
                    @media print {
                        .no-print { display: none; }
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1><?= $localization->get('work') ?> <?= $localization->get('report') ?></h1>
                    <p><?= $localization->get('generated_on') ?>: <?= date('d.m.Y H:i') ?></p>
                    <p><?= $localization->get('date_range') ?>: <?= $filters['from_date'] ?> - <?= $filters['to_date'] ?></p>
                </div>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-value"><?= number_format($stats['total_hours'], 1) ?></div>
                        <div class="stat-label"><?= $localization->get('total') ?> <?= $localization->get('hours') ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value"><?= number_format($stats['total_earnings'], 2) ?> €</div>
                        <div class="stat-label"><?= $localization->get('total') ?> <?= $localization->get('earnings') ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value"><?= number_format($stats['avg_hourly_rate'], 2) ?> €</div>
                        <div class="stat-label"><?= $localization->get('avg_hourly_rate') ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value"><?= number_format($stats['total_entries']) ?></div>
                        <div class="stat-label"><?= $localization->get('total_entries') ?></div>
                    </div>
                </div>
                
                <h2><?= $localization->get('top') ?> <?= $localization->get('projects') ?></h2>
                <table>
                    <thead>
                        <tr>
                            <th><?= $localization->get('project') ?></th>
                            <th><?= $localization->get('total_hours') ?></th>
                            <th><?= $localization->get('total_earnings') ?></th>
                            <th><?= $localization->get('entries_count') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($topProjects as $project): ?>
                            <tr>
                                <td><?= htmlspecialchars($project['work_project']) ?></td>
                                <td><?= number_format($project['total_hours'], 1) ?></td>
                                <td><?= number_format($project['total_earnings'], 2) ?> €</td>
                                <td><?= number_format($project['entries_count']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?> 