<?php
header('Content-Type: application/json');
include "../query/pdo.php";
include "../query/binh-luan.php";

try {
    $list_comment = load_all_comment(); // Hàm này mày đã viết sẵn rồi
    echo json_encode([
        "status" => "success",
        "data" => $list_comment
    ]);
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
?>