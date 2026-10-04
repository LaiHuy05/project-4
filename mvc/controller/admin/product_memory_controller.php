<?php
final class AdminProductMemoryController
{
    public static function handle(string $admin, $id = null): void
    {
        switch ($admin) {
            case 'productMemory-List':
                $list_product = load_all_product();
                $list_product_memory = load_all_product_memory();

                include 'view/admin/product/product-memory/list.php';
                break;

            case 'productMemory-Add':
                $list_product = load_all_product();
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
                        insert_product_memory($name, $id_sp);
                        $thongBao = 'Thêm thành công';
                    }
                }

                include 'view/admin/product/product-memory/add.php';
                break;

            case 'productMemory-Update':
                $product = load_one_product_memory($id);

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
                        update_product_memory($id, $name, $id_sp);
                        $pm_name = $name;
                        $thongBao = 'Sửa thành công';
                    }
                }

                include 'view/admin/product/product-memory/update.php';
                break;

            case 'productMemory-Delete':
                $productId = (int) ($_GET['idsp'] ?? 0);
                delete_product_memory($id);
                header('Location: ?act=admin&admin=productDetail&id=' . $productId);
                exit;
        }
    }
}
