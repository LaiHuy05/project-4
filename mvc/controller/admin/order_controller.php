<?php
/**
 * Admin order actions.
 * Database functions remain in mvc/query during this behavior-preserving refactor.
 */
final class AdminOrderController
{
    public static function handle(string $admin, $id = null): void
    {
        switch ($admin) {
            case 'orderList':
                $list_order = load_all_order();
                $list_orderdetail = load_all_orderdetail();
                $resultOrder = [];
                if (isset($_POST['btnSearch'], $_POST['inputSearch'])) {
                    $inputSearch = trim(strtolower($_POST['inputSearch']));
                    foreach ($list_order as $value) {
                        $dh_nameUser = strtolower($value['dh_nameUser']);
                        $dh_emailUser = strtolower($value['dh_emailUser']);
        
                        if (strpos($dh_nameUser, $inputSearch) !== false || strpos($dh_emailUser, $inputSearch) !== false) {
                            array_push($resultOrder, $value);
                        }
                    }
                }
                include 'view/admin/order/list.php';
                break;
            case 'orderDetail':
                $list_order = load_all_order();
                $list_orderdetail = load_all_orderdetail();
                $listAll_product = load_all_product();
                $list_account = load_all_account(); // Lấy dữ liệu từ CSDL
                $list_cart = load_all_cart();
                $list_cartDetail = load_all_cartDetail();
                $list_category = load_all_category();
                $list_order = load_all_order();
                $resultOrder = [];
        
                include 'view/admin/order/oderdetail.php';
                break;
            case 'orderDelete':
                $dh_id = isset($_GET['dhid']) ? intval($_GET['dhid']) : 0;
                delete_order($dh_id);
                header("Location: ?act=admin&admin=orderList");
                include 'view/admin/order/list.php';
                break;
            case 'orderUpdate':
                $dh_id = isset($_GET['dhid']) ? intval($_GET['dhid']) : 0;
                $load_one_order = load_one_order($dh_id);
        
                $mess = "";
        
                if (isset($_POST['btnPayUpdate'])) {
                    $statusPay = trim($_POST['statusPay']);
        
                    // Biến kiểm tra tính hợp lệ
                    $check_valid_order = true;
        
                    // cập nhật trạng thái đơn hàng
                    if ($check_valid_order) {
                        update_order($dh_id, $statusPay);
                        $mess = "Cập Nhật thành công!";
                        header("Refresh: 1.5; url='?act=admin&admin=orderList'");
                    }
                }
        
                include 'view/admin/order/update.php';
                break;
        }
    }
}
