<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="bi bi-pencil me-2"></i>
                    <?= $localization->get('edit') ?> <?= $localization->get('tour') ?>
                </h4>
            </div>
            <div class="card-body">
                <form method="POST" action="/tours/edit/<?= $tour['id'] ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tour_name" class="form-label"><?= $localization->get('tour_name') ?> *</label>
                                <input type="text" class="form-control" id="tour_name" name="tour_name" 
                                       value="<?= htmlspecialchars($tour['tour_name']) ?>" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tour_date" class="form-label"><?= $localization->get('tour_date') ?> *</label>
                                <input type="date" class="form-control" id="tour_date" name="tour_date" 
                                       value="<?= $tour['tour_date'] ?>" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tour_start" class="form-label"><?= $localization->get('tour_start') ?></label>
                                <input type="time" class="form-control" id="tour_start" name="tour_start" 
                                       value="<?= $tour['tour_start'] ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tour_end" class="form-label"><?= $localization->get('tour_end') ?></label>
                                <input type="time" class="form-control" id="tour_end" name="tour_end" 
                                       value="<?= $tour['tour_end'] ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tour_distance" class="form-label"><?= $localization->get('tour_distance') ?> (km)</label>
                                <input type="number" class="form-control" id="tour_distance" name="tour_distance" 
                                       step="0.1" min="0" value="<?= $tour['tour_distance'] ?>" placeholder="0.0">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tour_rate" class="form-label"><?= $localization->get('tour_rate') ?> (€/<?= $localization->get('hour') ?>)</label>
                                <input type="number" class="form-control" id="tour_rate" name="tour_rate" 
                                       step="0.01" min="0" value="<?= $tour['tour_rate'] ?>" placeholder="0.00">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tour_vehicle" class="form-label"><?= $localization->get('tour_vehicle') ?></label>
                                <input type="text" class="form-control" id="tour_vehicle" name="tour_vehicle" 
                                       value="<?= htmlspecialchars($tour['tour_vehicle']) ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tour_driver" class="form-label"><?= $localization->get('tour_driver') ?></label>
                                <input type="text" class="form-control" id="tour_driver" name="tour_driver" 
                                       value="<?= htmlspecialchars($tour['tour_driver']) ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tour_description" class="form-label"><?= $localization->get('tour_description') ?></label>
                        <textarea class="form-control" id="tour_description" name="tour_description" rows="3"><?= htmlspecialchars($tour['tour_description']) ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tour_status" class="form-label"><?= $localization->get('status') ?></label>
                                <select class="form-select" id="tour_status" name="tour_status">
                                    <option value="pending" <?= $tour['status'] === 'pending' ? 'selected' : '' ?>>
                                        <?= $localization->get('status_pending') ?>
                                    </option>
                                    <option value="approved" <?= $tour['status'] === 'approved' ? 'selected' : '' ?>>
                                        <?= $localization->get('status_approved') ?>
                                    </option>
                                    <option value="completed" <?= $tour['status'] === 'completed' ? 'selected' : '' ?>>
                                        <?= $localization->get('status_completed') ?>
                                    </option>
                                    <option value="cancelled" <?= $tour['status'] === 'cancelled' ? 'selected' : '' ?>>
                                        <?= $localization->get('status_cancelled') ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><?= $localization->get('amount_preview') ?></label>
                                <div class="form-control-plaintext" id="amount_preview">
                                    <strong>€<?= number_format($tour['tour_total'], 2) ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="/tours/view/<?= $tour['id'] ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>
                            <?= $localization->get('cancel') ?>
                        </a>
                        
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check me-1"></i>
                                <?= $localization->get('save_changes') ?>
                            </button>
                            <a href="/tours/overview" class="btn btn-outline-secondary">
                                <i class="bi bi-list me-1"></i>
                                <?= $localization->get('back_to_overview') ?>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startInput = document.getElementById('tour_start');
    const endInput = document.getElementById('tour_end');
    const rateInput = document.getElementById('tour_rate');
    const amountPreview = document.getElementById('amount_preview');
    
    function updateAmountPreview() {
        const start = startInput.value;
        const end = endInput.value;
        const rate = parseFloat(rateInput.value) || 0;
        
        if (start && end) {
            const startTime = new Date('2000-01-01T' + start);
            const endTime = new Date('2000-01-01T' + end);
            const duration = (endTime - startTime) / (1000 * 60 * 60); // Convert to hours
            const total = duration * rate;
            
            amountPreview.innerHTML = `<strong>€${total.toFixed(2)}</strong>`;
        } else {
            amountPreview.innerHTML = '<strong>€0.00</strong>';
        }
    }
    
    startInput.addEventListener('input', updateAmountPreview);
    endInput.addEventListener('input', updateAmountPreview);
    rateInput.addEventListener('input', updateAmountPreview);
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?> 