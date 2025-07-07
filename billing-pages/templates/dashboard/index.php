<?php
$localization = new BillingPages\Core\Localization();
$session = new BillingPages\Core\Session();
?>

<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0"><?= $localization->get('dashboard_welcome') ?></h1>
            <p class="text-muted"><?= $localization->get('dashboard_stats') ?></p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="window.print()">
                <i class="bi bi-printer me-1"></i>
                <?= $localization->get('print') ?>
            </button>
            <a href="/reports" class="btn btn-primary">
                <i class="bi bi-graph-up me-1"></i>
                <?= $localization->get('reports') ?>
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-cash-coin text-primary fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title text-muted mb-1"><?= $localization->get('dashboard_total_revenue') ?></h6>
                            <h4 class="mb-0">€<?= number_format($stats['total_revenue'], 2, ',', '.') ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-receipt text-success fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title text-muted mb-1"><?= $localization->get('dashboard_total_invoices') ?></h6>
                            <h4 class="mb-0"><?= number_format($stats['total_invoices']) ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-clock text-warning fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title text-muted mb-1"><?= $localization->get('dashboard_pending_amount') ?></h6>
                            <h4 class="mb-0">€<?= number_format($stats['pending_amount'], 2, ',', '.') ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-exclamation-triangle text-danger fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title text-muted mb-1"><?= $localization->get('dashboard_overdue') ?></h6>
                            <h4 class="mb-0"><?= number_format($stats['overdue_invoices']) ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-graph-up me-2"></i>
                        <?= $localization->get('dashboard_stats') ?>
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-pie-chart me-2"></i>
                        <?= $localization->get('dashboard_stats') ?>
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="billingTypeChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Entries and Quick Actions -->
    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clock-history me-2"></i>
                        <?= $localization->get('dashboard_recent') ?>
                    </h5>
                    <a href="/reports" class="btn btn-sm btn-outline-primary">
                        <?= $localization->get('view_all') ?>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th><?= $localization->get('table_id') ?></th>
                                    <th><?= $localization->get('table_description') ?></th>
                                    <th><?= $localization->get('table_amount') ?></th>
                                    <th><?= $localization->get('table_date') ?></th>
                                    <th><?= $localization->get('table_actions') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($recentEntries)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                            <?= $localization->get('no_entries_found') ?>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($recentEntries as $entry): ?>
                                        <tr>
                                            <td>
                                                <span class="badge bg-secondary">#<?= $entry['id'] ?></span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-<?= $this->getEntryIcon($entry['type']) ?> me-2 text-muted"></i>
                                                    <?= htmlspecialchars($entry['description']) ?>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if ($entry['amount'] > 0): ?>
                                                    <span class="text-success fw-bold">
                                                        €<?= number_format($entry['amount'], 2, ',', '.') ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= date('d.m.Y', strtotime($entry['created_at'])) ?>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="/<?= $entry['type'] ?>/edit/<?= $entry['id'] ?>" 
                                                       class="btn btn-outline-primary btn-sm">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <a href="/<?= $entry['type'] ?>/view/<?= $entry['id'] ?>" 
                                                       class="btn btn-outline-info btn-sm">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-lightning me-2"></i>
                        <?= $localization->get('quick_actions') ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="/company/add" class="btn btn-outline-primary">
                            <i class="bi bi-building me-2"></i>
                            <?= $localization->get('add') ?> <?= $localization->get('company') ?>
                        </a>
                        <a href="/work/add" class="btn btn-outline-success">
                            <i class="bi bi-briefcase me-2"></i>
                            <?= $localization->get('add') ?> <?= $localization->get('work') ?>
                        </a>
                        <a href="/money/add" class="btn btn-outline-warning">
                            <i class="bi bi-cash-coin me-2"></i>
                            <?= $localization->get('add') ?> <?= $localization->get('money') ?>
                        </a>
                        <a href="/tasks/add" class="btn btn-outline-info">
                            <i class="bi bi-list-task me-2"></i>
                            <?= $localization->get('add') ?> <?= $localization->get('task') ?>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Pending Tasks -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-list-check me-2"></i>
                        <?= $localization->get('dashboard_pending') ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="h4 mb-0"><?= $stats['pending_tasks'] ?></span>
                        <div class="progress flex-grow-1 mx-3" style="height: 8px;">
                            <div class="progress-bar" style="width: <?= min(100, ($stats['pending_tasks'] / 10) * 100) ?>%"></div>
                        </div>
                        <small class="text-muted"><?= $localization->get('tasks') ?></small>
                    </div>
                    <a href="/tasks" class="btn btn-sm btn-outline-primary w-100">
                        <?= $localization->get('view_all') ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: '<?= $localization->get('dashboard_total_revenue') ?>',
            data: [12000, 19000, 15000, 25000, 22000, 30000],
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '€' + value.toLocaleString();
                    }
                }
            }
        }
    }
});

// Billing Type Chart
const billingCtx = document.getElementById('billingTypeChart').getContext('2d');
new Chart(billingCtx, {
    type: 'doughnut',
    data: {
        labels: ['<?= $localization->get('company_billing') ?>', '<?= $localization->get('work_billing') ?>', '<?= $localization->get('money_billing') ?>'],
        datasets: [{
            data: [30, 45, 25],
            backgroundColor: [
                'rgba(255, 99, 132, 0.8)',
                'rgba(54, 162, 235, 0.8)',
                'rgba(255, 205, 86, 0.8)'
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
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?> 