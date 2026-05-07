<?php
header('Content-Type: application/json');
include "../query/pdo.php";
include "../query/tai-khoan.php";

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id'])) {
    try {
        // Khớp với hàm update_account($tk_id, $tk_user, $tk_password, $tk_email, $tk_address, $id_role)
        update_account(
            $data['id'], 
            $data['user'], 
            $data['pass'], 
            $data['email'], 
            $data['address'], 
            $data['role']
        );
        echo json_encode(["status" => "success", "message" => "Cập nhật tài khoản thành công!"]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
}
?>