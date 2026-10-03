<?php
/**
 * Thin admin entry point. Feature actions live in one controller per domain.
 * Existing query-string URLs continue to work.
 */
$root = dirname(__DIR__, 2);
require_once $root . '/core/ActionRouter.php';
require_once $root . '/query/danh-muc.php';
require_once $root . '/query/san-pham.php';
require_once $root . '/query/tai-khoan.php';
require_once $root . '/query/binh-luan.php';
require_once $root . '/query/don-hang.php';
require_once $root . '/query/pdo.php';
require_once $root . '/query/thong-ke.php';
require_once $root . '/query/tong-doanh-thu.php';
require_once $root . '/query/gio-hang.php';

final class AdminRouter
{
    public static function run(): void
    {
        $action = $_GET['admin'] ?? 'home';
        if (!is_string($action)) {
            http_response_code(400);
            return;
        }
        $id = isset($_GET['id']) && is_scalar($_GET['id']) ? $_GET['id'] : '';

        $routes = [
            'home' => [AdminHomeController::class, __DIR__ . '/home_controller.php'],
            'categoryList' => [AdminCategoryController::class, __DIR__ . '/category_controller.php'],
            'categoryAdd' => [AdminCategoryController::class, __DIR__ . '/category_controller.php'],
            'categoryDelete' => [AdminCategoryController::class, __DIR__ . '/category_controller.php'],
            'categoryUpdate' => [AdminCategoryController::class, __DIR__ . '/category_controller.php'],
            'productList' => [AdminProductController::class, __DIR__ . '/product_controller.php'],
            'productAdd' => [AdminProductController::class, __DIR__ . '/product_controller.php'],
            'productUpdate' => [AdminProductController::class, __DIR__ . '/product_controller.php'],
            'productDelete' => [AdminProductController::class, __DIR__ . '/product_controller.php'],
            'productDetail' => [AdminProductController::class, __DIR__ . '/product_controller.php'],
            'productColor-List' => [AdminProductColorController::class, __DIR__ . '/product_color_controller.php'],
            'productColor-Add' => [AdminProductColorController::class, __DIR__ . '/product_color_controller.php'],
            'productColor-Update' => [AdminProductColorController::class, __DIR__ . '/product_color_controller.php'],
            'productColor-Delete' => [AdminProductColorController::class, __DIR__ . '/product_color_controller.php'],
            'productMemory-List' => [AdminProductMemoryController::class, __DIR__ . '/product_memory_controller.php'],
            'productMemory-Add' => [AdminProductMemoryController::class, __DIR__ . '/product_memory_controller.php'],
            'productMemory-Update' => [AdminProductMemoryController::class, __DIR__ . '/product_memory_controller.php'],
            'productMemory-Delete' => [AdminProductMemoryController::class, __DIR__ . '/product_memory_controller.php'],
            'accountList' => [AdminAccountController::class, __DIR__ . '/account_controller.php'],
            'accountAdd' => [AdminAccountController::class, __DIR__ . '/account_controller.php'],
            'accountUpdate' => [AdminAccountController::class, __DIR__ . '/account_controller.php'],
            'accountDelete' => [AdminAccountController::class, __DIR__ . '/account_controller.php'],
            'commentList' => [AdminCommentController::class, __DIR__ . '/comment_controller.php'],
            'commentDelete' => [AdminCommentController::class, __DIR__ . '/comment_controller.php'],
            'orderList' => [AdminOrderController::class, __DIR__ . '/order_controller.php'],
            'orderDetail' => [AdminOrderController::class, __DIR__ . '/order_controller.php'],
            'orderDelete' => [AdminOrderController::class, __DIR__ . '/order_controller.php'],
            'orderUpdate' => [AdminOrderController::class, __DIR__ . '/order_controller.php'],
        ];

        ActionRouter::dispatch($action, $routes, [$action, $id]);
    }
}

AdminRouter::run();
