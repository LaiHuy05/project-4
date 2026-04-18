<?php
// File: api/delete_account.php
header('Content-Type: application/json');
include_once '../query/pdo.php';
include_once '../query/tai-khoan.php'; //

$id = $_GET['id'] ?? 0;

if ($id > 0) {
    try {
        delete_account($id); //
        echo json_encode(["status" => "success", "message" => "Xóa tài khoản thành công"]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Không thể xóa vì tài khoản có dữ liệu liên quan"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "ID không hợp lệ"]);
}