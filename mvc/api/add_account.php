<?php
header('Content-Type: application/json');
include "../query/pdo.php";
include "../query/tai-khoan.php";

$data = json_decode(file_get_contents('php://input'), true);

if (!empty($data['user']) && !empty($data['pass']) && !empty($data['email'])) {
    try {
        $user = $data['user'];
        $pass = $data['pass'];
        $email = $data['email'];
        $address = isset($data['address']) ? $data['address'] : "";
        $role = isset($data['role']) ? $data['role'] : 2; 

        insert_account($user, $pass, $email, $address, $role);
        
        echo json_encode(["status" => "success", "message" => "Tạo tài khoản thành công!"]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Lỗi: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Điền đủ User, Pass, Email "]);
}
?>