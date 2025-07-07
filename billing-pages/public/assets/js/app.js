/**
 * Billing Portal - Main JavaScript File
 */

// Global variables
let currentLocale = document.documentElement.lang || 'de';
let apiBaseUrl = '/api';

// Initialize Bootstrap components when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all dropdowns
    const dropdownElementList = document.querySelectorAll('.dropdown-toggle');
    dropdownElementList.forEach(dropdownToggleEl => {
        new bootstrap.Dropdown(dropdownToggleEl);
    });
    
    // Initialize all tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTriggerList.forEach(tooltipTriggerEl => {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Initialize all popovers
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
    popoverTriggerList.forEach(popoverTriggerEl => {
        new bootstrap.Popover(popoverTriggerEl);
    });
    
    console.log('Bootstrap components initialized');
    
    // Test dropdown functionality
    const testDropdown = document.querySelector('.dropdown-toggle');
    if (testDropdown) {
        console.log('Dropdown toggle found:', testDropdown);
        testDropdown.addEventListener('click', function(e) {
            console.log('Dropdown clicked');
        });
    } else {
        console.log('No dropdown toggle found');
    }
});

// Utility functions
const Utils = {
    /**
     * Format currency
     */
    formatCurrency: (amount, currency = 'EUR', locale = currentLocale) => {
        return new Intl.NumberFormat(locale === 'de' ? 'de-DE' : 'en-US', {
            style: 'currency',
            currency: currency
        }).format(amount);
    },

    /**
     * Format date
     */
    formatDate: (date, locale = currentLocale) => {
        const options = {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        };
        return new Intl.DateTimeFormat(locale === 'de' ? 'de-DE' : 'en-US', options).format(new Date(date));
    },

    /**
     * Show loading spinner
     */
    showLoading: (element) => {
        if (element) {
            element.innerHTML = '<div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>';
        }
    },

    /**
     * Hide loading spinner
     */
    hideLoading: (element, originalContent) => {
        if (element && originalContent) {
            element.innerHTML = originalContent;
        }
    },

    /**
     * Show toast notification
     */
    showToast: (message, type = 'info') => {
        const toastContainer = document.getElementById('toast-container') || createToastContainer();
        
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;
        
        toastContainer.appendChild(toast);
        
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        // Remove toast after it's hidden
        toast.addEventListener('hidden.bs.toast', () => {
            toast.remove();
        });
    },

    /**
     * Create toast container if it doesn't exist
     */
    createToastContainer: () => {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container position-fixed top-0 end-0 p-3';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
        return container;
    },

    /**
     * Make AJAX request
     */
    ajax: async (url, options = {}) => {
        const defaultOptions = {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        };

        const config = { ...defaultOptions, ...options };

        try {
            const response = await fetch(url, config);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return await response.json();
            }
            
            return await response.text();
        } catch (error) {
            console.error('AJAX request failed:', error);
            throw error;
        }
    },

    /**
     * Validate form
     */
    validateForm: (form) => {
        const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
        let isValid = true;
        
        inputs.forEach(input => {
            if (!input.value.trim()) {
                input.classList.add('is-invalid');
                isValid = false;
            } else {
                input.classList.remove('is-invalid');
            }
        });
        
        return isValid;
    },

    /**
     * Debounce function
     */
    debounce: (func, wait) => {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
};

// Form handling
const FormHandler = {
    /**
     * Initialize form handlers
     */
    init: () => {
        // Auto-save forms
        FormHandler.initAutoSave();
        
        // Form validation
        FormHandler.initValidation();
        
        // File upload preview
        FormHandler.initFileUpload();
    },

    /**
     * Initialize auto-save functionality
     */
    initAutoSave: () => {
        const forms = document.querySelectorAll('form[data-autosave]');
        
        forms.forEach(form => {
            const inputs = form.querySelectorAll('input, select, textarea');
            const saveUrl = form.dataset.autosave;
            
            const autoSave = Utils.debounce(async () => {
                if (Utils.validateForm(form)) {
                    const formData = new FormData(form);
                    
                    try {
                        await Utils.ajax(saveUrl, {
                            method: 'POST',
                            body: formData
                        });
                        
                        Utils.showToast('Form auto-saved', 'success');
                    } catch (error) {
                        Utils.showToast('Auto-save failed', 'danger');
                    }
                }
            }, 2000);
            
            inputs.forEach(input => {
                input.addEventListener('input', autoSave);
                input.addEventListener('change', autoSave);
            });
        });
    },

    /**
     * Initialize form validation
     */
    initValidation: () => {
        const forms = document.querySelectorAll('form[data-validate]');
        
        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                if (!Utils.validateForm(form)) {
                    e.preventDefault();
                    Utils.showToast('Please fill in all required fields', 'warning');
                }
            });
        });
    },

    /**
     * Initialize file upload preview
     */
    initFileUpload: () => {
        const fileInputs = document.querySelectorAll('input[type="file"]');
        
        fileInputs.forEach(input => {
            input.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    const preview = input.parentNode.querySelector('.file-preview');
                    if (preview) {
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                preview.innerHTML = `<img src="${e.target.result}" class="img-thumbnail" style="max-height: 100px;">`;
                            };
                            reader.readAsDataURL(file);
                        } else {
                            preview.innerHTML = `<div class="alert alert-info">${file.name} (${Utils.formatFileSize(file.size)})</div>`;
                        }
                    }
                }
            });
        });
    },

    /**
     * Format file size
     */
    formatFileSize: (bytes) => {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
};

// Table handling
const TableHandler = {
    /**
     * Initialize table handlers
     */
    init: () => {
        TableHandler.initSorting();
        TableHandler.initFiltering();
        TableHandler.initPagination();
    },

    /**
     * Initialize table sorting
     */
    initSorting: () => {
        const tables = document.querySelectorAll('table[data-sortable]');
        
        tables.forEach(table => {
            const headers = table.querySelectorAll('th[data-sort]');
            
            headers.forEach(header => {
                header.addEventListener('click', () => {
                    const column = header.dataset.sort;
                    const direction = header.dataset.direction === 'asc' ? 'desc' : 'asc';
                    
                    // Update all headers
                    headers.forEach(h => h.dataset.direction = '');
                    header.dataset.direction = direction;
                    
                    // Sort table
                    TableHandler.sortTable(table, column, direction);
                });
            });
        });
    },

    /**
     * Sort table
     */
    sortTable: (table, column, direction) => {
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        
        rows.sort((a, b) => {
            const aValue = a.querySelector(`td[data-${column}]`)?.dataset[column] || '';
            const bValue = b.querySelector(`td[data-${column}]`)?.dataset[column] || '';
            
            if (direction === 'asc') {
                return aValue.localeCompare(bValue);
            } else {
                return bValue.localeCompare(aValue);
            }
        });
        
        // Reorder rows
        rows.forEach(row => tbody.appendChild(row));
    },

    /**
     * Initialize table filtering
     */
    initFiltering: () => {
        const filterInputs = document.querySelectorAll('input[data-filter]');
        
        filterInputs.forEach(input => {
            input.addEventListener('input', Utils.debounce(() => {
                const filterValue = input.value.toLowerCase();
                const tableSelector = input.dataset.filter;
                const table = document.querySelector(tableSelector);
                
                if (table) {
                    const rows = table.querySelectorAll('tbody tr');
                    
                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(filterValue) ? '' : 'none';
                    });
                }
            }, 300));
        });
    },

    /**
     * Initialize pagination
     */
    initPagination: () => {
        const paginationLinks = document.querySelectorAll('.pagination .page-link');
        
        paginationLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const page = link.dataset.page;
                const url = new URL(window.location);
                url.searchParams.set('page', page);
                window.location.href = url.toString();
            });
        });
    }
};

// Chart handling
const ChartHandler = {
    /**
     * Initialize charts
     */
    init: () => {
        ChartHandler.initRevenueChart();
        ChartHandler.initBillingTypeChart();
    },

    /**
     * Initialize revenue chart
     */
    initRevenueChart: () => {
        const canvas = document.getElementById('revenueChart');
        if (!canvas) return;
        
        const ctx = canvas.getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Revenue',
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
                                return Utils.formatCurrency(value);
                            }
                        }
                    }
                }
            }
        });
    },

    /**
     * Initialize billing type chart
     */
    initBillingTypeChart: () => {
        const canvas = document.getElementById('billingTypeChart');
        if (!canvas) return;
        
        const ctx = canvas.getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Company', 'Work', 'Money'],
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
    }
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    // Initialize all handlers
    FormHandler.init();
    TableHandler.init();
    ChartHandler.init();
    
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Initialize popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
    
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
    
    // Confirm delete actions
    const deleteButtons = document.querySelectorAll('[data-confirm-delete]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            const message = button.dataset.confirmDelete || 'Are you sure you want to delete this item?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
});

// Export for use in other scripts
window.BillingPortal = {
    Utils,
    FormHandler,
    TableHandler,
    ChartHandler
}; 