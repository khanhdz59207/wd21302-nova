/**
 * Cart handling JS
 */
function updateCartBadge(count) {
    const badge = document.getElementById('cart-badge-count');
    if (!badge) {
        return;
    }

    badge.innerText = count;
    badge.classList.remove('badge-count-animate');
    void badge.offsetWidth;
    badge.classList.add('badge-count-animate');
}

function showCartToast(message) {
    if (window.NovaToast && typeof window.NovaToast.show === 'function') {
        window.NovaToast.show(message, 'cart', 1800);
        return;
    }

    const toast = document.createElement('div');
    toast.className = 'cart-toast';
    toast.setAttribute('role', 'status');

    const icon = document.createElement('i');
    icon.className = 'bi bi-check-circle-fill';

    const text = document.createElement('span');
    text.textContent = message;

    toast.appendChild(icon);
    toast.appendChild(text);
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.3s';
    }, 1800);
    setTimeout(() => toast.remove(), 2200);
}

document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('form.js-ajax-cart');
    if (!forms.length || !window.fetch) {
        return;
    }

    forms.forEach(form => {
        form.addEventListener('submit', async function (event) {
            event.preventDefault();

            const button = form.querySelector('button[type="submit"]');
            if (button) {
                button.disabled = true;
            }

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await response.json();

                if (data && data.success) {
                    updateCartBadge(data.totalCount);
                    showCartToast(data.message || 'Đã thêm vào giỏ hàng');
                } else {
                    form.submit();
                }
            } catch (error) {
                form.submit();
            } finally {
                if (button) {
                    button.disabled = false;
                }
            }
        });
    });
});
