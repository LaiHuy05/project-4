<?php
final class ClientProfileController
{
    public static function handle(string $client, $iduser = null): void
    {
        $userId = $_SESSION['user_id'] ?? null;

        if ($userId === null || filter_var($userId, FILTER_VALIDATE_INT) === false) {
            header('Location: ?client=login');
            exit;
        }

        $userId = (int) $userId;

        if (isset($_GET['iduser']) && (string) $_GET['iduser'] !== (string) $userId) {
            http_response_code(403);
            exit('Không thể xem hồ sơ tài khoản khác.');
        }

        $account = load_one_account($userId);
        if (!$account) {
            http_response_code(401);
            exit('Tài khoản không tồn tại.');
        }

        $list_account = [$account];
        $list_category = load_all_category();

        switch ($client) {
            case 'profile':
                include 'view/client/profile/profile.php';
                break;

            case 'detailProfile':
                include 'view/client/profile/profile-detail.php';
                break;

            case 'payProfile':
            case 'payfinal':
                $listAll_product = load_all_product();
                $list_orderdetail = load_order_details_for_user($userId);
                $list_order = load_orders_for_user($userId);

                include $client === 'payProfile'
                    ? 'view/client/profile/profile-pay.php'
                    : 'view/client/profile/profile-payfinal.php';
                break;

            case 'finaldh':
                self::confirmReceived($userId);
                break;
        }
    }

    private static function confirmReceived(int $userId): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            http_response_code(405);
            header('Allow: POST');
            exit('Chỉ chấp nhận POST.');
        }

        if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
            http_response_code(403);
            exit('Phiên biểu mẫu không hợp lệ.');
        }

        $orderId = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        if (!$orderId) {
            http_response_code(400);
            exit('Mã đơn hàng không hợp lệ.');
        }

        $order = load_one_order($orderId);
        if (!$order || (int) $order['id_tk'] !== $userId) {
            http_response_code(403);
            exit('Đơn hàng không thuộc tài khoản.');
        }

        if ($order['dh_status'] !== 'Giao Hàng Thành Công') {
            http_response_code(409);
            exit('Đơn hàng chưa thể xác nhận đã nhận.');
        }

        update_order($orderId, 'Đã nhận hàng');
        header('Location: ?client=payfinal&iduser=' . $userId);
        exit;
    }
}
