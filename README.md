# Nova Shop - Dự án Thương mại Điện tử (PHP MVC OOP)

> Dự án website thương mại điện tử chuyên cung cấp thiết bị công nghệ, laptop, linh kiện máy tính lấy cảm hứng từ Nova Shop.

Chi tiết tài liệu hướng dẫn cài đặt, kiến trúc hệ thống và phân công nhiệm vụ:
- [Tài liệu dự án (docs/README.md)](docs/README.md)
- [Thiết kế Cơ sở dữ liệu (docs/ERD.md)](docs/ERD.md)
- [Use Cases (docs/use-cases.md)](docs/use-cases.md)
- [Phân công công việc (docs/phan-cong.md)](docs/phan-cong.md)
- [Tài khoản Demo (docs/demo-accounts.md)](docs/demo-accounts.md)
- [Kịch bản kiểm thử (tests/TESTCASES.md)](tests/TESTCASES.md)

---
## Hướng dẫn chạy nhanh:
1. Tạo database MySQL: `nova_shop`
2. Import `database/schema.sql` và `database/seed.sql`
3. Cấu hình file `.env` nếu cần
4. Chạy web server:
   ```bash
   php -S localhost:8000 -t public
   ```
5. Mở trình duyệt: `http://localhost:8000`

