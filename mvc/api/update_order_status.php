<?php
// File: api/update_order_status.php
header('Content-Type: application/json');
include_once '../query/pdo.php';
include_once '../query/don-hang.php'; //

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['dh_id'] ?? 0;
    $status = $data['status'] ?? '';

    if ($id > 0 && $status !== '') {
        update_order($id, $status); //
        echo json_encode(["status" => "success", "message" => "Đã cập nhật đơn hàng $id sang trạng thái: $status"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Dữ liệu đầu vào không hợp lệ"]);
    }
}