<?php
header('Content-Type: application/json');
include "../query/pdo.php";
include "../query/san-pham.php";

if (isset($_POST['sp_id'])) {
    try {
        $sp_id = $_POST['sp_id'];
        $sp_name = $_POST['sp_name'];
        $sp_price = $_POST['sp_price'];
        $sp_quantity = $_POST['sp_quantity'];
        $sp_describe = $_POST['sp_describe'];
        $id_dm = $_POST['id_dm'];
        $sp_pricedel = $_POST['sp_pricedel'];
        $sp_image = $_POST['old_image']; // Mặc định dùng lại ảnh cũ

        if (isset($_FILES["sp_image"]["tmp_name"]) && $_FILES["sp_image"]["tmp_name"]) {
            $sp_image = "upload/" . $_FILES["sp_image"]["name"];
            move_uploaded_file($_FILES["sp_image"]["tmp_name"], "../upload/" . $_FILES["sp_image"]["name"]);
        }

        update_product($sp_id, $sp_name, $sp_image, $sp_price, $sp_quantity, $sp_describe, $id_dm, $sp_pricedel);
        
        echo json_encode(["status" => "success", "message" => "Cập nhật sản phẩm thành công!"]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
}
?>