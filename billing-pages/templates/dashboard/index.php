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
                <i class="bi bi-speedometer2 me-2"></i>
                <?= $localization->get('dashboard') ?>
            </h1>
            <p class="text-muted"><?= $localization->get('welcome_back') ?> <?= $session->getUsername() ?>!</p>
        </div>
        <div>
            <button class="btn btn-primary" onclick="refreshStats()">
                <i class="bi bi-arrow-clockwise me-1"></i>
                <?= $localization->get('refresh') ?>
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                <?= $localization->get('total') ?> <?= $localization->get('companies') ?>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="total_companies">
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
                                <?= $localization->get('work_hours') ?> (<?= $localization->get('this_month') ?>)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="work_hours_month">
                                <?= number_format($stats['work_hours_month'], 1) ?>
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
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                <?= $localization->get('earnings') ?> (<?= $localization->get('this_month') ?>)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="earnings_month">
                                <?= number_format($stats['earnings_month'], 2) ?> €
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
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                <?= $localization->get('pending') ?> <?= $localization->get('invoices') ?>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="pending_invoices">
                                <?= number_format($stats['pending_invoices']) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-receipt fa-2x text-gray-300"></i>
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
                        <?= $localization->get('monthly_earnings') ?>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="earningsChart"></canvas>
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

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <?= $localization->get('recent_activities') ?>
                    </h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header"><?= $localization->get('activities_options') ?>:</div>
                            <a class="dropdown-item" href="/work/overview"><?= $localization->get('view_all_work') ?></a>
                            <a class="dropdown-item" href="/money/overview"><?= $localization->get('view_all_money') ?></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($recentActivities)): ?>
                        <div class="text-center py-4">
                            <i class="bi bi-inbox fa-2x text-muted mb-3"></i>
                            <p class="text-muted"><?= $localization->get('no_recent_activities') ?></p>
                            <a href="/work/add" class="btn btn-primary btn-sm me-2">
                                <i class="bi bi-plus-circle me-1"></i>
                                <?= $localization->get('add_work') ?>
                            </a>
                            <a href="/money/add" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-plus-circle me-1"></i>
                                <?= $localization->get('add_money') ?>
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($recentActivities as $activity): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-3">
                                            <div class="avatar-title bg-<?= $activity['type'] === 'work' ? 'primary' : 'success' ?> rounded-circle">
                                                <i class="bi bi-<?= $activity['type'] === 'work' ? 'clock' : 'cash-coin' ?> text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="mb-0"><?= htmlspecialchars($activity['description']) ?></h6>
                                            <small class="text-muted">
                                                <?= date('d.m.Y', strtotime($activity['date'])) ?> • 
                                                <?= $localization->get($activity['type']) ?>
                                            </small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-<?= $activity['type'] === 'work' ? 'primary' : 'success' ?> rounded-pill">
                                            <?= number_format($activity['amount'], 2) ?> €
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <?= $localization->get('quick_actions') ?>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="/company/add" class="btn btn-outline-primary w-100">
                                <i class="bi bi-building me-2"></i>
                                <?= $localization->get('add') ?> <?= $localization->get('company') ?>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="/work/add" class="btn btn-outline-success w-100">
                                <i class="bi bi-clock me-2"></i>
                                <?= $localization->get('add') ?> <?= $localization->get('work') ?>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="/money/add" class="btn btn-outline-info w-100">
                                <i class="bi bi-cash-coin me-2"></i>
                                <?= $localization->get('add') ?> <?= $localization->get('money') ?>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="/billing" class="btn btn-outline-warning w-100">
                                <i class="bi bi-receipt me-2"></i>
                                <?= $localization->get('generate') ?> <?= $localization->get('invoice') ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Dashboard charts and functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize charts
    initializeCharts();
    
    // Auto-refresh stats every 5 minutes
    setInterval(refreshStats, 300000);
});

function initializeCharts() {
    // Monthly earnings chart
    const earningsCtx = document.getElementById('earningsChart').getContext('2d');
    const earningsData = <?= json_encode($chartData['monthly_earnings'] ?? []) ?>;
    
    new Chart(earningsCtx, {
        type: 'line',
        data: {
            labels: earningsData.map(item => item.month),
            datasets: [{
                label: '<?= $localization->get('earnings') ?> (€)',
                data: earningsData.map(item => item.total),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
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
}

function refreshStats() {
    fetch('/api/stats')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('total_companies').textContent = data.data.total_companies.toLocaleString();
                document.getElementById('work_hours_month').textContent = data.data.work_hours_month.toFixed(1);
                document.getElementById('earnings_month').textContent = data.data.earnings_month.toFixed(2) + ' €';
                document.getElementById('pending_invoices').textContent = data.data.pending_invoices.toLocaleString();
            }
        })
        .catch(error => {
            console.error('Error refreshing stats:', error);
        });
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + R to refresh
    if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
        e.preventDefault();
        refreshStats();
    }
    
    // Ctrl/Cmd + 1-4 for quick actions
    if (e.ctrlKey || e.metaKey) {
        switch(e.key) {
            case '1':
                e.preventDefault();
                window.location.href = '/company/add';
                break;
            case '2':
                e.preventDefault();
                window.location.href = '/work/add';
                break;
            case '3':
                e.preventDefault();
                window.location.href = '/money/add';
                break;
            case '4':
                e.preventDefault();
                window.location.href = '/billing';
                break;
        }
    }
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?> 