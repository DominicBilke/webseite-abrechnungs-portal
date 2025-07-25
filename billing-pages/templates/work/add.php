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
                <i class="bi bi-plus-circle me-2"></i>
                <?= $localization->get('add') ?> <?= $localization->get('work') ?>
            </h1>
            <p class="text-muted"><?= $localization->get('add') ?> <?= $localization->get('new') ?> <?= $localization->get('work') ?> <?= $localization->get('entry') ?></p>
        </div>
        <div>
            <a href="/work/overview" class="btn btn-outline-secondary">
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
                        <?= $localization->get('new') ?> <?= $localization->get('work') ?> <?= $localization->get('entry') ?>
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="/work/add" id="workForm">
                        <div class="row">
                            <!-- Work Information -->
                            <div class="col-md-6">
                                <h6 class="mb-3"><?= $localization->get('work') ?> <?= $localization->get('information') ?></h6>
                                
                                <div class="mb-3">
                                    <label for="work_date" class="form-label">
                                        <?= $localization->get('work_date') ?> <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control" id="work_date" name="work_date" 
                                           value="<?= date('Y-m-d') ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="work_hours" class="form-label">
                                        <?= $localization->get('work_hours') ?> <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control" id="work_hours" name="work_hours" 
                                           value="8.00" step="0.25" min="0" required>
                                    <div class="form-text"><?= $localization->get('enter_hours_decimal') ?></div>
                                </div>

                                <div class="mb-3">
                                    <label for="work_rate" class="form-label">
                                        <?= $localization->get('work_rate') ?> (€/<?= $localization->get('hour') ?>)
                                    </label>
                                    <input type="number" class="form-control" id="work_rate" name="work_rate" 
                                           value="50.00" step="0.01" min="0">
                                </div>

                                <div class="mb-3">
                                    <label for="work_type" class="form-label">
                                        <?= $localization->get('work_type') ?>
                                    </label>
                                    <select class="form-select" id="work_type" name="work_type">
                                        <option value=""><?= $localization->get('select_work_type') ?></option>
                                        <option value="development"><?= $localization->get('development') ?></option>
                                        <option value="design"><?= $localization->get('design') ?></option>
                                        <option value="consulting"><?= $localization->get('consulting') ?></option>
                                        <option value="maintenance"><?= $localization->get('maintenance') ?></option>
                                        <option value="testing"><?= $localization->get('testing') ?></option>
                                        <option value="other"><?= $localization->get('other') ?></option>
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
                                              rows="4" required placeholder="<?= $localization->get('describe_your_work') ?>"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="work_project" class="form-label">
                                        <?= $localization->get('work_project') ?>
                                    </label>
                                    <input type="text" class="form-control" id="work_project" name="work_project" 
                                           value="<?= htmlspecialchars($_GET['project'] ?? '') ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="work_client" class="form-label">
                                        <?= $localization->get('work_client') ?>
                                    </label>
                                    <input type="text" class="form-control" id="work_client" name="work_client" 
                                           value="<?= htmlspecialchars($_GET['client'] ?? '') ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">
                                        <?= $localization->get('status') ?>
                                    </label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="pending"><?= $localization->get('pending') ?></option>
                                        <option value="completed"><?= $localization->get('completed') ?></option>
                                        <option value="billed"><?= $localization->get('billed') ?></option>
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
                                            <span id="calculatedTotal" class="fs-5 text-success">400.00 €</span>
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

                        <!-- Quick Actions -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="mb-3"><?= $localization->get('quick_actions') ?></h6>
                                        <div class="d-flex gap-2 flex-wrap">
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setHours(4)">
                                                4 <?= $localization->get('hours') ?>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setHours(6)">
                                                6 <?= $localization->get('hours') ?>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setHours(8)">
                                                8 <?= $localization->get('hours') ?>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setHours(10)">
                                                10 <?= $localization->get('hours') ?>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setHours(12)">
                                                12 <?= $localization->get('hours') ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <div>
                                <a href="/work/overview" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-1"></i>
                                    <?= $localization->get('cancel') ?>
                                </a>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i>
                                    <?= $localization->get('save') ?> <?= $localization->get('work') ?>
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
// Quick action functions
function setHours(hours) {
    document.getElementById('work_hours').value = hours;
    calculateTotal();
}

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
        
        localStorage.setItem('work_draft_new', JSON.stringify(draftData));
        
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
    const savedDraft = localStorage.getItem('work_draft_new');
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
        localStorage.removeItem('work_draft_new');
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
        window.location.href = '/work/overview';
    }
    
    // Number keys for quick hours
    if (e.key >= '1' && e.key <= '9' && !e.ctrlKey && !e.metaKey) {
        const hours = parseInt(e.key);
        if (hours <= 12) {
            document.getElementById('work_hours').value = hours;
            calculateTotal();
        }
    }
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?> 