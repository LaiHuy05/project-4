<?php
final class ClientCartController
{
    public static function handle(string $client, $iduser = null): void
    {
        $userId = $_SESSION['user_id'] ?? null;

        if ($client === 'cart') {
            $list_category = CategoryModel::all();
            $listAll_product = ProductModel::all();
            $list_account = [];
            $list_cart = [];
            $list_cartDetail = [];

            if ($userId !== null && is_numeric($userId)) {
                $userId = (int) $userId;

                if (isset($_GET['iduser']) && (string) $_GET['iduser'] !== (string) $userId) {
                    header('Location: ?client=cart&iduser=' . $userId);
                    exit;
                }

                $account = AccountModel::find($userId);
                if ($account) {
                    $list_account[] = $account;
                }

                $cart = CartModel::findForUser($userId);
                if (!$cart) {
                    CartModel::create($userId);
                    $cart = CartModel::findForUser($userId);
                }

                if ($cart) {
                    $list_cart[] = $cart;
                    $list_cartDetail = CartModel::detailsForCart($cart['gh_id']);
                }
            }

            include 'view/client/cart.php';
            return;
        }

        if ($userId === null || !is_numeric($userId)) {
            http_response_code(401);
            exit('Vui lòng đăng nhập.');
        }

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            http_response_code(405);
            header('Allow: POST');
            exit('Thao tác giỏ hàng phải dùng POST.');
        }

        if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
            http_response_code(403);
            exit('Phiên biểu mẫu không hợp lệ.');
        }

        $userId = (int) $userId;
        $cartId = filter_var($_POST['idgh'] ?? null, FILTER_VALIDATE_INT);

        if ($client === 'cartdetail' && (!$cartId || $cartId < 1)) {
            $cartId = CartModel::findIdForUser($userId);

            if ($cartId === null) {
                CartModel::create($userId);
                $cartId = CartModel::findIdForUser($userId);
            }
        }

        if (!$cartId || !CartModel::belongsToUser($cartId, $userId)) {
            http_response_code(403);
            exit('Giỏ hàng không thuộc tài khoản.');
        }

        switch ($client) {
            case 'cartdetail':
                $productId = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
                $memory = trim((string) ($_POST['option'] ?? ''));
                $color = trim((string) ($_POST['colorOption'] ?? ''));

                if (
                    !$productId
                    || !ProductModel::find($productId)
                    || strlen($memory) > 100
                    || strlen($color) > 100
                    || $memory !== strip_tags($memory)
                    || $color !== strip_tags($color)
                ) {
                    http_response_code(400);
                    exit('Sản phẩm hoặc tùy chọn không hợp lệ.');
                }

                CartModel::addItem($cartId, $productId, $memory, $color);
                break;

            case 'Cartdetailadd':
            case 'Cartdetailoss':
            case 'cartdelete':
                $detailId = filter_var($_POST['idcd'] ?? null, FILTER_VALIDATE_INT);

                if (!$detailId || !CartModel::detailBelongsToUser($detailId, $userId)) {
                    http_response_code(403);
                    exit('Sản phẩm không thuộc giỏ hàng.');
                }

                if ($client === 'Cartdetailadd') {
                    CartModel::increaseItem($detailId);
                } elseif ($client === 'Cartdetailoss') {
                    CartModel::decreaseItem($detailId);
                } else {
                    CartModel::deleteItem($detailId);
                }
                break;

            default:
                http_response_code(404);
                exit('Thao tác không tồn tại.');
        }

        header('Location: ?client=cart&iduser=' . $userId);
        exit;
    }
}
