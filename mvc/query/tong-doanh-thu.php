<?php
function current_year_monthly_revenue()
{
    static $months = null;

    if ($months !== null) {
        return $months;
    }

    $rows = pdo_query(
        'SELECT
            MONTH(dh_orderdate) AS month_no,
            SUM(dh_totalamount) AS tong_doanh_thu
         FROM `order`
         WHERE dh_orderdate >= MAKEDATE(YEAR(CURDATE()), 1)
           AND dh_orderdate < MAKEDATE(YEAR(CURDATE()) + 1, 1)
         GROUP BY MONTH(dh_orderdate)'
    );

    $months = [];

    foreach ($rows as $row) {
        $months[(int) $row['month_no']] = (float) $row['tong_doanh_thu'];
    }

    return $months;
}
