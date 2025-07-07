<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="bi bi-eye me-2"></i>
                    <?= $localization->get('view') ?> <?= $localization->get('task') ?>
                </h4>
                <div class="btn-group">
                    <a href="/tasks/edit/<?= $task['id'] ?>" class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil me-1"></i>
                        <?= $localization->get('edit') ?>
                    </a>
                    <a href="/tasks/overview" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>
                        <?= $localization->get('back') ?>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3"><?= $localization->get('task_information') ?></h6>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?= $localization->get('task_name') ?></label>
                            <p class="form-control-plaintext"><?= htmlspecialchars($task['task_name']) ?></p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?= $localization->get('task_description') ?></label>
                            <p class="form-control-plaintext">
                                <?= $task['task_description'] ? nl2br(htmlspecialchars($task['task_description'])) : $localization->get('no_description') ?>
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?= $localization->get('task_priority') ?></label>
                            <p class="form-control-plaintext">
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
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?= $localization->get('task_assigned') ?></label>
                            <p class="form-control-plaintext">
                                <?= $task['task_assigned'] ? htmlspecialchars($task['task_assigned']) : $localization->get('not_assigned') ?>
                            </p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3"><?= $localization->get('task_details') ?></h6>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?= $localization->get('status') ?></label>
                            <p class="form-control-plaintext">
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
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?= $localization->get('task_due_date') ?></label>
                            <p class="form-control-plaintext">
                                <?= $task['task_due_date'] ? date('d.m.Y', strtotime($task['task_due_date'])) : $localization->get('no_due_date') ?>
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?= $localization->get('task_estimated_hours') ?></label>
                            <p class="form-control-plaintext"><?= number_format($task['task_estimated_hours'], 1) ?> <?= $localization->get('hours') ?></p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?= $localization->get('task_actual_hours') ?></label>
                            <p class="form-control-plaintext">
                                <?= $task['task_actual_hours'] ? number_format($task['task_actual_hours'], 1) . ' ' . $localization->get('hours') : $localization->get('not_tracked') ?>
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?= $localization->get('task_rate') ?></label>
                            <p class="form-control-plaintext">€<?= number_format($task['task_rate'], 2) ?> / <?= $localization->get('hour') ?></p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?= $localization->get('total') ?></label>
                            <p class="form-control-plaintext">
                                <strong>€<?= number_format($task['task_total'], 2) ?></strong>
                            </p>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3"><?= $localization->get('timestamps') ?></h6>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?= $localization->get('created') ?></label>
                            <p class="form-control-plaintext"><?= date('d.m.Y H:i', strtotime($task['created_at'])) ?></p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?= $localization->get('updated') ?></label>
                            <p class="form-control-plaintext"><?= date('d.m.Y H:i', strtotime($task['updated_at'])) ?></p>
                        </div>
                        
                        <?php if ($task['task_completed_date']): ?>
                            <div class="mb-3">
                                <label class="form-label fw-bold"><?= $localization->get('completed_date') ?></label>
                                <p class="form-control-plaintext"><?= date('d.m.Y H:i', strtotime($task['task_completed_date'])) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <hr>
                
                <div class="d-flex justify-content-between">
                    <?php if ($task['task_status'] !== 'completed'): ?>
                        <a href="/tasks/complete/<?= $task['id'] ?>" class="btn btn-success" 
                           onclick="return confirm('<?= $localization->get('confirm_complete_task') ?>')">
                            <i class="bi bi-check-circle me-1"></i>
                            <?= $localization->get('mark_completed') ?>
                        </a>
                    <?php else: ?>
                        <div></div>
                    <?php endif; ?>
                    
                    <div class="btn-group">
                        <a href="/tasks/edit/<?= $task['id'] ?>" class="btn btn-primary">
                            <i class="bi bi-pencil me-1"></i>
                            <?= $localization->get('edit') ?>
                        </a>
                        <a href="/tasks/overview" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>
                            <?= $localization->get('back_to_overview') ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?> 