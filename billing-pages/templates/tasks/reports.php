<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="bi bi-graph-up me-2"></i>
                    <?= $localization->get('tasks') ?> - <?= $localization->get('reports') ?>
                </h4>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <form method="GET" class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label for="from_date" class="form-label"><?= $localization->get('report_from_date') ?></label>
                        <input type="date" class="form-control" id="from_date" name="from_date" 
                               value="<?= htmlspecialchars($filters['from_date']) ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="to_date" class="form-label"><?= $localization->get('report_to_date') ?></label>
                        <input type="date" class="form-control" id="to_date" name="to_date" 
                               value="<?= htmlspecialchars($filters['to_date']) ?>">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bi bi-search me-1"></i>
                            <?= $localization->get('filter') ?>
                        </button>
                        <a href="/tasks/reports" class="btn btn-secondary">
                            <i class="bi bi-arrow-clockwise me-1"></i>
                            <?= $localization->get('reset') ?>
                        </a>
                    </div>
                </form>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title"><?= $localization->get('total_tasks') ?></h6>
                                        <h3 class="mb-0"><?= $stats['total_tasks'] ?></h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-list-task fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title"><?= $localization->get('completed_tasks') ?></h6>
                                        <h3 class="mb-0"><?= $stats['completed_tasks'] ?></h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-check-circle fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title"><?= $localization->get('pending_tasks') ?></h6>
                                        <h3 class="mb-0"><?= $stats['pending_tasks'] ?></h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-clock fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title"><?= $localization->get('total_hours') ?></h6>
                                        <h3 class="mb-0"><?= number_format($stats['total_estimated_hours'], 1) ?></h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-clock-history fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tasks Table -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th><?= $localization->get('task_name') ?></th>
                                <th><?= $localization->get('task_priority') ?></th>
                                <th><?= $localization->get('task_due_date') ?></th>
                                <th><?= $localization->get('task_estimated_hours') ?></th>
                                <th><?= $localization->get('task_rate') ?></th>
                                <th><?= $localization->get('total') ?></th>
                                <th><?= $localization->get('status') ?></th>
                                <th><?= $localization->get('actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($tasks)): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted">
                                        <?= $localization->get('no_tasks_found') ?>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($tasks as $task): ?>
                                    <tr>
                                        <td>
                                            <strong><?= htmlspecialchars($task['task_name']) ?></strong>
                                            <?php if ($task['task_description']): ?>
                                                <br><small class="text-muted"><?= htmlspecialchars(substr($task['task_description'], 0, 50)) ?>...</small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                            $priorityClass = match($task['task_priority']) {
                                                'urgent' => 'danger',
                                                'high' => 'warning',
                                                'medium' => 'info',
                                                'low' => 'secondary',
                                                default => 'secondary'
                                            };
                                            ?>
                                            <span class="badge bg-<?= $priorityClass ?>">
                                                <?= $localization->get('priority_' . $task['task_priority']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($task['task_due_date']): ?>
                                                <?= date('d.m.Y', strtotime($task['task_due_date'])) ?>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= number_format($task['task_estimated_hours'], 1) ?></td>
                                        <td>€<?= number_format($task['task_rate'], 2) ?></td>
                                        <td><strong>€<?= number_format($task['task_total'], 2) ?></strong></td>
                                        <td>
                                            <?php
                                            $statusClass = match($task['task_status']) {
                                                'completed' => 'success',
                                                'in_progress' => 'warning',
                                                'pending' => 'secondary',
                                                'cancelled' => 'danger',
                                                default => 'secondary'
                                            };
                                            ?>
                                            <span class="badge bg-<?= $statusClass ?>">
                                                <?= $localization->get('status_' . $task['task_status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="/tasks/view/<?= $task['id'] ?>" class="btn btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="/tasks/edit/<?= $task['id'] ?>" class="btn btn-outline-secondary">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Export Buttons -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <a href="/tasks/overview" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>
                            <?= $localization->get('back_to_overview') ?>
                        </a>
                    </div>
                    <div>
                        <button type="button" class="btn btn-success" onclick="exportToPDF()">
                            <i class="bi bi-file-pdf me-1"></i>
                            <?= $localization->get('export_pdf') ?>
                        </button>
                        <button type="button" class="btn btn-info" onclick="exportToExcel()">
                            <i class="bi bi-file-excel me-1"></i>
                            <?= $localization->get('export_excel') ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function exportToPDF() {
    // Implementation for PDF export
    alert('PDF export functionality will be implemented here');
}

function exportToExcel() {
    // Implementation for Excel export
    alert('Excel export functionality will be implemented here');
}
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?> 