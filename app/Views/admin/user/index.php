<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Quản lý Tài khoản & Khách hàng</h3>
        <small class="text-muted">Tổng cộng <?= $users['total'] ?? 0 ?> tài khoản</small>
    </div>
</div>

<div class="card border-0 shadow-sm p-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light small">
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>Họ và tên</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Vai trò</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th class="text-end" style="width: 120px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users['data'])): ?>
                    <?php foreach ($users['data'] as $u): ?>
                        <tr>
                            <td><?= $u['id'] ?></td>
                            <td class="fw-bold text-dark"><?= e($u['name']) ?></td>
                            <td><?= e($u['email']) ?></td>
                            <td><?= e($u['phone'] ?? 'Chưa cập nhật') ?></td>
                            <td>
                                <span class="badge <?= ($u['role'] === 'admin') ? 'bg-danger' : 'bg-primary' ?>">
                                    <?= strtoupper(e($u['role'])) ?>
                                </span>
                            </td>
                            <td>
                                <?= ($u['status'] == 1) ? '<span class="badge bg-success">Hoạt động</span>' : '<span class="badge bg-secondary">Bị khóa</span>' ?>
                            </td>
                            <td class="small text-muted"><?= format_date($u['created_at']) ?></td>
                            <td class="text-end">
                                <?php if ($u['id'] != \App\Core\Auth::id()): ?>
                                    <form action="<?= base_url('admin/users/' . $u['id'] . '/toggle') ?>" method="POST" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn <?= ($u['status'] == 1) ? 'btn-outline-warning' : 'btn-outline-success' ?> btn-sm rounded-pill">
                                            <?= ($u['status'] == 1) ? 'Khóa' : 'Mở khóa' ?>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="badge bg-light text-muted">Tài khoản này</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Chưa có người dùng nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php \App\Core\View::partial('pagination', ['pagination' => $users]); ?>
</div>

