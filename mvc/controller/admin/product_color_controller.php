<?php
final class AdminProductColorController
{
    public static function handle(string $admin, $id = null): void
    {
        switch ($admin) {
            case 'productColor-List':
                $list_product = load_all_product();
                $list_product_color = load_all_product_color();

                include 'view/admin/product/product-color/list.php';
                break;

            case 'productColor-Add':
                $list_product = load_all_product();
                $thongBao = '';
                $thongBaoLoiTen = '';
                $thongBaoLoiSP = '';

                if (isset($_POST['submit'])) {
                    $name = trim((string) ($_POST['name'] ?? ''));
                    $id_sp = trim((string) ($_POST['id_sp'] ?? ''));

                    if ($name === '') {
                        $thongBaoLoiTen = 'Vui lòng nhập tên màu!';
                    }
                    if ($id_sp === '' || $id_sp === '0') {
                        $thongBaoLoiSP = 'Vui lòng chọn sản phẩm!';
                    }

                    if ($thongBaoLoiTen === '' && $thongBaoLoiSP === '') {
                        insert_product_color($name, $id_sp);
                        $thongBao = 'Thêm thành công';
                    }
                }

                include 'view/admin/product/product-color/add.php';
                break;

            case 'productColor-Update':
                $product_color = load_one_product_color($id);

                if (!$product_color) {
                    http_response_code(404);
                    exit('Màu sản phẩm không tồn tại.');
                }

                $pc_name = $product_color['pc_name'];
                $thongBao = '';
                $thongBaoLoiTen = '';

                if (isset($_POST['submit'])) {
                    $name = trim((string) ($_POST['name'] ?? ''));
                    $id_sp = (int) ($_GET['idsp'] ?? $product_color['id_sp']);

                    if ($name === '') {
                        $thongBaoLoiTen = 'Vui lòng nhập tên màu!';
                    } else {
                        update_product_color($id, $name, $id_sp);
                        $pc_name = $name;
                        $thongBao = 'Sửa thành công';
                    }
                }

                include 'view/admin/product/product-color/update.php';
                break;

            case 'productColor-Delete':
                $productId = (int) ($_GET['idsp'] ?? 0);
                delete_product_color($id);
                header('Location: ?act=admin&admin=productDetail&id=' . $productId);
                exit;
        }
    }
}
