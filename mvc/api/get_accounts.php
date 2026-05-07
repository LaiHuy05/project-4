<?php
header('Content-Type: application/json');
include "../query/pdo.php";
include "../query/tai-khoan.php";

try {
    // Gọi hàm load_all_account đã có sẵn của mày
    $list_account = load_all_account();
    
    // Loại bỏ mật khẩu khỏi danh sách trả về
    foreach ($list_account as $key => $account) {
        unset($list_account[$key]['tk_password']); 
    }

    echo json_encode([
        "status" => "success",
        "data" => $list_account
    ]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>