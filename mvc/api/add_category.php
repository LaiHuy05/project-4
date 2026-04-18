<?php
// File: api/add_category.php
header('Content-Type: application/json');
include_once '../query/pdo.php';
include_once '../query/danh-muc.php'; //

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $name = trim($data['name'] ?? '');

    if (!empty($name)) {
        insert_category($name); //
        echo json_encode(["status" => "success", "message" => "Thêm danh mục '$name' thành công!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Tên danh mục không được trống"]);
    }
}