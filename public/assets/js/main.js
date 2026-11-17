/**
 * Nova Shop — client interactions
 */
document.addEventListener('DOMContentLoaded', function () {
    // Auto dismiss flash messages after 5 seconds
    const flashAlerts = document.querySelectorAll('.pv-flash-container .alert');
    flashAlerts.forEach(alert => {
        setTimeout(() => {
            if (window.bootstrap && bootstrap.Alert) {
                bootstrap.Alert.getOrCreateInstance(alert).close();
            }
        }, 5000);
    });

    // Header elevation on scroll
    const header = document.getElementById('main-header');
    if (header) {
        const syncHeader = () => header.classList.toggle('is-scrolled', window.scrollY > 80);
        syncHeader();
        window.addEventListener('scroll', syncHeader, { passive: true });
    }

    // Close the mobile drawer after choosing a destination
    const drawer = document.getElementById('mobileDrawer');
    if (drawer && window.bootstrap) {
        drawer.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                const instance = bootstrap.Offcanvas.getInstance(drawer);
                if (instance) {
                    instance.hide();
                }
            });
        });
    }

    // Flash sale countdown is handled by effects.js (flip animation)

    // Quantity stepper
    document.querySelectorAll('[data-qty]').forEach(control => {
        const input = control.querySelector('.qty-input');
        if (!input) {
            return;
        }

        const max = parseInt(input.getAttribute('max'), 10) || 99;
        const setValue = value => {
            input.value = Math.min(max, Math.max(1, value || 1));
        };

        const minus = control.querySelector('[data-qty-minus]');
        const plus = control.querySelector('[data-qty-plus]');

        if (minus) {
            minus.addEventListener('click', () => setValue((parseInt(input.value, 10) || 1) - 1));
        }
        if (plus) {
            plus.addEventListener('click', () => setValue((parseInt(input.value, 10) || 1) + 1));
        }
    });

    // Sort dropdown keeps the current filters and submits immediately.
    document.querySelectorAll('[data-auto-submit]').forEach(control => {
        control.addEventListener('change', () => {
            if (control.form) {
                control.form.submit();
            }
        });
    });
});
