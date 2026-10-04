<?php
require __DIR__ . '/../model/OrderModel.php';

function check_revenue($condition, $message)
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

$revenue = OrderModel::mapMonthlyRevenue([
    ['month_no' => 1, 'tong_doanh_thu' => '1500000'],
    ['month_no' => 12, 'tong_doanh_thu' => '2500000'],
]);

check_revenue($revenue[1] === 1500000.0, 'January revenue');
check_revenue(!isset($revenue[2]), 'Missing month');
check_revenue($revenue[12] === 2500000.0, 'December revenue');

echo "Revenue model smoke tests passed\n";
