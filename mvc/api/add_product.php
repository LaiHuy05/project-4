<?php
header('Content-Type: application/json');
include "../query/pdo.php";
include "../query/san-pham.php";

if (isset($_POST['ten_sp'])) {
    try {
        $hinh = $_FILES['hinh']['name'];
        move_uploaded_file($_FILES["hinh"]["tmp_name"], "../upload/" . $hinh);
        insert_product($_POST['ten_sp'], $_POST['gia_sp'], $hinh, $_POST['mota_sp'], $_POST['iddm']);
        echo json_encode(["status" => "success", "message" => "Thêm sản phẩm thành công"]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
}
?>