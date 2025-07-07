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
                <i class="bi bi-pencil me-2"></i>
                <?= $localization->get('edit') ?> <?= $localization->get('work') ?>
            </h1>
            <p class="text-muted"><?= $localization->get('update') ?> <?= $localization->get('work') ?> <?= $localization->get('entry') ?> <?= $localization->get('information') ?></p>
        </div>
        <div>
            <a href="/work/view/<?= $work['id'] ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                <?= $localization->get('back') ?>
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-clock me-2"></i>
                        <?= htmlspecialchars($work['work_description']) ?>
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="/work/update/<?= $work['id'] ?>" id="workForm">
                        <div class="row">
                            <!-- Work Information -->
                            <div class="col-md-6">
                                <h6 class="mb-3"><?= $localization->get('work') ?> <?= $localization->get('information') ?></h6>
                                
                                <div class="mb-3">
                                    <label for="work_date" class="form-label">
                                        <?= $localization->get('work_date') ?> <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control" id="work_date" name="work_date" 
                                           value="<?= $work['work_date'] ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="work_hours" class="form-label">
                                        <?= $localization->get('work_hours') ?> <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control" id="work_hours" name="work_hours" 
                                           value="<?= $work['work_hours'] ?>" step="0.25" min="0" required>
                                    <div class="form-text"><?= $localization->get('enter_hours_decimal') ?></div>
                                </div>

                                <div class="mb-3">
                                    <label for="work_rate" class="form-label">
                                        <?= $localization->get('work_rate') ?> (€/<?= $localization->get('hour') ?>)
                                    </label>
                                    <input type="number" class="form-control" id="work_rate" name="work_rate" 
                                           value="<?= $work['work_rate'] ?>" step="0.01" min="0">
                                </div>

                                <div class="mb-3">
                                    <label for="work_type" class="form-label">
                                        <?= $localization->get('work_type') ?>
                                    </label>
                                    <select class="form-select" id="work_type" name="work_type">
                                        <option value=""><?= $localization->get('select_work_type') ?></option>
                                        <option value="development" <?= $work['work_type'] === 'development' ? 'selected' : '' ?>>
                                            <?= $localization->get('development') ?>
                                        </option>
                                        <option value="design" <?= $work['work_type'] === 'design' ? 'selected' : '' ?>>
                                            <?= $localization->get('design') ?>
                                        </option>
                                        <option value="consulting" <?= $work['work_type'] === 'consulting' ? 'selected' : '' ?>>
                                            <?= $localization->get('consulting') ?>
                                        </option>
                                        <option value="maintenance" <?= $work['work_type'] === 'maintenance' ? 'selected' : '' ?>>
                                            <?= $localization->get('maintenance') ?>
                                        </option>
                                        <option value="testing" <?= $work['work_type'] === 'testing' ? 'selected' : '' ?>>
                                            <?= $localization->get('testing') ?>
                                        </option>
                                        <option value="other" <?= $work['work_type'] === 'other' ? 'selected' : '' ?>>
                                            <?= $localization->get('other') ?>
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Project and Client Information -->
                            <div class="col-md-6">
                                <h6 class="mb-3"><?= $localization->get('project') ?> <?= $localization->get('and') ?> <?= $localization->get('client') ?></h6>
                                
                                <div class="mb-3">
                                    <label for="work_description" class="form-label">
                                        <?= $localization->get('work_description') ?> <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control" id="work_description" name="work_description" 
                                              rows="4" required><?= htmlspecialchars($work['work_description']) ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="work_project" class="form-label">
                                        <?= $localization->get('work_project') ?>
                                    </label>
                                    <input type="text" class="form-control" id="work_project" name="work_project" 
                                           value="<?= htmlspecialchars($work['work_project']) ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="work_client" class="form-label">
                                        <?= $localization->get('work_client') ?>
                                    </label>
                                    <input type="text" class="form-control" id="work_client" name="work_client" 
                                           value="<?= htmlspecialchars($work['work_client']) ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">
                                        <?= $localization->get('status') ?>
                                    </label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="pending" <?= $work['status'] === 'pending' ? 'selected' : '' ?>>
                                            <?= $localization->get('pending') ?>
                                        </option>
                                        <option value="completed" <?= $work['status'] === 'completed' ? 'selected' : '' ?>>
                                            <?= $localization->get('completed') ?>
                                        </option>
                                        <option value="billed" <?= $work['status'] === 'billed' ? 'selected' : '' ?>>
                                            <?= $localization->get('billed') ?>
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Total Calculation -->
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <strong><?= $localization->get('calculated_total') ?>:</strong>
                                            <span id="calculatedTotal" class="fs-5 text-success">
                                                <?= number_format($work['work_total'], 2) ?> €
                                            </span>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <small class="text-muted">
                                                <?= $localization->get('hours') ?> × <?= $localization->get('rate') ?> = <?= $localization->get('total') ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <div>
                                <a href="/work/view/<?= $work['id'] ?>" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-1"></i>
                                    <?= $localization->get('cancel') ?>
                                </a>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i>
                                    <?= $localization->get('save') ?> <?= $localization->get('changes') ?>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Form validation and functionality
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('workForm');
    const workDateInput = document.getElementById('work_date');
    const workHoursInput = document.getElementById('work_hours');
    const workRateInput = document.getElementById('work_rate');
    const workDescriptionInput = document.getElementById('work_description');
    const calculatedTotalSpan = document.getElementById('calculatedTotal');

    // Real-time total calculation
    function calculateTotal() {
        const hours = parseFloat(workHoursInput.value) || 0;
        const rate = parseFloat(workRateInput.value) || 0;
        const total = hours * rate;
        calculatedTotalSpan.textContent = total.toFixed(2) + ' €';
    }

    workHoursInput.addEventListener('input', calculateTotal);
    workRateInput.addEventListener('input', calculateTotal);

    // Real-time validation
    workDateInput.addEventListener('input', function() {
        if (!this.value) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });

    workHoursInput.addEventListener('input', function() {
        const hours = parseFloat(this.value);
        if (!this.value || hours <= 0) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
        calculateTotal();
    });

    workDescriptionInput.addEventListener('input', function() {
        if (this.value.trim() === '') {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        let isValid = true;

        if (!workDateInput.value) {
            workDateInput.classList.add('is-invalid');
            isValid = false;
        }

        if (!workHoursInput.value || parseFloat(workHoursInput.value) <= 0) {
            workHoursInput.classList.add('is-invalid');
            isValid = false;
        }

        if (workDescriptionInput.value.trim() === '') {
            workDescriptionInput.classList.add('is-invalid');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            return false;
        }
    });

    // Auto-save draft functionality
    let autoSaveTimer;
    const inputs = form.querySelectorAll('input, textarea, select');
    
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(() => {
                saveDraft();
            }, 2000);
        });
    });

    function saveDraft() {
        const formData = new FormData(form);
        const draftData = {};
        
        for (let [key, value] of formData.entries()) {
            draftData[key] = value;
        }
        
        localStorage.setItem('work_draft_<?= $work['id'] ?>', JSON.stringify(draftData));
        
        // Show auto-save indicator
        const indicator = document.createElement('div');
        indicator.className = 'position-fixed bottom-0 end-0 m-3';
        indicator.innerHTML = `
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-1"></i>
                <?= $localization->get('draft_saved') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        document.body.appendChild(indicator);
        
        setTimeout(() => {
            indicator.remove();
        }, 3000);
    }

    // Load draft on page load
    const savedDraft = localStorage.getItem('work_draft_<?= $work['id'] ?>');
    if (savedDraft) {
        const draftData = JSON.parse(savedDraft);
        Object.keys(draftData).forEach(key => {
            const input = form.querySelector(`[name="${key}"]`);
            if (input && input.value === '') {
                input.value = draftData[key];
            }
        });
        calculateTotal();
    }

    // Clear draft after successful save
    form.addEventListener('submit', function() {
        localStorage.removeItem('work_draft_<?= $work['id'] ?>');
    });

    // Initialize total calculation
    calculateTotal();
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + S to save
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        document.getElementById('workForm').submit();
    }
    
    // Ctrl/Cmd + Z to cancel
    if ((e.ctrlKey || e.metaKey) && e.key === 'z') {
        e.preventDefault();
        window.location.href = '/work/view/<?= $work['id'] ?>';
    }
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?> 