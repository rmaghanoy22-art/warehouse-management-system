// WMS App JS - Global utilities
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss flash messages after 5 seconds
    document.querySelectorAll('[data-auto-dismiss]').forEach(el => {
        setTimeout(() => el.remove(), 5000);
    });

    // Delegated submit listener for all confirm/prompt actions across WMS
    document.addEventListener('submit', function(e) {
        const form = e.target;
        if (form.dataset.wmsConfirmed === 'true') {
            return;
        }

        const dataConfirm = form.dataset.confirm;
        const rejectTitle = form.dataset.promptReject;
        const rejectPlaceholder = form.dataset.placeholder;

        if (dataConfirm) {
            e.preventDefault();
            wmsConfirm('Are you sure?', dataConfirm, {
                confirmText: 'Yes, proceed',
                cancelText: 'Cancel'
            }).then(confirmed => {
                if (confirmed) {
                    form.dataset.wmsConfirmed = 'true';
                    form.submit();
                }
            });
            return;
        }

        if (rejectTitle) {
            e.preventDefault();
            wmsPrompt(rejectTitle, rejectPlaceholder || 'Enter reason...', {
                confirmText: 'Reject Request',
                cancelText: 'Cancel'
            }).then(reason => {
                if (reason && reason.trim()) {
                    let input = form.querySelector('[name="rejection_reason"]');
                    if (!input) {
                        input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'rejection_reason';
                        form.appendChild(input);
                    }
                    input.value = reason;
                    form.dataset.wmsConfirmed = 'true';
                    form.submit();
                }
            });
            return;
        }
    });

    // Table row hover effect
    document.querySelectorAll('.data-table tbody tr').forEach(row => {
        row.style.cursor = row.hasAttribute('onclick') ? 'pointer' : 'default';
    });

    // Initialize all password toggle buttons
    initPasswordToggles();

    // Staggered animation for cards
    document.querySelectorAll('.stagger-in').forEach((el, i) => {
        el.style.animationDelay = `${i * 0.08}s`;
    });
});

// Global WMS Custom Dialog Promise-based Helpers
window.wmsConfirm = function(title, message, options = {}) {
    return new Promise((resolve) => {
        window.dispatchEvent(new CustomEvent('wms-show-modal', {
            detail: {
                type: 'confirm',
                title: title,
                message: message,
                confirmText: options.confirmText || 'Confirm',
                cancelText: options.cancelText || 'Cancel',
                resolve: resolve
            }
        }));
    });
};

window.wmsPrompt = function(title, placeholder, options = {}) {
    return new Promise((resolve) => {
        window.dispatchEvent(new CustomEvent('wms-show-modal', {
            detail: {
                type: 'prompt',
                title: title,
                placeholder: placeholder,
                confirmText: options.confirmText || 'Submit',
                cancelText: options.cancelText || 'Cancel',
                resolve: resolve
            }
        }));
    });
};

/**
 * Toggle password visibility for a given input field.
 * @param {string} inputId - The ID of the password input field.
 */
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const btn = input?.parentElement?.querySelector('.password-toggle');
    if (!input) return;

    const isPassword = input.type === 'password';
    input.type = isPassword ? 'text' : 'password';

    if (btn) {
        const eyeOpen = btn.querySelector('.eye-open');
        const eyeClosed = btn.querySelector('.eye-closed');
        if (eyeOpen && eyeClosed) {
            eyeOpen.classList.toggle('hidden', !isPassword);
            eyeClosed.classList.toggle('hidden', isPassword);
        }
    }
}

/**
 * Initialize all password toggle buttons automatically.
 */
function initPasswordToggles() {
    document.querySelectorAll('.password-wrapper').forEach(wrapper => {
        const input = wrapper.querySelector('input[type="password"]');
        const btn = wrapper.querySelector('.password-toggle');
        if (input && btn) {
            btn.addEventListener('click', () => togglePassword(input.id));
        }
    });
}
