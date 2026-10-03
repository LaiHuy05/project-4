<?php
/**
 * Admin product color actions.
 * Database functions remain in mvc/query during this behavior-preserving refactor.
 */
final class AdminProductColorController
{
    public static function handle(string $admin, $id = null): void
    {
        switch ($admin) {
            case 'productColor-List':
                $list_product = load_all_product();
                $list_product_color = load_all_product_color();
                $list_category = load_all_category();
                include 'product/product-color/list.php';
                break;
            case 'productColor-Add':
                $thongBao = "";
                $thongBaoLoiTen = '';
                $thongBaoLoiSP = '';
                $list_product = load_all_product();
                $list_product_color = load_all_product_color();
                $list_category = load_all_category();
                if (isset($_POST["submit"])) {
                    $name = trim($_POST['name']);
        
                    $id_sp = trim($_POST['id_sp']);
        
                    if ($name === '') {
                        $thongBaoLoiTen = 'Vui lòng nhập Tên!';
                    } elseif (strlen($name) < 5) {
                        $thongBaoLoiTen = 'Tên đăng nhập phải có ít nhất 5 ký tự.';
                    }
        
        
                    if ($id_sp === '' || $id_sp === '0') {
                        $thongBaoLoiSP = 'Vui lòng chọn danh mục!';
                    }
        
        
                    if (
                        empty($thongBaoLoiTen) &&  empty($thongBaoLoiSP)
                    ) {
                        insert_product_color($name, $id_sp);
                        $thongBao = "Thêm thành công";
                    }
                }
                include 'view/admin/product/product-color/add.php';
                break;
            case 'productColor-Update':
                $thongBao = "";
                $thongBaoLoiTen = '';
                $thongBaoLoiSP = '';
                $list_product = load_all_product();
                $list_product_color = load_all_product_color();
        
                $product_color = load_one_product_color($id);
                $list_category = load_all_category();
                $pc_id = $product_color['pc_id'];
                $pc_name = $product_color['pc_name'];
        
        
        
                // $name = load_one_product($id);
                if (isset($_POST["submit"])) {
                    $name = trim($_POST['name']);
                    $id_sp = $_GET["idsp"];
        
        
        
        
        
                    if (
                        empty($thongBaoLoiTen) &&  empty($thongBaoLoiSP)
                    ) {
                        update_product_color($id, $name, $id_sp);
        
                        $thongBao = "Sửa thành công";
                    }
                }
                include 'view/admin/product/product-color/update.php';
                break;
            case 'productColor-Delete':
        
        
        
                $id_sp = $_GET['idsp'];
                $pc_id = $_GET['id'];
        
                delete_product_color($id);
                header("Location: ?act=admin&admin=productDetail&id=$id_sp&idcolor=$pc_id");
        
                break;
        }
    }
}
