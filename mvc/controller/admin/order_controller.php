<?php
final class AdminOrderController
{
    public static function handle(string $admin, $id = null): void
    {
        switch ($admin) {
            case 'orderList':
                $list_order = load_all_order();
                $resultOrder = [];

                if (isset($_POST['btnSearch'])) {
                    $keyword = trim((string) ($_POST['inputSearch'] ?? ''));

                    foreach ($list_order as $order) {
                        $matchesName = stripos($order['dh_nameUser'], $keyword) !== false;
                        $matchesEmail = stripos($order['dh_emailUser'], $keyword) !== false;

                        if ($matchesName || $matchesEmail) {
                            $resultOrder[] = $order;
                        }
                    }
                }

                include 'view/admin/order/list.php';
                break;

            case 'orderDetail':
                $list_order = load_all_order();
                $list_orderdetail = load_all_orderdetail();
                $listAll_product = load_all_product();
                $list_account = load_all_account();

                include 'view/admin/order/oderdetail.php';
                break;

            case 'orderUpdate':
                $orderId = (int) ($_GET['dhid'] ?? 0);
                $load_one_order = load_one_order($orderId);

                if (!$load_one_order) {
                    http_response_code(404);
                    exit('Đơn hàng không tồn tại.');
                }

                $mess = '';

                if (isset($_POST['btnPayUpdate'])) {
                    $statusPay = trim((string) ($_POST['statusPay'] ?? ''));

                    if ($statusPay !== '') {
                        update_order($orderId, $statusPay);
                        $load_one_order = load_one_order($orderId);
                        $mess = 'Cập nhật thành công!';
                        header("Refresh: 1.5; url='?act=admin&admin=orderList'");
                    }
                }

                include 'view/admin/order/update.php';
                break;

            case 'orderDelete':
                $orderId = (int) ($_GET['dhid'] ?? 0);
                delete_order($orderId);
                header('Location: ?act=admin&admin=orderList');
                exit;
        }
    }
}
