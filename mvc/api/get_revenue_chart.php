<?php
// File: api/get_revenue_chart.php
header('Content-Type: application/json');
include_once '../query/pdo.php';
include_once '../query/tong-doanh-thu.php'; //

$revenue_data = [];
for ($i = 1; $i <= 12; $i++) {
    $function_name = "thang_$i";
    if (function_exists($function_name)) {
        $result = $function_name(); //
        $revenue_data[] = isset($result[0]['tong_doanh_thu']) ? (float)$result[0]['tong_doanh_thu'] : 0;
    } else {
        $revenue_data[] = 0;
    }
}

echo json_encode([
    "status" => "success",
    "labels" => ["T1", "T2", "T3", "T4", "T5", "T6", "T7", "T8", "T9", "T10", "T11", "T12"],
    "datasets" => $revenue_data
]);