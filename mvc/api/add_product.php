<?php
header('Content-Type: application/json');
include "../query/pdo.php";
include "../query/san-pham.php";

if (isset($_POST['sp_name']) && isset($_POST['sp_price'])) {
    try {
        $sp_name = $_POST['sp_name'];
        $sp_price = $_POST['sp_price'];
        $sp_quantity = $_POST['sp_quantity'];
        $sp_describe = $_POST['sp_describe'];
        $id_dm = $_POST['id_dm'];
        $sp_pricedel = isset($_POST['sp_pricedel']) ? $_POST['sp_pricedel'] : 0;
        
        $sp_ma = 'SP_' . mt_rand(100000, 999999);

        $sp_image = "";
        if (isset($_FILES["sp_image"]["tmp_name"]) && $_FILES["sp_image"]["tmp_name"]) {
            $sp_image = "upload/" . $_FILES["sp_image"]["name"];
            $target_file = "../upload/" . $_FILES["sp_image"]["name"];
            move_uploaded_file($_FILES["sp_image"]["tmp_name"], $target_file);
        }

        insert_product($sp_name, $sp_image, $sp_price, $sp_quantity, $sp_describe, $id_dm, $sp_ma, $sp_pricedel);
        
        echo json_encode(["status" => "success", "message" => "Thêm sản phẩm thành công!"]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Lỗi: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Thiếu thông tin bắt buộc (sp_name, sp_price)!"]);
}
?>