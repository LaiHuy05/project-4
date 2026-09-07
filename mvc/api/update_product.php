<?php
header('Content-Type: application/json');
include "../query/pdo.php";
include "../query/san-pham.php";

if (isset($_POST['sp_id'])) {
    try {
        $sp_id = $_POST['sp_id'];

        $old_product = load_one_product($sp_id);

        if (!$old_product) {
            echo json_encode(["status" => "error", "message" => "Không tìm thấy sản phẩm này!"]);
            exit; 
        }
        $sp_name = isset($_POST['sp_name']) ? $_POST['sp_name'] : $old_product['sp_name'];
        $sp_price = isset($_POST['sp_price']) ? $_POST['sp_price'] : $old_product['sp_price'];
        $sp_quantity = isset($_POST['sp_quantity']) ? $_POST['sp_quantity'] : $old_product['sp_quantity'];
        $sp_describe = isset($_POST['sp_describe']) ? $_POST['sp_describe'] : $old_product['sp_describe'];
        $id_dm = isset($_POST['id_dm']) ? $_POST['id_dm'] : $old_product['id_dm'];
        $sp_pricedel = isset($_POST['sp_pricedel']) ? $_POST['sp_pricedel'] : $old_product['sp_pricedel'];

        $sp_image = $old_product['sp_image']; 

        if (isset($_FILES["sp_image"]["tmp_name"]) && $_FILES["sp_image"]["tmp_name"]) {
            $sp_image = "upload/" . $_FILES["sp_image"]["name"];
            move_uploaded_file($_FILES["sp_image"]["tmp_name"], "../upload/" . $_FILES["sp_image"]["name"]);
        }

        update_product($sp_id, $sp_name, $sp_image, $sp_price, $sp_quantity, $sp_describe, $id_dm, $sp_pricedel);
        
        echo json_encode(["status" => "success", "message" => "Cập nhật sản phẩm thành công!"]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
} else {

    echo json_encode(["status" => "error", "message" => "Thiếu ID sản phẩm (sp_id)! Vui lòng kiểm tra lại."]);
}
?>