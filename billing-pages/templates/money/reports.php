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
                <?= $localization->get('money') ?> <?= $localization->get('reports') ?>
            </h1>
            <p class="text-muted"><?= $localization->get('detailed') ?> <?= $localization->get('analysis') ?> <?= $localization->get('and') ?> <?= $localization->get('statistics') ?> <?= $localization->get('for') ?> <?= $localization->get('money') ?> <?= $localization->get('entries') ?></p>
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
                    <a href="/money/reports" class="btn btn-outline-secondary">
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
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                <?= $localization->get('total') ?> <?= $localization->get('income') ?>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($stats['total_income'], 2) ?> €
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-arrow-up-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                <?= $localization->get('total') ?> <?= $localization->get('expenses') ?>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($stats['total_expenses'], 2) ?> €
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-arrow-down-circle fa-2x text-gray-300"></i>
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
                                <?= $localization->get('net') ?> <?= $localization->get('balance') ?>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($stats['net_balance'], 2) ?> €
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-wallet2 fa-2x text-gray-300"></i>
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
                        <?= $localization->get('monthly') ?> <?= $localization->get('cash_flow') ?>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="cashFlowChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <?= $localization->get('income_vs_expenses') ?>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="incomeExpensesChart"></canvas>
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
                        <?= $localization->get('expenses_by_category') ?>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="expensesByCategoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <?= $localization->get('payment_methods') ?>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-doughnut">
                        <canvas id="paymentMethodsChart"></canvas>
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
                            <td><?= $localization->get('total_income_entries') ?></td>
                            <td><?= $stats['income_entries'] ?></td>
                            <td><?= number_format(($stats['income_entries'] / $stats['total_entries']) * 100, 1) ?>%</td>
                        </tr>
                        <tr>
                            <td><?= $localization->get('total_expense_entries') ?></td>
                            <td><?= $stats['expense_entries'] ?></td>
                            <td><?= number_format(($stats['expense_entries'] / $stats['total_entries']) * 100, 1) ?>%</td>
                        </tr>
                        <tr>
                            <td><?= $localization->get('avg_income_per_entry') ?></td>
                            <td><?= number_format($stats['avg_income_per_entry'], 2) ?> €</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td><?= $localization->get('avg_expense_per_entry') ?></td>
                            <td><?= number_format($stats['avg_expense_per_entry'], 2) ?> €</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td><?= $localization->get('highest_income_day') ?></td>
                            <td><?= number_format($stats['highest_income_day'], 2) ?> €</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td><?= $localization->get('highest_expense_day') ?></td>
                            <td><?= number_format($stats['highest_expense_day'], 2) ?> €</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td><?= $localization->get('completed_payments') ?></td>
                            <td><?= $stats['completed_payments'] ?></td>
                            <td><?= number_format(($stats['completed_payments'] / $stats['total_entries']) * 100, 1) ?>%</td>
                        </tr>
                        <tr>
                            <td><?= $localization->get('pending_payments') ?></td>
                            <td><?= $stats['pending_payments'] ?></td>
                            <td><?= number_format(($stats['pending_payments'] / $stats['total_entries']) * 100, 1) ?>%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Top Categories -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <?= $localization->get('top') ?> <?= $localization->get('categories') ?>
            </h6>
        </div>
        <div class="card-body">
            <?php if (empty($topCategories)): ?>
                <div class="text-center py-4">
                    <i class="bi bi-inbox fa-2x text-muted mb-3"></i>
                    <p class="text-muted"><?= $localization->get('no_category_data') ?></p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th><?= $localization->get('category') ?></th>
                                <th><?= $localization->get('total_amount') ?></th>
                                <th><?= $localization->get('entries_count') ?></th>
                                <th><?= $localization->get('avg_amount') ?></th>
                                <th><?= $localization->get('type') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($topCategories as $category): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($category['category']) ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $category['total_amount'] >= 0 ? 'success' : 'danger' ?>">
                                            <?= number_format($category['total_amount'], 2) ?> €
                                        </span>
                                    </td>
                                    <td><?= number_format($category['entries_count']) ?></td>
                                    <td><?= number_format($category['avg_amount'], 2) ?> €</td>
                                    <td>
                                        <span class="badge bg-<?= $category['total_amount'] >= 0 ? 'success' : 'danger' ?>">
                                            <?= $category['total_amount'] >= 0 ? $localization->get('income') : $localization->get('expense') ?>
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
    // Cash flow chart
    const cashFlowCtx = document.getElementById('cashFlowChart').getContext('2d');
    const cashFlowData = <?= json_encode($chartData['cash_flow'] ?? []) ?>;
    
    new Chart(cashFlowCtx, {
        type: 'line',
        data: {
            labels: cashFlowData.map(item => item.month),
            datasets: [{
                label: '<?= $localization->get('income') ?> (€)',
                data: cashFlowData.map(item => item.income),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1,
                fill: false
            }, {
                label: '<?= $localization->get('expenses') ?> (€)',
                data: cashFlowData.map(item => item.expenses),
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                tension: 0.1,
                fill: false
            }, {
                label: '<?= $localization->get('net') ?> (€)',
                data: cashFlowData.map(item => item.net),
                borderColor: 'rgb(255, 205, 86)',
                backgroundColor: 'rgba(255, 205, 86, 0.2)',
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
            }
        }
    });

    // Income vs Expenses chart
    const incomeExpensesCtx = document.getElementById('incomeExpensesChart').getContext('2d');
    const incomeExpensesData = <?= json_encode($chartData['income_expenses'] ?? []) ?>;
    
    new Chart(incomeExpensesCtx, {
        type: 'doughnut',
        data: {
            labels: ['<?= $localization->get('income') ?>', '<?= $localization->get('expenses') ?>'],
            datasets: [{
                data: [incomeExpensesData.income, incomeExpensesData.expenses],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(255, 99, 132, 0.8)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)'
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

    // Expenses by category chart
    const expensesByCategoryCtx = document.getElementById('expensesByCategoryChart').getContext('2d');
    const expensesByCategoryData = <?= json_encode($chartData['expenses_by_category'] ?? []) ?>;
    
    new Chart(expensesByCategoryCtx, {
        type: 'bar',
        data: {
            labels: expensesByCategoryData.map(item => item.category),
            datasets: [{
                label: '<?= $localization->get('expenses') ?> (€)',
                data: expensesByCategoryData.map(item => Math.abs(item.amount)),
                backgroundColor: 'rgba(255, 99, 132, 0.8)',
                borderColor: 'rgba(255, 99, 132, 1)',
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

    // Payment methods chart
    const paymentMethodsCtx = document.getElementById('paymentMethodsChart').getContext('2d');
    const paymentMethodsData = <?= json_encode($chartData['payment_methods'] ?? []) ?>;
    
    new Chart(paymentMethodsCtx, {
        type: 'doughnut',
        data: {
            labels: paymentMethodsData.map(item => item.payment_method),
            datasets: [{
                data: paymentMethodsData.map(item => item.count),
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
}

// Export report functionality
function exportReport() {
    const reportData = {
        title: '<?= $localization->get('money') ?> <?= $localization->get('report') ?>',
        dateRange: '<?= $filters['from_date'] ?> - <?= $filters['to_date'] ?>',
        stats: <?= json_encode($stats) ?>,
        generatedAt: new Date().toISOString()
    };
    
    const blob = new Blob([JSON.stringify(reportData, null, 2)], { type: 'application/json' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `money_report_${new Date().toISOString().split('T')[0]}.json`;
    a.click();
    window.URL.revokeObjectURL(url);
}

// Print report functionality
function printReport() {
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title><?= $localization->get('money') ?> <?= $localization->get('report') ?> - <?= date('Y-m-d') ?></title>
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
                    <h1><?= $localization->get('money') ?> <?= $localization->get('report') ?></h1>
                    <p><?= $localization->get('generated_on') ?>: <?= date('d.m.Y H:i') ?></p>
                    <p><?= $localization->get('date_range') ?>: <?= $filters['from_date'] ?> - <?= $filters['to_date'] ?></p>
                </div>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-value"><?= number_format($stats['total_income'], 2) ?> €</div>
                        <div class="stat-label"><?= $localization->get('total') ?> <?= $localization->get('income') ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value"><?= number_format($stats['total_expenses'], 2) ?> €</div>
                        <div class="stat-label"><?= $localization->get('total') ?> <?= $localization->get('expenses') ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value"><?= number_format($stats['net_balance'], 2) ?> €</div>
                        <div class="stat-label"><?= $localization->get('net') ?> <?= $localization->get('balance') ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value"><?= number_format($stats['total_entries']) ?></div>
                        <div class="stat-label"><?= $localization->get('total_entries') ?></div>
                    </div>
                </div>
                
                <h2><?= $localization->get('top') ?> <?= $localization->get('categories') ?></h2>
                <table>
                    <thead>
                        <tr>
                            <th><?= $localization->get('category') ?></th>
                            <th><?= $localization->get('total_amount') ?></th>
                            <th><?= $localization->get('entries_count') ?></th>
                            <th><?= $localization->get('type') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($topCategories as $category): ?>
                            <tr>
                                <td><?= htmlspecialchars($category['category']) ?></td>
                                <td><?= number_format($category['total_amount'], 2) ?> €</td>
                                <td><?= number_format($category['entries_count']) ?></td>
                                <td><?= $category['total_amount'] >= 0 ? $localization->get('income') : $localization->get('expense') ?></td>
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