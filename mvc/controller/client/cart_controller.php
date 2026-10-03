<?php
/** Cart reads and mutations are always scoped to the authenticated user. */
final class ClientCartController
{
    public static function handle(string $client, $iduser = null): void
    {
        $userId = $_SESSION['user_id'] ?? null;
        if ($client === 'cart') {
            if ($userId !== null && (!isset($_GET['iduser']) || (string)$_GET['iduser'] !== (string)$userId)) {
                header('Location: ?client=cart&iduser='.(int)$userId); exit;
            }
            $list_category = load_all_category();
            $listAll_product = load_all_product();
            $list_account = $userId !== null ? array_filter([load_one_account((int)$userId)]) : [];
            $list_cart = $userId !== null ? load_all_cart() : [];
            $list_cartDetail = $userId !== null ? load_all_cartDetail() : [];
            include 'view/client/cart.php';
            return;
        }

        if (!isset($userId) || !is_numeric($userId)) {
            http_response_code(401); exit('Vui lòng đăng nhập.');
        }
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            http_response_code(405); header('Allow: POST'); exit('Thao tác giỏ hàng phải dùng POST.');
        }
        if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
            http_response_code(403); exit('Phiên biểu mẫu không hợp lệ.');
        }
        $userId = (int)$userId;
        $cartId = filter_var($_POST['idgh'] ?? null, FILTER_VALIDATE_INT);
        if ($client === 'cartdetail' && (!$cartId || $cartId < 1)) {
            $cartId = find_cart_id_for_user($userId);
            if ($cartId === null) {
                insert_cart($userId);
                $cartId = find_cart_id_for_user($userId);
            }
        }
        if (!$cartId || !cart_belongs_to_user($cartId, $userId)) {
            http_response_code(403); exit('Giỏ hàng không thuộc tài khoản.');
        }
        switch ($client) {
            case 'cartdetail':
                $productId = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
                $memory = trim((string)($_POST['option'] ?? ''));
                $color = trim((string)($_POST['colorOption'] ?? ''));
                if (!$productId || $productId < 1 || !load_one_product($productId)
                    || strlen($memory) > 100 || strlen($color) > 100
                    || $memory !== strip_tags($memory) || $color !== strip_tags($color)) {
                    http_response_code(400); exit('Sản phẩm hoặc tùy chọn không hợp lệ.');
                }
                insert_cartDetail($cartId, $productId, $memory, $color);
                break;
            case 'Cartdetailadd':
            case 'Cartdetailoss':
            case 'cartdelete':
                $detailId = filter_var($_POST['idcd'] ?? null, FILTER_VALIDATE_INT);
                if (!$detailId || !cartdetail_belongs_to_user($detailId, $userId)) {
                    http_response_code(403); exit('Sản phẩm không thuộc giỏ hàng.');
                }
                if ($client === 'Cartdetailadd') addOneCartdetail($detailId);
                elseif ($client === 'Cartdetailoss') lossOneCartdetail($detailId);
                else delete_cartdetail($detailId);
                break;
            default:
                http_response_code(404); exit('Thao tác không tồn tại.');
        }
        header('Location: ?client=cart&iduser='.$userId); exit;
    }
}
