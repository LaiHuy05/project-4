<?php
header('Content-Type: application/json');
include "../query/pdo.php";
include "../query/binh-luan.php";

// Đọc dữ liệu JSON từ request body
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['content']) && isset($data['id_tk']) && isset($data['id_sp'])) {
    try {
        insert_comment($data['content'], $data['id_tk'], $data['id_sp']); // Gọi hàm insert của mày
        echo json_encode([
            "status" => "success",
            "message" => "Đã đăng bình luận thành công!"
        ]);
    } catch (Exception $e) {
        echo json_encode([
            "status" => "error",
            "message" => "Lỗi: " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Thiếu dữ liệu đầu vào (content, id_tk, id_sp)"
    ]);
}
?>