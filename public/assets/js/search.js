/**
 * Search helpers
 */
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.header-search input[name="q"]').forEach(input => {
        input.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                input.value = '';
            }
        });
    });
});
