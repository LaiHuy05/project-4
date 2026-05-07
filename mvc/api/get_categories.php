<?php
header('Content-Type: application/json');
include "../query/pdo.php";
include "../query/danh-muc.php";

try {
    // Sử dụng hàm load_all_category từ file của mày
    $list_category = load_all_category();
    echo json_encode([
        "status" => "success",
        "data" => $list_category
    ]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>