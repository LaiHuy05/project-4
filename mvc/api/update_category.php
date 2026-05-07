<?php
header('Content-Type: application/json');
include "../query/pdo.php";
include "../query/danh-muc.php";

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id']) && isset($data['name'])) {
    try {
        // Khớp với hàm update_category($dm_id, $dm_name) của mày
        update_category($data['id'], $data['name']);
        echo json_encode(["status" => "success", "message" => "Cập nhật danh mục thành công!"]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
}
?>