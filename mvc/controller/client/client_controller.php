<?php
/**
 * Thin client entry point. Feature actions live in one controller per domain.
 * Existing query-string URLs continue to work.
 */
$root = dirname(__DIR__, 2);
require_once $root . '/core/ActionRouter.php';
require_once $root . '/query/san-pham.php';
require_once $root . '/query/gio-hang.php';
require_once $root . '/query/danh-muc.php';
require_once $root . '/query/tai-khoan.php';
require_once $root . '/query/don-hang.php';
require_once $root . '/query/binh-luan.php';
require_once $root . '/core/Csrf.php';

final class ClientRouter
{
    public static function run(): void
    {
        $action = $_GET['client'] ?? 'home';
        if (!is_string($action)) {
            http_response_code(400);
            return;
        }
        $iduser = $_SESSION['user_id'] ?? (
            isset($_GET['iduser']) && is_scalar($_GET['iduser']) ? $_GET['iduser'] : null
        );

        $routes = [
            'home' => [ClientHomeController::class, __DIR__ . '/home_controller.php'],
            'login' => [ClientAuthController::class, __DIR__ . '/auth_controller.php'],
            'register' => [ClientAuthController::class, __DIR__ . '/auth_controller.php'],
            'detail' => [ClientProductController::class, __DIR__ . '/product_controller.php'],
            'categoryShow' => [ClientProductController::class, __DIR__ . '/product_controller.php'],
            'search' => [ClientProductController::class, __DIR__ . '/product_controller.php'],
            'cart' => [ClientCartController::class, __DIR__ . '/cart_controller.php'],
            'cartdetail' => [ClientCartController::class, __DIR__ . '/cart_controller.php'],
            'Cartdetailadd' => [ClientCartController::class, __DIR__ . '/cart_controller.php'],
            'Cartdetailoss' => [ClientCartController::class, __DIR__ . '/cart_controller.php'],
            'cartdelete' => [ClientCartController::class, __DIR__ . '/cart_controller.php'],
            'pay' => [ClientCheckoutController::class, __DIR__ . '/checkout_controller.php'],
            'payOne' => [ClientSingleCheckoutController::class, __DIR__ . '/single_checkout_controller.php'],
            'profile' => [ClientProfileController::class, __DIR__ . '/profile_controller.php'],
            'detailProfile' => [ClientProfileController::class, __DIR__ . '/profile_controller.php'],
            'payProfile' => [ClientProfileController::class, __DIR__ . '/profile_controller.php'],
            'payfinal' => [ClientProfileController::class, __DIR__ . '/profile_controller.php'],
            'finaldh' => [ClientProfileController::class, __DIR__ . '/profile_controller.php'],
            'addComment' => [ClientCommentController::class, __DIR__ . '/comment_controller.php'],
        ];

        ActionRouter::dispatch($action, $routes, [$action, $iduser]);
    }
}

ClientRouter::run();
