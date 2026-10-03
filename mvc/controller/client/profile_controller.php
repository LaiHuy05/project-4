<?php
/** All profile and order-acknowledgement operations are session-scoped. */
final class ClientProfileController
{
    public static function handle(string $client, $iduser = null): void
    {
        $uid = $_SESSION['user_id'] ?? null;
        if ($uid === null || filter_var($uid,FILTER_VALIDATE_INT) === false) {
            header('Location: ?client=login'); exit;
        }
        $uid = (int)$uid;
        if (isset($_GET['iduser']) && (string)$_GET['iduser'] !== (string)$uid) {
            http_response_code(403); exit('Không thể xem hồ sơ tài khoản khác.');
        }
        $account = load_one_account($uid);
        if (!$account) { http_response_code(401); exit('Tài khoản không tồn tại.'); }
        $list_account = [$account];
        $list_category = load_all_category();
        $list_cart = load_all_cart();
        switch ($client) {
            case 'profile':
                include 'view/client/profile/profile.php';
                return;
            case 'detailProfile':
                $listAll_product=load_all_product();
                include 'view/client/profile/profile-detail.php';
                return;
            case 'payProfile':
            case 'payfinal':
                $listAll_product = load_all_product();
                $list_orderdetail = load_order_details_for_user($uid);
                $list_cartDetail = load_all_cartDetail();
                $list_order = load_orders_for_user($uid);
                include $client === 'payProfile'
                    ? 'view/client/profile/profile-pay.php'
                    : 'view/client/profile/profile-payfinal.php';
                return;
            case 'finaldh':
                if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
                    http_response_code(405); header('Allow: POST'); exit('Chỉ chấp nhận POST.');
                }
                if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
                    http_response_code(403); exit('Phiên biểu mẫu không hợp lệ.');
                }
                $orderId = filter_var($_POST['id'] ?? null,FILTER_VALIDATE_INT);
                if (!$orderId || $orderId < 1) {
                    http_response_code(400); exit('Mã đơn hàng không hợp lệ.');
                }
                $order = load_one_order($orderId);
                if (!$order || (int)$order['id_tk'] !== $uid) {
                    http_response_code(403); exit('Đơn hàng không thuộc tài khoản.');
                }
                if ($order['dh_status'] !== 'Giao Hàng Thành Công') {
                    http_response_code(409); exit('Đơn hàng chưa thể xác nhận đã nhận.');
                }
                update_order($orderId,'Đã nhận hàng');
                header('Location: ?client=payfinal&iduser='.$uid); exit;
        }
    }
}
