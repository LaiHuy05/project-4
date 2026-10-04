<?php
final class AdminProductColorController
{
    public static function handle(string $admin, $id = null): void
    {
        switch ($admin) {
            case 'productColor-List':
                $list_product = ProductModel::all();
                $list_product_color = ProductModel::colors();

                include 'view/admin/product/product-color/list.php';
                break;

            case 'productColor-Add':
                $list_product = ProductModel::all();
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
                        ProductModel::addColor($name, $id_sp);
                        $thongBao = 'Thêm thành công';
                    }
                }

                include 'view/admin/product/product-color/add.php';
                break;

            case 'productColor-Update':
                $product_color = ProductModel::findColor($id);

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
                        ProductModel::updateColor($id, $name, $id_sp);
                        $pc_name = $name;
                        $thongBao = 'Sửa thành công';
                    }
                }

                include 'view/admin/product/product-color/update.php';
                break;

            case 'productColor-Delete':
                $productId = (int) ($_GET['idsp'] ?? 0);
                ProductModel::deleteColor($id);
                header('Location: ?act=admin&admin=productDetail&id=' . $productId);
                exit;
        }
    }
}
