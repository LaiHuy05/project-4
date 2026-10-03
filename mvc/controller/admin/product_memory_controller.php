<?php
/**
 * Admin product memory actions.
 * Database functions remain in mvc/query during this behavior-preserving refactor.
 */
final class AdminProductMemoryController
{
    public static function handle(string $admin, $id = null): void
    {
        switch ($admin) {
            case 'productMemory-List':
                $list_product = load_all_product();
                $list_product_memory = load_all_product_memory();
                $list_category = load_all_category();
                include 'view/admin/product/product-memory/list.php';
                break;
            case 'productMemory-Add':
                $thongBao = "";
                $thongBaoLoiTen = '';
                $thongBaoLoiSP = '';
                $list_product = load_all_product();
                $list_product_memory = load_all_product_memory();
                $list_category = load_all_category();
                if (isset($_POST["submit"])) {
                    $name = trim($_POST['name']);
        
                    $id_sp = trim($_POST['id_sp']);
        
                    if ($name === '') {
                        $thongBaoLoiTen = 'Vui lòng nhập Tên!';
                    } elseif (strlen($name) < 5) {
                        $thongBaoLoiTen = 'Tên đăng nhập phải có ít nhất 5 ký tự.';
                    }
        
        
                    if ($id_dm === '' || $id_dm === '0') {
                        $thongBaoLoiSP = 'Vui lòng chọn danh mục!';
                    }
        
        
                    if (
                        empty($thongBaoLoiTen) &&  empty($thongBaoLoiSP)
                    ) {
                        insert_product_memory($name, $id_sp);
                        $thongBao = "Thêm thành công";
                    }
                }
        
                include 'view/admin/product/product-memory/add.php';
                break;
            case 'productMemory-Update':
                $thongBao = "";
                $thongBaoLoiTen = '';
                $thongBaoLoiSP = '';
                $list_product = load_all_product();
                $list_product_memory = load_all_product_memory();
                $product = load_one_product_memory($id);
                $list_category = load_all_category();
                $pm_id = $product['pm_id'];
                $pm_name = $product['pm_name'];
        
                $id_sp = $product['id_sp'];
        
                // $name = load_one_product($id);
                if (isset($_POST["submit"])) {
                    $name = trim($_POST['name']);
        
                    $id_sp = $_GET["idsp"];
                    if (
                        empty($thongBaoLoiTen) &&  empty($thongBaoLoiSP)
                    ) {
                        update_product_memory($id, $name, $id_sp);
                        $thongBao = "Sửa thành công";
                    }
                }
                include 'view/admin/product/product-memory/update.php';
                break;
            case 'productMemory-Delete':
        
                $id_sp = $_GET['idsp'];
                $pm_id = $_GET['id'];
                delete_product_memory($id);
        
        
                header("Location: ?act=admin&admin=productDetail&id=$id_sp&idmemory=$pm_id");
                break;
        }
    }
}
