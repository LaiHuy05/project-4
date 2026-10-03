# PHP MVC storefront

The application entry point is `mvc/index.php`. Route names and query-string
links are unchanged: `?act=admin&admin=productList` and
`?client=cart` still go through the same front controller.

## Layout

```text
mvc/
  index.php                 Single front controller
  core/ActionRouter.php     Whitelisted action dispatcher
  controller/
    admin/
      admin_controller.php  Entry and route definitions
      home_controller.php
      category_controller.php
      product_controller.php
      product_color_controller.php
      product_memory_controller.php
      account_controller.php
      comment_controller.php
      order_controller.php
    client/
      client_controller.php Entry and route definitions
      home_controller.php
      auth_controller.php
      product_controller.php
      cart_controller.php
      checkout_controller.php
      single_checkout_controller.php
      profile_controller.php
      comment_controller.php
  model/                    Existing domain class stubs
  query/                    Existing SQL and PDO functions (legacy data access)
  view/admin/               Existing admin templates
  view/client/              Existing storefront templates
  view/assets/              Existing CSS and JavaScript
  upload/                   Existing product images
```

## Refactor details

- All 28 admin actions and 19 storefront actions were assigned to feature
  controllers, without rewriting SQL or changing template variable names.
- Route lookup accepts only predefined actions; user input cannot form a PHP
  include path. Missing routes return HTTP 404.
- The duplicate `orderUpdate` switch label was removed, and the storefront
  `profile` action now uses the existing view path.
- The standalone `mvc/api/` JSON endpoints were intentionally **deleted**.
  Any independent integrations still calling `/mvc/api/*.php` need to be
  migrated or retired; they are **not** silently recreated here.
- SQL helpers remain in `query/`, rather than moving them and breaking
  existing relative includes. They can be migrated to proper repository classes
  separately once there are database-backed integration tests.

## Local verification

PHP 7.4+ and MySQL with the existing `duan1` database are required.
Point the web server document root at `mvc/` or visit its `index.php`.
Update the database connection in `query/pdo.php` for your environment.

Syntax check:
```bash
find mvc -type f -name '*.php' -print0 | xargs -0 -n1 php -l
```

Smoke-test these routes against a disposable database:

1. Storefront home, search, product detail and category.
2. Login, registration, cart add/update/remove and both checkout flows.
3. Profile, order status updates and comments.
4. Admin dashboard and category/product/color/memory/account/comment/order CRUD.
5. Verify unknown actions return 404 and that the old `api/` paths are gone.

**Security follow-up:** The existing application still needs an access-control
and CSRF review for admin and state-changing GET routes, parameter validation,
upload handling and password storage. Moving controllers does not itself secure
those actions; do not expose it publicly before reviewing them.
