<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="bi bi-list-task me-2"></i>
                <?= $localization->get('tasks') ?> - <?= $localization->get('overview') ?>
            </h1>
            <a href="/tasks/add" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>
                <?= $localization->get('add') ?> <?= $localization->get('task') ?>
            </a>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title"><?= $localization->get('total_tasks') ?></h6>
                        <h3 class="mb-0"><?= $pagination['total_records'] ?? 0 ?></h3>
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
                        <h3 class="mb-0"><?= $stats['completed_count'] ?? 0 ?></h3>
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
                        <h3 class="mb-0"><?= $stats['pending_count'] ?? 0 ?></h3>
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
                        <h3 class="mb-0"><?= number_format($stats['total_hours'] ?? 0, 1) ?>h</h3>
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
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h5 class="mb-0"><?= $localization->get('tasks') ?></h5>
            </div>
            <div class="col-auto">
                <div class="input-group">
                    <input type="text" class="form-control" id="searchInput" placeholder="<?= $localization->get('search_tasks') ?>">
                    <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <?php if (empty($tasks)): ?>
            <div class="text-center py-5">
                <i class="bi bi-list-task fs-1 text-muted mb-3"></i>
                <h5 class="text-muted"><?= $localization->get('no_tasks') ?></h5>
                <p class="text-muted"><?= $localization->get('no_tasks_description') ?></p>
                <a href="/tasks/add" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>
                    <?= $localization->get('add_first_task') ?>
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th><?= $localization->get('task_name') ?></th>
                            <th><?= $localization->get('assigned_to') ?></th>
                            <th><?= $localization->get('due_date') ?></th>
                            <th><?= $localization->get('priority') ?></th>
                            <th><?= $localization->get('status') ?></th>
                            <th><?= $localization->get('hours') ?></th>
                            <th><?= $localization->get('actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tasks as $task): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($task['task_name']) ?></strong>
                                </td>
                                <td><?= htmlspecialchars($task['task_assigned'] ?: $localization->get('unassigned')) ?></td>
                                <td>
                                    <?php 
                                    $dueDate = strtotime($task['task_due_date']);
                                    $isOverdue = $dueDate < time() && $task['task_status'] !== 'completed';
                                    ?>
                                    <span class="<?= $isOverdue ? 'text-danger fw-bold' : '' ?>">
                                        <?= date('d.m.Y', $dueDate) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    $priorityClass = match($task['task_priority']) {
                                        'urgent' => 'bg-danger',
                                        'high' => 'bg-warning',
                                        'medium' => 'bg-info',
                                        'low' => 'bg-secondary',
                                        default => 'bg-secondary'
                                    };
                                    $priorityText = $localization->get('priority_' . $task['task_priority']);
                                    ?>
                                    <span class="badge <?= $priorityClass ?>"><?= $priorityText ?></span>
                                </td>
                                <td>
                                    <?php
                                    $statusClass = match($task['task_status']) {
                                        'pending' => 'bg-warning',
                                        'in_progress' => 'bg-info',
                                        'completed' => 'bg-success',
                                        'cancelled' => 'bg-danger',
                                        default => 'bg-secondary'
                                    };
                                    $statusText = $localization->get('status_' . $task['task_status']);
                                    ?>
                                    <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                                </td>
                                <td>
                                    <?= $task['task_actual_hours'] ? number_format($task['task_actual_hours'], 1) . 'h' : '-' ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="/tasks/view/<?= $task['id'] ?>" 
                                           class="btn btn-outline-primary" 
                                           title="<?= $localization->get('view') ?>">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="/tasks/edit/<?= $task['id'] ?>" 
                                           class="btn btn-outline-secondary" 
                                           title="<?= $localization->get('edit') ?>">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <?php if ($task['task_status'] !== 'completed'): ?>
                                            <a href="/tasks/complete/<?= $task['id'] ?>" 
                                               class="btn btn-outline-success" 
                                               title="<?= $localization->get('mark_completed') ?>"
                                               onclick="return confirm('<?= $localization->get('confirm_mark_completed') ?>')">
                                                <i class="bi bi-check-circle"></i>
                                            </a>
                                        <?php endif; ?>
                                        <button type="button" 
                                                class="btn btn-outline-danger" 
                                                title="<?= $localization->get('delete') ?>"
                                                onclick="deleteTask(<?= $task['id'] ?>)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($pagination['total'] > 1): ?>
                <nav aria-label="<?= $localization->get('pagination') ?>">
                    <ul class="pagination justify-content-center">
                        <?php if ($pagination['current'] > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $pagination['current'] - 1 ?>">
                                    <?= $localization->get('previous') ?>
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $pagination['total']; $i++): ?>
                            <li class="page-item <?= $i === $pagination['current'] ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($pagination['current'] < $pagination['total']): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $pagination['current'] + 1 ?>">
                                    <?= $localization->get('next') ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<script>
function deleteTask(id) {
    if (confirm('<?= $localization->get('confirm_delete_task') ?>')) {
        fetch(`/tasks/delete/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || '<?= $localization->get('error_delete') ?>');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('<?= $localization->get('error_delete') ?>');
        });
    }
}

// Search functionality
document.getElementById('searchBtn').addEventListener('click', function() {
    const query = document.getElementById('searchInput').value;
    if (query.trim()) {
        window.location.href = `/tasks/search?q=${encodeURIComponent(query)}`;
    }
});

document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        document.getElementById('searchBtn').click();
    }
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?> 