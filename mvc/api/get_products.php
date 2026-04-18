<?php
// File: api/get_products.php
header('Content-Type: application/json');
include_once '../query/pdo.php';
include_once '../query/san-pham.php'; //

try {
    $products = load_all_product(); //
    echo json_encode([
        "status" => "success",
        "data" => $products
    ]);
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}