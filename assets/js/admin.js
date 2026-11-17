/**
 * Admin Panel interactions
 */
document.addEventListener('DOMContentLoaded', function () {
    // Confirm delete forms
    const deleteButtons = document.querySelectorAll('.btn-confirm-delete');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
            if (!confirm('Bạn có chắc chắn muốn thực hiện thao tác xóa này?')) {
                e.preventDefault();
            }
        });
    });
});

