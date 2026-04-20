<?php
header('Content-Type: application/json');
include "../query/pdo.php";
include "../query/tai-khoan.php";

$data = json_decode(file_get_contents('php://input'), true);
if (!empty($data['user'])) {
    try {
        insert_taikhoan($data['user'], $data['pass'], $data['email'], $data['address'], $data['tel'], $data['role'] ?? 0);
        echo json_encode(["status" => "success", "message" => "Tạo tài khoản thành công"]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
}
?>