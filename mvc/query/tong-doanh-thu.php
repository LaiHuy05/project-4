<?php
/**
 * One grouped, index-friendly query per PHP request instead of 12 queries.
 * The thang_1() ... thang_12() return values keep the legacy controller shape.
 */
function current_year_monthly_revenue()
{
    static $months = null;
    if ($months !== null) return $months;
    $rows = pdo_query("SELECT MONTH(dh_orderdate) AS month_no,
            SUM(dh_totalamount) AS tong_doanh_thu
        FROM `order`
        WHERE dh_orderdate >= MAKEDATE(YEAR(CURDATE()), 1)
          AND dh_orderdate < MAKEDATE(YEAR(CURDATE())+1, 1)
        GROUP BY MONTH(dh_orderdate)");
    $months = [];
    foreach ($rows as $row) {
        $months[(int)$row['month_no']] = (float)$row['tong_doanh_thu'];
    }
    return $months;
}

function revenue_for_month($month)
{
    $months = current_year_monthly_revenue();
    return array_key_exists($month,$months) ? [['tong_doanh_thu' => $months[$month]]] : [];
}

function thang_1() { return revenue_for_month(1); }
function thang_2() { return revenue_for_month(2); }
function thang_3() { return revenue_for_month(3); }
function thang_4() { return revenue_for_month(4); }
function thang_5() { return revenue_for_month(5); }
function thang_6() { return revenue_for_month(6); }
function thang_7() { return revenue_for_month(7); }
function thang_8() { return revenue_for_month(8); }
function thang_9() { return revenue_for_month(9); }
function thang_10() { return revenue_for_month(10); }
function thang_11() { return revenue_for_month(11); }
function thang_12() { return revenue_for_month(12); }
