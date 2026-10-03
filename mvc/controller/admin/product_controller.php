<?php
/**
 * Admin product actions.
 * Database functions remain in mvc/query during this behavior-preserving refactor.
 */
final class AdminProductController
{
    public static function handle(string $admin, $id = null): void
    {
        switch ($admin) {
            case 'productList':
                $list_product = load_all_product();
                $list_category = load_all_category();
                $resultProduct = [];
        
                if (isset($_POST['btnSearch'], $_POST['inputSearch'])) {
                    $inputSearch = trim(strtolower($_POST['inputSearch'])); 
        
                    foreach ($list_product as $value) {
                        $nameProduct = strtolower($value['sp_name']);
                        $priceProduct = strtolower($value['sp_price']);
        
                        if (strpos($nameProduct, $inputSearch) !== false || strpos($priceProduct, $inputSearch) !== false) {
                            array_push($resultProduct, $value);
                        }
                    }
                }
                include "view/admin/product/list.php";
                break;
            case 'productAdd':
                $list_category = load_all_category();
                $thongBao = '';
                $thongBaoLoiTen = '';
                $thongBaoLoiAnh = '';
                $thongBaoLoiGia = '';
                $thongBaoLoiSoLuong = '';
                $thongBaoLoiMoTa = '';
                $thongBaoLoiDM = '';
                $thongBaoLoiSP = '';
                $thongBaoLoiMau = '';
                $thongBaoLoiBoNho = '';
                $thongBaoLoiTenBN = '';
                $thongBaoLoiTenMau = '';
        
                if (isset($_POST["submit"])) {
                    $name = trim($_POST['name']);
                    $image = trim($_POST["image"]);
                    $price = trim($_POST["price"]);
                    $quantity = trim($_POST["quantity"]);
                    $describe = trim($_POST['describe']);
                    $id_dm = trim($_POST['dm_id']);
                    $sp_pricedel = trim($_POST['sp_pricedel']);
        
        
                    if (isset($_FILES["file_upload"]["tmp_name"]) && $_FILES["file_upload"]["tmp_name"]) {
                        $thamSo1 = $_FILES["file_upload"]["tmp_name"];
                        $thamSo2 = "./upload/" . $_FILES["file_upload"]["name"];
                        if (move_uploaded_file($thamSo1, $thamSo2)) {
                            $image = "upload/" . $_FILES["file_upload"]["name"];
                        }
                    } else { // Xử lý trường hợp tệp không được tải lên $image = null; }
                    }
        
        
        
        
                    if ($name === '') {
                        $thongBaoLoiTen = 'Vui lòng nhập Tên!';
                    } elseif (strlen($name) < 5) {
                        $thongBaoLoiTen = 'Tên đăng nhập phải có ít nhất 5 ký tự.';
                    }
                    if ($image === '') {
                        $thongBaoLoiAnh = 'Vui lòng nhập đường dẫn ảnh!';
                    }
                    if ($price === '') {
                        $thongBaoLoiGia = 'Vui lòng nhập Giá!';
                    } elseif (!is_numeric($price)) {
                        $thongBaoLoiGia = 'Giá phải là một số.';
                    }
                    if ($quantity === '') {
                        $thongBaoLoiSoLuong = 'Vui lòng nhập Số Lượng!';
                    }
                    if ($describe === '') {
                        $thongBaoLoiMoTa = 'Vui lòng nhập Mô tả!';
                    }
                    if ($id_dm === '') {
                        $thongBaoLoiDM = 'Vui lòng chọn danh mục!';
                    }
                    if (
                        empty($thongBaoLoiTen) && empty($thongBaoLoiAnh) && empty($thongBaoLoiGia) &&
                        empty($thongBaoLoiTieuDe) && empty($thongBaoLoiSoLuong) &&
                        empty($thongBaoLoiMoTa) && empty($thongBaoLoiDM)
                    ) {
                        $sp_ma = 'SP_' . mt_rand(100000, 999999);
                        insert_product($name, $image, $price, $quantity, $describe, $id_dm, $sp_ma, $sp_pricedel);
                        $thongBao = "Thêm mới thành công";
                    }
                }
        
                $list_product = load_all_product();
                $list_product_color = load_all_product_color();
                $list_category = load_all_category();
                if (isset($_POST["submit_color"])) {
                    $name = trim($_POST['name_color']);
        
                    $id_sp = trim($_POST['id_sp_c']);
        
                    if ($name === '') {
                        $thongBaoLoiTenMau = 'Vui lòng nhập Tên!';
                    } elseif (strlen($name) < 5) {
                        $thongBaoLoiTenMau = 'Tên đăng nhập phải có ít nhất 5 ký tự.';
                    }
        
        
                    if ($id_sp === '' || $id_sp === '0') {
                        $thongBaoLoiMau = 'Vui lòng chọn sản phẩm!';
                    }
        
        
                    if (
                        empty($thongBaoLoiTenMau) &&  empty($thongBaoLoiMau)
                    ) {
                        insert_product_color($name, $id_sp);
                        $thongBao = "Thêm thành công";
                    }
                }
        
                $list_product = load_all_product();
                $list_product_memory = load_all_product_memory();
                $list_category = load_all_category();
                if (isset($_POST["submit_memory"])) {
                    $name = trim($_POST['name_memory']);
        
                    $id_sp = trim($_POST['id_sp_m']);
        
                    if ($name === '') {
                        $thongBaoLoiTenBN = 'Vui lòng nhập Tên!';
                    } elseif (strlen($name) < 5) {
                        $thongBaoLoiTenBN = 'Tên đăng nhập phải có ít nhất 5 ký tự.';
                    }
        
        
                    if ($id_sp === '' || $id_sp === '0') {
                        $thongBaoLoiBoNho = 'Vui lòng chọn sản phẩm!';
                    }
        
        
                    if (
                        empty($thongBaoLoiTen) &&  empty($thongBaoLoiBoNho)
                    ) {
                        insert_product_memory($name, $id_sp);
                        $thongBao = "Thêm thành công";
                    }
                }
                include "view/admin/product/add.php";
                break;
            case 'productUpdate':
                $thongBao = "";
                $thongBaoLoi = "";
                $id = $_GET['id'] ?? '';
                $list_product = load_all_product();
                $list_category = load_all_category();
                $list_product_color = load_all_product_color();
                $list_product_memory = load_all_product_memory();
        
        
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
        
                $product = load_one_product($id);
                $sp_id = $product['sp_id'];
                $sp_name = $product['sp_name'];
                $sp_image = $product['sp_image'];
                $sp_price = $product['sp_price'];
                $sp_quantity = $product['sp_quantity'];
                $sp_describe = $product['sp_describe'];
                $id_dm = $product['id_dm'];
                $sp_pricedel = $product['sp_pricedel'];
        
                // $name = load_one_product($id);
                if (isset($_POST["submit"])) {
                    $name = trim($_POST['name']);
                    $image = trim($_POST["image"]);
                    $price = trim($_POST["price"]);
                    $quantity = trim($_POST["quantity"]);
                    $describe = trim($_POST['describe']);
                    $id_dm = trim($_POST['dm_id']);
                    $sp_pricedel = trim($_POST['sp_pricedel']);
                    if (isset($_FILES["file_upload"]["tmp_name"]) && $_FILES["file_upload"]["tmp_name"]) {
                        $thamSo1 = $_FILES["file_upload"]["tmp_name"];
                        $thamSo2 = "./upload/" . $_FILES["file_upload"]["name"];
                        if (move_uploaded_file($thamSo1, $thamSo2)) {
                            $image = "upload/" . $_FILES["file_upload"]["name"];
                        }
                    }
        
                    if ($name === '') {
                        $thongBaoLoiTen = 'Vui lòng nhập Tên!';
                    } elseif (strlen($name) < 5) {
                        $thongBaoLoiTen = 'Tên đăng nhập phải có ít nhất 5 ký tự.';
                    }
        
                    if ($image === '') {
                        $thongBaoLoiAnh = 'Vui lòng nhập đường dẫn ảnh!';
                    }
        
                    if ($price === '') {
                        $thongBaoLoiGia = 'Vui lòng nhập Giá!';
                    } elseif (!is_numeric($price)) {
                        $thongBaoLoiGia = 'Giá phải là một số.';
                    }
        
                    if ($quantity === '') {
                        $thongBaoLoiSoLuong = 'Vui lòng nhập Số Lượng!';
                    }
        
                    if ($describe === '') {
                        $thongBaoLoiMoTa = 'Vui lòng nhập Mô tả!';
                    }
        
                    if ($id_dm === '' || $id_dm === '0') {
                        $thongBaoLoiDM = 'Vui lòng chọn danh mục!';
                    }
                    if (
                        empty($thongBaoLoiTen) && empty($thongBaoLoiAnh) && empty($thongBaoLoiGia) &&
                        empty($thongBaoLoiTieuDe) && empty($thongBaoLoiSoLuong) &&
                        empty($thongBaoLoiMoTa) && empty($thongBaoLoiDM)
                    ) {
                        update_product($sp_id, $name, $image, $price, $quantity, $describe, $id_dm, $sp_pricedel);
                        $thongBao = "Sửa thành công";
                    }
                }
                if (isset($_POST["submit_color"])) {
                    $name = trim($_POST['name_color']);
        
                    $id_sp = $_GET["id"];
        
                    if ($name === '') {
                        $thongBaoLoiTenMau = 'Vui lòng nhập Tên!';
                    }
                    if (
                        empty($thongBaoLoiTenMau) &&  empty($thongBaoLoiSP)
                    ) {
                        insert_product_color($name, $id_sp);
                        $thongBaoMau = "Thêm thành công";
                    }
                }
                if (isset($_POST["submit_memory"])) {
                    $name = trim($_POST['name_memory']);
        
                    $id_sp = $_GET["id"];
        
                    if ($name === '') {
                        $thongBaoLoiTenBN = 'Vui lòng nhập Tên!';
                    }
        
                    if (
                        empty($thongBaoLoiTenBN) &&  empty($thongBaoLoiSP)
                    ) {
                        insert_product_memory($name, $id_sp);
                        $thongBaoBN = "Thêm thành công";
                    }
                }
                include "view/admin/product/update.php";
                break;
            case 'productDelete':
                delete_product($id);
        
                header("Location: ?act=admin&admin=productList");
                break;
            case 'productDetail':
        
                $id = $_GET['id'] ?? '';
                $list_product = load_all_product();
                $list_category = load_all_category();
                $list_product_color = load_all_product_color();
                $list_product_memory = load_all_product_memory();
        
                $thongBaoLoiSP = '';
                $thongBaoLoiMau = '';
                $thongBaoLoiBoNho = '';
                $thongBaoLoiTenBN = '';
                $thongBaoLoiTenMau = '';
                $thongBaoMau = '';
                $thongBaoBN = '';
        
                $product = load_one_product($id);
                $sp_id = $product['sp_id'];
                $sp_name = $product['sp_name'];
                $sp_image = $product['sp_image'];
                $sp_price = $product['sp_price'];
                $sp_quantity = $product['sp_quantity'];
                $sp_describe = $product['sp_describe'];
                $id_dm = $product['id_dm'];
                $sp_pricedel = $product['sp_pricedel'];
        
        
                include "view/admin/product/detail.php";
                break;
        }
    }
}
