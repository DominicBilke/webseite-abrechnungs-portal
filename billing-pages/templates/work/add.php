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
                <i class="bi bi-clock me-2"></i>
                <?= $localization->get('add') ?> <?= $localization->get('work') ?>
            </h1>
            <p class="text-muted"><?= $localization->get('form_enter') ?> <?= $localization->get('work') ?> <?= $localization->get('form_required') ?></p>
        </div>
        <div>
            <a href="/work/overview" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                <?= $localization->get('back') ?>
            </a>
        </div>
    </div>

    <!-- Work Form -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-plus-circle me-2"></i>
                        <?= $localization->get('work') ?> <?= $localization->get('form_required') ?>
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/work/add" data-validate="true">
                        <div class="row">
                            <!-- Work Date -->
                            <div class="col-md-6 mb-3">
                                <label for="work_date" class="form-label">
                                    <?= $localization->get('work_date') ?> <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control" id="work_date" name="work_date" 
                                       value="<?= date('Y-m-d') ?>" required>
                                <div class="invalid-feedback">
                                    <?= $localization->get('validation_required', ['field' => $localization->get('work_date')]) ?>
                                </div>
                            </div>

                            <!-- Work Hours -->
                            <div class="col-md-6 mb-3">
                                <label for="work_hours" class="form-label">
                                    <?= $localization->get('work_hours') ?> <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="work_hours" name="work_hours" 
                                           step="0.25" min="0" max="24" placeholder="0.00" required>
                                    <span class="input-group-text"><?= $localization->get('hours') ?></span>
                                </div>
                                <div class="invalid-feedback">
                                    <?= $localization->get('validation_required', ['field' => $localization->get('work_hours')]) ?>
                                </div>
                            </div>

                            <!-- Work Rate -->
                            <div class="col-md-6 mb-3">
                                <label for="work_rate" class="form-label">
                                    <?= $localization->get('work_rate') ?> <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="work_rate" name="work_rate" 
                                           step="0.01" min="0" placeholder="0.00" required>
                                    <span class="input-group-text">€/<?= $localization->get('hour') ?></span>
                                </div>
                                <div class="invalid-feedback">
                                    <?= $localization->get('validation_required', ['field' => $localization->get('work_rate')]) ?>
                                </div>
                            </div>

                            <!-- Work Type -->
                            <div class="col-md-6 mb-3">
                                <label for="work_type" class="form-label">
                                    <?= $localization->get('work_type') ?>
                                </label>
                                <select class="form-select" id="work_type" name="work_type">
                                    <option value=""><?= $localization->get('select') ?> <?= $localization->get('work_type') ?></option>
                                    <option value="development"><?= $localization->get('development') ?></option>
                                    <option value="design"><?= $localization->get('design') ?></option>
                                    <option value="consulting"><?= $localization->get('consulting') ?></option>
                                    <option value="maintenance"><?= $localization->get('maintenance') ?></option>
                                    <option value="testing"><?= $localization->get('testing') ?></option>
                                    <option value="documentation"><?= $localization->get('documentation') ?></option>
                                    <option value="meeting"><?= $localization->get('meeting') ?></option>
                                    <option value="other"><?= $localization->get('other') ?></option>
                                </select>
                            </div>

                            <!-- Work Project -->
                            <div class="col-md-6 mb-3">
                                <label for="work_project" class="form-label">
                                    <?= $localization->get('work_project') ?>
                                </label>
                                <input type="text" class="form-control" id="work_project" name="work_project" 
                                       placeholder="<?= $localization->get('form_enter') ?> <?= $localization->get('work_project') ?>">
                            </div>

                            <!-- Work Client -->
                            <div class="col-md-6 mb-3">
                                <label for="work_client" class="form-label">
                                    <?= $localization->get('work_client') ?>
                                </label>
                                <input type="text" class="form-control" id="work_client" name="work_client" 
                                       placeholder="<?= $localization->get('form_enter') ?> <?= $localization->get('work_client') ?>">
                            </div>

                            <!-- Work Description -->
                            <div class="col-12 mb-3">
                                <label for="work_description" class="form-label">
                                    <?= $localization->get('work_description') ?> <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control" id="work_description" name="work_description" rows="4" 
                                          placeholder="<?= $localization->get('form_enter') ?> <?= $localization->get('work_description') ?>" required></textarea>
                                <div class="invalid-feedback">
                                    <?= $localization->get('validation_required', ['field' => $localization->get('work_description')]) ?>
                                </div>
                            </div>

                            <!-- Total Calculation -->
                            <div class="col-12 mb-3">
                                <div class="alert alert-info">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong><?= $localization->get('work_hours') ?>:</strong>
                                            <span id="display_hours">0.00</span>
                                        </div>
                                        <div class="col-md-4">
                                            <strong><?= $localization->get('work_rate') ?>:</strong>
                                            <span id="display_rate">0.00 €/<?= $localization->get('hour') ?></span>
                                        </div>
                                        <div class="col-md-4">
                                            <strong><?= $localization->get('total') ?>:</strong>
                                            <span id="display_total" class="fw-bold">0.00 €</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="/work/overview" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i>
                                <?= $localization->get('cancel') ?>
                            </a>
                            <div>
                                <button type="button" class="btn btn-outline-primary me-2" onclick="startTimer()">
                                    <i class="bi bi-play-circle me-1"></i>
                                    <?= $localization->get('start_timer') ?>
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i>
                                    <?= $localization->get('save') ?>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Timer Modal -->
    <div class="modal fade" id="timerModal" tabindex="-1" aria-labelledby="timerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="timerModalLabel">
                        <i class="bi bi-stopwatch me-2"></i>
                        <?= $localization->get('work_timer') ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="display-4 mb-3" id="timerDisplay">00:00:00</div>
                    <div class="mb-3">
                        <button type="button" class="btn btn-success btn-lg me-2" onclick="pauseTimer()">
                            <i class="bi bi-pause-circle me-1"></i>
                            <?= $localization->get('pause') ?>
                        </button>
                        <button type="button" class="btn btn-danger btn-lg" onclick="stopTimer()">
                            <i class="bi bi-stop-circle me-1"></i>
                            <?= $localization->get('stop') ?>
                        </button>
                    </div>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <?= $localization->get('timer_info') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let timerInterval;
let timerSeconds = 0;
let isTimerRunning = false;

// Form validation and calculation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[data-validate="true"]');
    const hoursInput = document.getElementById('work_hours');
    const rateInput = document.getElementById('work_rate');
    const descriptionInput = document.getElementById('work_description');
    
    // Real-time calculation
    function calculateTotal() {
        const hours = parseFloat(hoursInput.value) || 0;
        const rate = parseFloat(rateInput.value) || 0;
        const total = hours * rate;
        
        document.getElementById('display_hours').textContent = hours.toFixed(2);
        document.getElementById('display_rate').textContent = rate.toFixed(2) + ' €/<?= $localization->get('hour') ?>';
        document.getElementById('display_total').textContent = total.toFixed(2) + ' €';
    }
    
    hoursInput.addEventListener('input', calculateTotal);
    rateInput.addEventListener('input', calculateTotal);
    
    // Form validation
    const requiredInputs = form.querySelectorAll('input[required], textarea[required]');
    
    requiredInputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                validateField(this);
            }
        });
    });
    
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        requiredInputs.forEach(input => {
            if (!validateField(input)) {
                isValid = false;
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            showAlert('<?= $localization->get('error_validation') ?>', 'danger');
        }
    });
    
    function validateField(field) {
        const value = field.value.trim();
        let isValid = true;
        
        field.classList.remove('is-valid', 'is-invalid');
        
        if (field.hasAttribute('required') && !value) {
            field.classList.add('is-invalid');
            isValid = false;
        }
        
        if (field.type === 'number' && value) {
            const numValue = parseFloat(value);
            if (isNaN(numValue) || numValue < 0) {
                field.classList.add('is-invalid');
                isValid = false;
            }
        }
        
        if (isValid && value) {
            field.classList.add('is-valid');
        }
        
        return isValid;
    }
    
    // Initial calculation
    calculateTotal();
});

// Timer functionality
function startTimer() {
    if (!isTimerRunning) {
        isTimerRunning = true;
        timerInterval = setInterval(updateTimer, 1000);
        
        // Show timer modal
        const timerModal = new bootstrap.Modal(document.getElementById('timerModal'));
        timerModal.show();
        
        // Update work description with timer start
        const descriptionInput = document.getElementById('work_description');
        const currentTime = new Date().toLocaleTimeString();
        if (descriptionInput.value) {
            descriptionInput.value += `\n[${currentTime}] Timer started`;
        } else {
            descriptionInput.value = `[${currentTime}] Timer started`;
        }
    }
}

function pauseTimer() {
    if (isTimerRunning) {
        clearInterval(timerInterval);
        isTimerRunning = false;
        
        // Update work description with pause
        const descriptionInput = document.getElementById('work_description');
        const currentTime = new Date().toLocaleTimeString();
        descriptionInput.value += `\n[${currentTime}] Timer paused`;
    }
}

function stopTimer() {
    if (isTimerRunning) {
        clearInterval(timerInterval);
        isTimerRunning = false;
        
        // Calculate hours from seconds
        const hours = (timerSeconds / 3600).toFixed(2);
        
        // Update form fields
        document.getElementById('work_hours').value = hours;
        document.getElementById('work_hours').dispatchEvent(new Event('input'));
        
        // Update work description with stop
        const descriptionInput = document.getElementById('work_description');
        const currentTime = new Date().toLocaleTimeString();
        descriptionInput.value += `\n[${currentTime}] Timer stopped - Total time: ${hours} hours`;
        
        // Close modal
        const timerModal = bootstrap.Modal.getInstance(document.getElementById('timerModal'));
        timerModal.hide();
        
        // Reset timer
        timerSeconds = 0;
        document.getElementById('timerDisplay').textContent = '00:00:00';
        
        showAlert('<?= $localization->get('timer_stopped') ?>', 'success');
    }
}

function updateTimer() {
    timerSeconds++;
    const hours = Math.floor(timerSeconds / 3600);
    const minutes = Math.floor((timerSeconds % 3600) / 60);
    const seconds = timerSeconds % 60;
    
    document.getElementById('timerDisplay').textContent = 
        `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
}

// Utility functions
function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.container-fluid');
    container.insertBefore(alertDiv, container.firstChild);
    
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Auto-save functionality
let autoSaveTimeout;
const form = document.querySelector('form[data-validate="true"]');
const inputs = form.querySelectorAll('input, textarea, select');

inputs.forEach(input => {
    input.addEventListener('input', function() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(() => {
            // Auto-save logic would go here
            console.log('Auto-saving work entry...');
        }, 3000);
    });
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + S to save
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        document.querySelector('form[data-validate="true"]').submit();
    }
    
    // Ctrl/Cmd + T to start timer
    if ((e.ctrlKey || e.metaKey) && e.key === 't') {
        e.preventDefault();
        startTimer();
    }
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?> 