<?php
final class AdminProductMemoryController
{
    public static function handle(string $admin, $id = null): void
    {
        switch ($admin) {
            case 'productMemory-List':
                $list_product = ProductModel::all();
                $list_product_memory = ProductModel::memories();

                include 'view/admin/product/product-memory/list.php';
                break;

            case 'productMemory-Add':
                $list_product = ProductModel::all();
                $thongBao = '';
                $thongBaoLoiTen = '';
                $thongBaoLoiSP = '';

                if (isset($_POST['submit'])) {
                    $name = trim((string) ($_POST['name'] ?? ''));
                    $id_sp = trim((string) ($_POST['id_sp'] ?? ''));

                    if ($name === '') {
                        $thongBaoLoiTen = 'Vui lòng nhập tên bộ nhớ!';
                    }
                    if ($id_sp === '' || $id_sp === '0') {
                        $thongBaoLoiSP = 'Vui lòng chọn sản phẩm!';
                    }

                    if ($thongBaoLoiTen === '' && $thongBaoLoiSP === '') {
                        ProductModel::addMemory($name, $id_sp);
                        $thongBao = 'Thêm thành công';
                    }
                }

                include 'view/admin/product/product-memory/add.php';
                break;

            case 'productMemory-Update':
                $product = ProductModel::findMemory($id);

                if (!$product) {
                    http_response_code(404);
                    exit('Bộ nhớ sản phẩm không tồn tại.');
                }

                $pm_name = $product['pm_name'];
                $thongBao = '';
                $thongBaoLoiTen = '';

                if (isset($_POST['submit'])) {
                    $name = trim((string) ($_POST['name'] ?? ''));
                    $id_sp = (int) ($_GET['idsp'] ?? $product['id_sp']);

                    if ($name === '') {
                        $thongBaoLoiTen = 'Vui lòng nhập tên bộ nhớ!';
                    } else {
                        ProductModel::updateMemory($id, $name, $id_sp);
                        $pm_name = $name;
                        $thongBao = 'Sửa thành công';
                    }
                }

                include 'view/admin/product/product-memory/update.php';
                break;

            case 'productMemory-Delete':
                $productId = (int) ($_GET['idsp'] ?? 0);
                ProductModel::deleteMemory($id);
                header('Location: ?act=admin&admin=productDetail&id=' . $productId);
                exit;
        }
    }
}
