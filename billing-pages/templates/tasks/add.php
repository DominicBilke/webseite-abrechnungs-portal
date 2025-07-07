<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="bi bi-plus-circle me-2"></i>
                    <?= $localization->get('add') ?> <?= $localization->get('task') ?>
                </h4>
            </div>
            <div class="card-body">
                <form method="POST" action="/tasks/add">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="task_name" class="form-label"><?= $localization->get('task_name') ?> *</label>
                                <input type="text" class="form-control" id="task_name" name="task_name" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="task_priority" class="form-label"><?= $localization->get('task_priority') ?></label>
                                <select class="form-select" id="task_priority" name="task_priority">
                                    <option value="low"><?= $localization->get('priority_low') ?></option>
                                    <option value="medium" selected><?= $localization->get('priority_medium') ?></option>
                                    <option value="high"><?= $localization->get('priority_high') ?></option>
                                    <option value="urgent"><?= $localization->get('priority_urgent') ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="task_description" class="form-label"><?= $localization->get('task_description') ?></label>
                        <textarea class="form-control" id="task_description" name="task_description" rows="3"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="task_assigned" class="form-label"><?= $localization->get('task_assigned') ?></label>
                                <input type="text" class="form-control" id="task_assigned" name="task_assigned">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="task_due_date" class="form-label"><?= $localization->get('task_due_date') ?></label>
                                <input type="date" class="form-control" id="task_due_date" name="task_due_date">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="task_estimated_hours" class="form-label"><?= $localization->get('task_estimated_hours') ?></label>
                                <input type="number" class="form-control" id="task_estimated_hours" name="task_estimated_hours" 
                                       step="0.5" min="0" placeholder="0.0">
                                <div class="form-text"><?= $localization->get('enter_hours_decimal') ?></div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="task_rate" class="form-label"><?= $localization->get('task_rate') ?> (€/<?= $localization->get('hour') ?>)</label>
                                <input type="number" class="form-control" id="task_rate" name="task_rate" 
                                       step="0.01" min="0" placeholder="0.00">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><?= $localization->get('amount_preview') ?></label>
                                <div class="form-control-plaintext" id="amount_preview">
                                    <strong>€0.00</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="/tasks/overview" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>
                            <?= $localization->get('cancel') ?>
                        </a>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check me-1"></i>
                            <?= $localization->get('save') ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const estimatedHoursInput = document.getElementById('task_estimated_hours');
    const rateInput = document.getElementById('task_rate');
    const amountPreview = document.getElementById('amount_preview');
    
    function updateAmountPreview() {
        const hours = parseFloat(estimatedHoursInput.value) || 0;
        const rate = parseFloat(rateInput.value) || 0;
        const total = hours * rate;
        
        amountPreview.innerHTML = `<strong>€${total.toFixed(2)}</strong>`;
    }
    
    estimatedHoursInput.addEventListener('input', updateAmountPreview);
    rateInput.addEventListener('input', updateAmountPreview);
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?> 