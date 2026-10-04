<?php
$queries = 0;

function pdo_query($sql, ...$params)
{
    global $queries;
    ++$queries;

    return [
        ['month_no' => 1, 'tong_doanh_thu' => '1500000'],
        ['month_no' => 12, 'tong_doanh_thu' => '2500000'],
    ];
}

require __DIR__ . '/../query/tong-doanh-thu.php';

function check_revenue($condition, $message)
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

$revenue = current_year_monthly_revenue();

check_revenue($revenue[1] === 1500000.0, 'January revenue');
check_revenue(!isset($revenue[2]), 'Missing month');
check_revenue($revenue[12] === 2500000.0, 'December revenue');
check_revenue($queries === 1, 'Only one grouped SQL query');

echo "Revenue query smoke tests passed\n";
