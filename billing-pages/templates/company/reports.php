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
                <?= $localization->get('company') ?> <?= $localization->get('reports') ?>
            </h1>
            <p class="text-muted"><?= $localization->get('detailed') ?> <?= $localization->get('analysis') ?> <?= $localization->get('and') ?> <?= $localization->get('statistics') ?> <?= $localization->get('for') ?> <?= $localization->get('company') ?> <?= $localization->get('billing') ?></p>
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
                    <a href="/company/reports" class="btn btn-outline-secondary">
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
                                <?= $localization->get('total') ?> <?= $localization->get('companies') ?>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($stats['total_companies']) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-building fa-2x text-gray-300"></i>
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
                                <?= $localization->get('active') ?> <?= $localization->get('companies') ?>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($stats['active_companies']) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle fa-2x text-gray-300"></i>
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
                                <?= $localization->get('companies_with_billing') ?>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($stats['companies_with_billing']) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-receipt fa-2x text-gray-300"></i>
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
                                <?= $localization->get('avg_billing_per_company') ?>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($stats['avg_billing_per_company'], 2) ?> €
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-graph-up fa-2x text-gray-300"></i>
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
                        <?= $localization->get('monthly') ?> <?= $localization->get('company_billing') ?>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="monthlyBillingChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <?= $localization->get('company_status_distribution') ?>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="companyStatusChart"></canvas>
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
                        <?= $localization->get('top_billing_companies') ?>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="topCompaniesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <?= $localization->get('billing_trends') ?>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-line">
                        <canvas id="billingTrendsChart"></canvas>
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
                            <td><?= $localization->get('new_companies_this_period') ?></td>
                            <td><?= $stats['new_companies'] ?></td>
                            <td><?= number_format(($stats['new_companies'] / $stats['total_companies']) * 100, 1) ?>%</td>
                        </tr>
                        <tr>
                            <td><?= $localization->get('companies_with_invoices') ?></td>
                            <td><?= $stats['companies_with_billing'] ?></td>
                            <td><?= number_format(($stats['companies_with_billing'] / $stats['total_companies']) * 100, 1) ?>%</td>
                        </tr>
                        <tr>
                            <td><?= $localization->get('total_billing_amount') ?></td>
                            <td><?= number_format($stats['total_billing_amount'], 2) ?> €</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td><?= $localization->get('highest_company_billing') ?></td>
                            <td><?= number_format($stats['highest_company_billing'], 2) ?> €</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td><?= $localization->get('lowest_company_billing') ?></td>
                            <td><?= number_format($stats['lowest_company_billing'], 2) ?> €</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td><?= $localization->get('avg_invoices_per_company') ?></td>
                            <td><?= number_format($stats['avg_invoices_per_company'], 1) ?></td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td><?= $localization->get('inactive_companies') ?></td>
                            <td><?= $stats['inactive_companies'] ?></td>
                            <td><?= number_format(($stats['inactive_companies'] / $stats['total_companies']) * 100, 1) ?>%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Top Companies -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <?= $localization->get('top') ?> <?= $localization->get('companies') ?> <?= $localization->get('by') ?> <?= $localization->get('billing') ?>
            </h6>
        </div>
        <div class="card-body">
            <?php if (empty($companies)): ?>
                <div class="text-center py-4">
                    <i class="bi bi-inbox fa-2x text-muted mb-3"></i>
                    <p class="text-muted"><?= $localization->get('no_company_data') ?></p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th><?= $localization->get('company') ?></th>
                                <th><?= $localization->get('contact') ?></th>
                                <th><?= $localization->get('invoice_count') ?></th>
                                <th><?= $localization->get('total_billing') ?></th>
                                <th><?= $localization->get('avg_billing') ?></th>
                                <th><?= $localization->get('status') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($companies as $company): ?>
                                <tr>
                                    <td>
                                        <div>
                                            <strong><?= htmlspecialchars($company['company_name']) ?></strong>
                                            <?php if (!empty($company['company_email'])): ?>
                                                <br><small class="text-muted"><?= htmlspecialchars($company['company_email']) ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($company['company_contact']) ?></td>
                                    <td>
                                        <span class="badge bg-primary"><?= number_format($company['invoice_count']) ?></span>
                                    </td>
                                    <td>
                                        <strong class="text-success"><?= number_format($company['total_billing'], 2) ?> €</strong>
                                    </td>
                                    <td><?= number_format($company['avg_billing'], 2) ?> €</td>
                                    <td>
                                        <span class="badge bg-<?= $company['status'] === 'active' ? 'success' : 'secondary' ?>">
                                            <?= $localization->get($company['status']) ?>
                                        </span>
                                    </td>
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
    // Monthly billing chart
    const monthlyBillingCtx = document.getElementById('monthlyBillingChart').getContext('2d');
    const monthlyBillingData = <?= json_encode($chartData['monthly_billing'] ?? []) ?>;
    
    new Chart(monthlyBillingCtx, {
        type: 'line',
        data: {
            labels: monthlyBillingData.map(item => item.month),
            datasets: [{
                label: '<?= $localization->get('total_billing') ?> (€)',
                data: monthlyBillingData.map(item => item.total),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1,
                fill: true
            }, {
                label: '<?= $localization->get('company_count') ?>',
                data: monthlyBillingData.map(item => item.company_count),
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                tension: 0.1,
                fill: false,
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
                            return value + ' €';
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
                            return value + ' <?= $localization->get('companies') ?>';
                        }
                    }
                }
            }
        }
    });

    // Company status chart
    const companyStatusCtx = document.getElementById('companyStatusChart').getContext('2d');
    const companyStatusData = <?= json_encode($chartData['company_status'] ?? []) ?>;
    
    new Chart(companyStatusCtx, {
        type: 'doughnut',
        data: {
            labels: companyStatusData.map(item => item.status),
            datasets: [{
                data: companyStatusData.map(item => item.count),
                backgroundColor: [
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(255, 205, 86, 0.8)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 205, 86, 1)'
                ],
                borderWidth: 1
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

    // Top companies chart
    const topCompaniesCtx = document.getElementById('topCompaniesChart').getContext('2d');
    const topCompaniesData = <?= json_encode($chartData['top_companies'] ?? []) ?>;
    
    new Chart(topCompaniesCtx, {
        type: 'bar',
        data: {
            labels: topCompaniesData.map(item => item.company_name),
            datasets: [{
                label: '<?= $localization->get('total_billing') ?> (€)',
                data: topCompaniesData.map(item => item.total_billing),
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
                            return value + ' €';
                        }
                    }
                }
            }
        }
    });

    // Billing trends chart
    const billingTrendsCtx = document.getElementById('billingTrendsChart').getContext('2d');
    const billingTrendsData = <?= json_encode($chartData['billing_trends'] ?? []) ?>;
    
    new Chart(billingTrendsCtx, {
        type: 'line',
        data: {
            labels: billingTrendsData.map(item => item.period),
            datasets: [{
                label: '<?= $localization->get('avg_billing_per_company') ?> (€)',
                data: billingTrendsData.map(item => item.avg_billing),
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                tension: 0.1
            }, {
                label: '<?= $localization->get('total_companies') ?>',
                data: billingTrendsData.map(item => item.company_count),
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
                            return value + ' €';
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
                            return value + ' <?= $localization->get('companies') ?>';
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
        title: '<?= $localization->get('company') ?> <?= $localization->get('report') ?>',
        dateRange: '<?= $filters['from_date'] ?> - <?= $filters['to_date'] ?>',
        stats: <?= json_encode($stats) ?>,
        generatedAt: new Date().toISOString()
    };
    
    const blob = new Blob([JSON.stringify(reportData, null, 2)], { type: 'application/json' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `company_report_${new Date().toISOString().split('T')[0]}.json`;
    a.click();
    window.URL.revokeObjectURL(url);
}

// Print report functionality
function printReport() {
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title><?= $localization->get('company') ?> <?= $localization->get('report') ?> - <?= date('Y-m-d') ?></title>
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
                    <h1><?= $localization->get('company') ?> <?= $localization->get('report') ?></h1>
                    <p><?= $localization->get('generated_on') ?>: <?= date('d.m.Y H:i') ?></p>
                    <p><?= $localization->get('date_range') ?>: <?= $filters['from_date'] ?> - <?= $filters['to_date'] ?></p>
                </div>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-value"><?= number_format($stats['total_companies']) ?></div>
                        <div class="stat-label"><?= $localization->get('total') ?> <?= $localization->get('companies') ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value"><?= number_format($stats['active_companies']) ?></div>
                        <div class="stat-label"><?= $localization->get('active') ?> <?= $localization->get('companies') ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value"><?= number_format($stats['companies_with_billing']) ?></div>
                        <div class="stat-label"><?= $localization->get('companies_with_billing') ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value"><?= number_format($stats['avg_billing_per_company'], 2) ?> €</div>
                        <div class="stat-label"><?= $localization->get('avg_billing_per_company') ?></div>
                    </div>
                </div>
                
                <h2><?= $localization->get('top') ?> <?= $localization->get('companies') ?> <?= $localization->get('by') ?> <?= $localization->get('billing') ?></h2>
                <table>
                    <thead>
                        <tr>
                            <th><?= $localization->get('company') ?></th>
                            <th><?= $localization->get('contact') ?></th>
                            <th><?= $localization->get('invoice_count') ?></th>
                            <th><?= $localization->get('total_billing') ?></th>
                            <th><?= $localization->get('status') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($companies as $company): ?>
                            <tr>
                                <td><?= htmlspecialchars($company['company_name']) ?></td>
                                <td><?= htmlspecialchars($company['company_contact']) ?></td>
                                <td><?= number_format($company['invoice_count']) ?></td>
                                <td><?= number_format($company['total_billing'], 2) ?> €</td>
                                <td><?= $localization->get($company['status']) ?></td>
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