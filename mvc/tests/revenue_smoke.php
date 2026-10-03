<?php
$queries=0;
function pdo_query($sql,...$params) {
    global $queries;
    ++$queries;
    return [['month_no'=>1,'tong_doanh_thu'=>'1500000'],
            ['month_no'=>12,'tong_doanh_thu'=>'2500000']];
}
require __DIR__.'/../query/tong-doanh-thu.php';
function check_revenue($condition,$message) { if (!$condition) throw new RuntimeException($message); }
check_revenue(thang_1()[0]['tong_doanh_thu']===1500000.0,'January shape');
check_revenue(thang_2()===[],'Missing month');
check_revenue(thang_12()[0]['tong_doanh_thu']===2500000.0,'December shape');
check_revenue($queries===1,'One grouped SQL query for twelve report methods');
echo "Revenue query smoke tests passed\n";
