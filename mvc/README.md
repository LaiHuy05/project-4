# FourSmart - PHP MVC

Ứng dụng bán điện thoại viết bằng PHP + MySQL.

## Kiến trúc

```text
Request
  -> index.php
  -> Router
  -> Controller
  -> Model / Service
  -> Database (PDO)
  -> View
```

## Cấu trúc chính

```text
mvc/
  index.php
  core/
    ActionRouter.php
    Csrf.php
    Database.php
    bootstrap.php
  middleware/
    require_admin.php
  controller/
    admin/
    client/
  model/
    AccountModel.php
    CategoryModel.php
    ProductModel.php
    CartModel.php
    OrderModel.php
    CommentModel.php
    DashboardModel.php
  service/
    CheckoutService.php
  view/
    admin/
    client/
    assets/
  upload/
  migrations/
  tests/
```

## Vai trò từng tầng

- **Controller**: nhận request, kiểm tra dữ liệu, gọi Model/Service và chọn View.
- **Model**: chứa toàn bộ truy vấn và thao tác dữ liệu theo từng nghiệp vụ.
- **Service**: chứa logic nghiệp vụ phức tạp; hiện tại thanh toán nằm trong `CheckoutService`.
- **Database**: `core/Database.php` quản lý PDO, prepared statement và transaction.
- **View**: chỉ hiển thị dữ liệu; không truy vấn database trực tiếp.

Thư mục `query/` cũ đã được thay bằng các Model thật để đúng luồng MVC hơn.

## Database

Mặc định dùng MySQL database `duan1`. Có thể cấu hình bằng:

- `DB_HOST`
- `DB_NAME`
- `DB_USER`
- `DB_PASSWORD`

Trước khi dùng bcrypt, chạy:

```text
mvc/migrations/2026-10-03_expand_password_column.sql
```

## Test

Giữ nguyên các smoke test:

```bash
php mvc/tests/security_smoke.php
php mvc/tests/checkout_smoke.php
php mvc/tests/revenue_smoke.php
```

Kiểm tra cú pháp PHP:

```bash
find mvc -type f -name '*.php' -print0 | xargs -0 -n1 php -l
```

GitHub Actions trong `.github/workflows/php-lint.yml` tự chạy lint và các smoke test.
