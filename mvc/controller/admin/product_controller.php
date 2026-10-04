<?php
final class AdminProductController
{
    public static function handle(string $admin, $id = null): void
    {
        switch ($admin) {
            case 'productList':
                $list_product = ProductModel::all();
                $list_category = CategoryModel::all();
                $resultProduct = [];

                if (isset($_POST['btnSearch'])) {
                    $keyword = trim((string) ($_POST['inputSearch'] ?? ''));

                    foreach ($list_product as $product) {
                        $matchesName = stripos($product['sp_name'], $keyword) !== false;
                        $matchesPrice = stripos((string) $product['sp_price'], $keyword) !== false;

                        if ($matchesName || $matchesPrice) {
                            $resultProduct[] = $product;
                        }
                    }
                }

                include 'view/admin/product/list.php';
                break;

            case 'productAdd':
                self::addProduct();
                break;

            case 'productUpdate':
                self::updateProduct($id);
                break;

            case 'productDetail':
                self::showProduct($id);
                break;

            case 'productDelete':
                ProductModel::delete($id);
                header('Location: ?act=admin&admin=productList');
                exit;
        }
    }

    private static function addProduct(): void
    {
        $list_category = CategoryModel::all();

        $thongBao = '';
        $thongBaoLoiTen = '';
        $thongBaoLoiAnh = '';
        $thongBaoLoiGia = '';
        $thongBaoLoiSoLuong = '';
        $thongBaoLoiMoTa = '';
        $thongBaoLoiDM = '';

        if (isset($_POST['submit'])) {
            $name = trim((string) ($_POST['name'] ?? ''));
            $image = trim((string) ($_POST['image'] ?? ''));
            $price = trim((string) ($_POST['price'] ?? ''));
            $quantity = trim((string) ($_POST['quantity'] ?? ''));
            $describe = trim((string) ($_POST['describe'] ?? ''));
            $id_dm = trim((string) ($_POST['dm_id'] ?? ''));
            $sp_pricedel = trim((string) ($_POST['sp_pricedel'] ?? ''));

            $image = self::uploadedImage($image);

            if ($name === '') {
                $thongBaoLoiTen = 'Vui lòng nhập tên sản phẩm!';
            }
            if ($image === '') {
                $thongBaoLoiAnh = 'Vui lòng nhập hoặc tải ảnh!';
            }
            if ($price === '' || !is_numeric($price)) {
                $thongBaoLoiGia = 'Giá phải là một số!';
            }
            if ($quantity === '' || !is_numeric($quantity)) {
                $thongBaoLoiSoLuong = 'Số lượng phải là một số!';
            }
            if ($describe === '') {
                $thongBaoLoiMoTa = 'Vui lòng nhập mô tả!';
            }
            if ($id_dm === '' || $id_dm === '0') {
                $thongBaoLoiDM = 'Vui lòng chọn danh mục!';
            }

            if (
                $thongBaoLoiTen === ''
                && $thongBaoLoiAnh === ''
                && $thongBaoLoiGia === ''
                && $thongBaoLoiSoLuong === ''
                && $thongBaoLoiMoTa === ''
                && $thongBaoLoiDM === ''
            ) {
                $sp_ma = 'SP_' . random_int(100000, 999999);
                ProductModel::create($name, $image, $price, $quantity, $describe, $id_dm, $sp_ma, $sp_pricedel);
                $thongBao = 'Thêm mới thành công';
            }
        }

        include 'view/admin/product/add.php';
    }

    private static function updateProduct($id): void
    {
        $product = ProductModel::find($id);

        if (!$product) {
            http_response_code(404);
            exit('Sản phẩm không tồn tại.');
        }

        $list_category = CategoryModel::all();

        $sp_id = $product['sp_id'];
        $sp_name = $product['sp_name'];
        $sp_image = $product['sp_image'];
        $sp_price = $product['sp_price'];
        $sp_quantity = $product['sp_quantity'];
        $sp_describe = $product['sp_describe'];
        $id_dm = $product['id_dm'];
        $sp_pricedel = $product['sp_pricedel'];

        $thongBao = '';
        $thongBaoLoiTen = '';
        $thongBaoLoiAnh = '';
        $thongBaoLoiGia = '';
        $thongBaoLoiSoLuong = '';
        $thongBaoLoiMoTa = '';
        $thongBaoLoiDM = '';
        $thongBaoLoiTenBN = '';
        $thongBaoLoiTenMau = '';
        $thongBaoMau = '';
        $thongBaoBN = '';

        if (isset($_POST['submit'])) {
            $name = trim((string) ($_POST['name'] ?? ''));
            $image = trim((string) ($_POST['image'] ?? ''));
            $price = trim((string) ($_POST['price'] ?? ''));
            $quantity = trim((string) ($_POST['quantity'] ?? ''));
            $describe = trim((string) ($_POST['describe'] ?? ''));
            $id_dm = trim((string) ($_POST['dm_id'] ?? ''));
            $sp_pricedel = trim((string) ($_POST['sp_pricedel'] ?? ''));

            $image = self::uploadedImage($image);

            if ($name === '') {
                $thongBaoLoiTen = 'Vui lòng nhập tên sản phẩm!';
            }
            if ($image === '') {
                $thongBaoLoiAnh = 'Vui lòng nhập hoặc tải ảnh!';
            }
            if ($price === '' || !is_numeric($price)) {
                $thongBaoLoiGia = 'Giá phải là một số!';
            }
            if ($quantity === '' || !is_numeric($quantity)) {
                $thongBaoLoiSoLuong = 'Số lượng phải là một số!';
            }
            if ($describe === '') {
                $thongBaoLoiMoTa = 'Vui lòng nhập mô tả!';
            }
            if ($id_dm === '' || $id_dm === '0') {
                $thongBaoLoiDM = 'Vui lòng chọn danh mục!';
            }

            if (
                $thongBaoLoiTen === ''
                && $thongBaoLoiAnh === ''
                && $thongBaoLoiGia === ''
                && $thongBaoLoiSoLuong === ''
                && $thongBaoLoiMoTa === ''
                && $thongBaoLoiDM === ''
            ) {
                ProductModel::update($sp_id, $name, $image, $price, $quantity, $describe, $id_dm, $sp_pricedel);
                $thongBao = 'Sửa thành công';

                $sp_name = $name;
                $sp_image = $image;
                $sp_price = $price;
                $sp_quantity = $quantity;
                $sp_describe = $describe;
            }
        }

        if (isset($_POST['submit_color'])) {
            $colorName = trim((string) ($_POST['name_color'] ?? ''));

            if ($colorName === '') {
                $thongBaoLoiTenMau = 'Vui lòng nhập tên màu!';
            } else {
                ProductModel::addColor($colorName, $sp_id);
                $thongBaoMau = 'Thêm thành công';
            }
        }

        if (isset($_POST['submit_memory'])) {
            $memoryName = trim((string) ($_POST['name_memory'] ?? ''));

            if ($memoryName === '') {
                $thongBaoLoiTenBN = 'Vui lòng nhập tên bộ nhớ!';
            } else {
                ProductModel::addMemory($memoryName, $sp_id);
                $thongBaoBN = 'Thêm thành công';
            }
        }

        include 'view/admin/product/update.php';
    }

    private static function showProduct($id): void
    {
        $product = ProductModel::find($id);

        if (!$product) {
            http_response_code(404);
            exit('Sản phẩm không tồn tại.');
        }

        $list_product = ProductModel::all();
        $list_category = CategoryModel::all();
        $list_product_color = ProductModel::colors();
        $list_product_memory = ProductModel::memories();

        $sp_id = $product['sp_id'];
        $sp_name = $product['sp_name'];
        $sp_image = $product['sp_image'];
        $sp_price = $product['sp_price'];
        $sp_quantity = $product['sp_quantity'];
        $sp_describe = $product['sp_describe'];
        $id_dm = $product['id_dm'];
        $sp_pricedel = $product['sp_pricedel'];

        include 'view/admin/product/detail.php';
    }

    private static function uploadedImage(string $currentImage): string
    {
        if (empty($_FILES['file_upload']['tmp_name'])) {
            return $currentImage;
        }

        $fileName = basename($_FILES['file_upload']['name']);
        $target = './upload/' . $fileName;

        if (move_uploaded_file($_FILES['file_upload']['tmp_name'], $target)) {
            return 'upload/' . $fileName;
        }

        return $currentImage;
    }
}
