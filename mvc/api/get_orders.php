<?php
header('Content-Type: application/json');
include "../query/pdo.php";
include "../query/don-hang.php";

try {
    // Mày kiểm tra lại trong query/don-hang.php xem hàm của mày tên là load_all_order hay load_all_bill nhé
    $list_order = load_all_order(); 
    echo json_encode([
        "status" => "success",
        "data" => $list_order
    ]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>