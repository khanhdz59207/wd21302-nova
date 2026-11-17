<footer class="site-footer">
    <div class="container-site">
        <div class="footer-grid">
            <div class="footer-col">
                <a href="<?= base_url() ?>" class="footer-logo" aria-label="Nova Shop - Trang chủ">
                    <img src="<?= asset('img/logo.svg') ?>" alt="Nova Shop">
                    <strong>NOVA<span>SHOP</span></strong>
                </a>
                <p class="footer-desc">
                    Nova Shop — Hệ thống bán lẻ công nghệ: laptop, PC, linh kiện, màn hình
                    và thiết bị chính hãng. Cam kết giá tốt, bảo hành tận tâm.
                </p>
                <p class="footer-contact">
                    <i class="bi bi-telephone" aria-hidden="true"></i>
                    Hotline: <strong><a href="tel:18006867">1800 6867</a></strong>
                </p>
                <p class="footer-contact">
                    <i class="bi bi-envelope" aria-hidden="true"></i>
                    <a href="mailto:hotro@novashop.vn">hotro@novashop.vn</a>
                </p>
                <p class="footer-contact">
                    <i class="bi bi-geo-alt" aria-hidden="true"></i>
                    Tầng 11, Toà nhà FPT, Phạm Văn Bạch, Cầu Giấy, Hà Nội
                </p>
                <div class="d-flex gap-2 mt-2">
                    <a href="#" class="social-btn" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-btn" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
                    <a href="#" class="social-btn" aria-label="Zalo"><i class="bi bi-chat-dots"></i></a>
                </div>
            </div>

            <div class="footer-col">
                <h3 class="footer-heading">Chính sách</h3>
                <ul class="footer-links">
                    <li><a href="<?= base_url('products') ?>">Chính sách bảo hành</a></li>
                    <li><a href="<?= base_url('products') ?>">Chính sách đổi trả</a></li>
                    <li><a href="<?= base_url('products') ?>">Chính sách bảo mật</a></li>
                    <li><a href="<?= base_url('products') ?>">Điều khoản dịch vụ</a></li>
                    <li><a href="<?= base_url('products') ?>">Hướng dẫn mua hàng</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h3 class="footer-heading">Hỗ trợ</h3>
                <ul class="footer-links">
                    <li><a href="<?= base_url('products') ?>">Hệ thống cửa hàng</a></li>
                    <li><a href="<?= base_url('orders') ?>">Tra cứu đơn hàng</a></li>
                    <li><a href="<?= base_url('cart') ?>">Giỏ hàng</a></li>
                    <li><a href="<?= base_url('auth/login') ?>">Tài khoản</a></li>
                    <li><a href="tel:18006867">Liên hệ tư vấn</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h3 class="footer-heading">Phương thức thanh toán</h3>
                <div class="payment-icons">
                    <span class="payment-badge">VISA</span>
                    <span class="payment-badge">Mastercard</span>
                    <span class="payment-badge">MoMo</span>
                    <span class="payment-badge">ZaloPay</span>
                    <span class="payment-badge">VNPAY</span>
                    <span class="payment-badge">COD</span>
                    <span class="payment-badge">Chuyển khoản</span>
                </div>
                <h3 class="footer-heading">Vận chuyển bởi</h3>
                <div class="payment-icons">
                    <span class="payment-badge">GHN</span>
                    <span class="payment-badge">GHTK</span>
                    <span class="payment-badge">J&amp;T</span>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>© <?= date('Y') ?> Nova Shop. Tất cả quyền được bảo lưu.</p>
            <p>Dự án môn học PHP MVC OOP — FPT Polytechnic</p>
        </div>
    </div>
</footer>

<a href="tel:18006867" class="chat-fab" aria-label="Tư vấn trực tuyến" title="Tư vấn trực tuyến 1800 6867">
    <i class="bi bi-chat-dots-fill" aria-hidden="true"></i>
</a>
