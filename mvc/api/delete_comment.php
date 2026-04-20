<?php
header('Content-Type: application/json');
include "../query/pdo.php";
include "../query/binh-luan.php";
if (isset($_GET['id'])) {
    delete_binhluan($_GET['id']);
    echo json_encode(["status" => "success"]);
}
?>