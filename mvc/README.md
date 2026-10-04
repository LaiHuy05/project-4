# FourSmart - PHP MVC

Ứng dụng bán điện thoại viết bằng PHP + MySQL. Điểm vào chính là `mvc/index.php`.

## Cấu trúc

```text
mvc/
  index.php                  Front controller
  core/
    ActionRouter.php         Điều hướng action đến controller
    Csrf.php                 Tạo và kiểm tra CSRF token
  middleware/
    require_admin.php        Kiểm tra quyền admin
  controller/
    admin/                   Chức năng quản trị
    client/                  Chức năng khách hàng
  query/                     Truy vấn MySQL qua PDO
  service/
    CheckoutService.php      Logic thanh toán và transaction
  view/
    admin/                   Giao diện quản trị
    client/                  Giao diện khách hàng
    assets/                  CSS và JavaScript
  upload/                    Ảnh sản phẩm
  migrations/                Thay đổi cấu trúc database
  tests/                     Smoke test (giữ lại)
```

## Luồng chạy

`index.php` -> router client/admin -> controller -> query/service -> view.

- Client route dùng tham số `?client=...`.
- Admin route dùng `?act=admin&admin=...`.
- Admin được kiểm tra quyền bởi `middleware/require_admin.php`.
- Thanh toán đi qua `CheckoutService` để tính lại tổng tiền từ database và ghi đơn hàng trong transaction.

## Database

Mặc định dùng MySQL database `duan1`. Có thể cấu hình bằng biến môi trường:

- `DB_HOST`
- `DB_NAME`
- `DB_USER`
- `DB_PASSWORD`

Trước khi dùng mật khẩu bcrypt, chạy migration:

```sql
mvc/migrations/2026-10-03_expand_password_column.sql
```

## Kiểm thử

Các file trong `mvc/tests/` được giữ lại:

```bash
php mvc/tests/security_smoke.php
php mvc/tests/checkout_smoke.php
php mvc/tests/revenue_smoke.php
```

Kiểm tra cú pháp toàn bộ PHP:

```bash
find mvc -type f -name '*.php' -print0 | xargs -0 -n1 php -l
```

GitHub Actions trong `.github/workflows/php-lint.yml` tự chạy lint và các smoke test.

## Ghi chú

Dự án không còn dùng JSON Server/Node.js, vì vậy các file `package.json`, `package-lock.json`
và các model class rỗng/không được gọi đã được loại bỏ để cấu trúc dễ đọc hơn.
