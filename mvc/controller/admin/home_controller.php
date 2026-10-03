<?php
/**
 * Admin home actions.
 * Database functions remain in mvc/query during this behavior-preserving refactor.
 */
final class AdminHomeController
{
    public static function handle(string $admin, $id = null): void
    {
        switch ($admin) {
            case 'home':
                $count_product = count_product();
                $count_category = count_category();
                $count_order = count_order();
                $count_account = count_account();
                $count_profit = count_profit();
        
                $count_iphone = count_iphone();
                $count_samsung = count_samsung();
                $count_oppo = count_oppo();
        
                $top_selling = top_product_selling();
                $report_totalamount = report_totalamount();
                // Gọi các hàm lấy doanh thu theo tháng
                $thang_1 = thang_1();
                $thang_2 = thang_2();
                $thang_3 = thang_3();
                $thang_4 = thang_4();
                $thang_5 = thang_5();
                $thang_6 = thang_6();
                $thang_7 = thang_7();
                $thang_8 = thang_8();
                $thang_9 = thang_9();
                $thang_10 = thang_10();
                $thang_11 = thang_11();
                $thang_12 = thang_12();
        
                // Tránh lỗi khi mảng không có phần tử
                $doanh_thu_thang_1 = isset($thang_1[0]['tong_doanh_thu']) ? (float)$thang_1[0]['tong_doanh_thu'] : 0;
                $doanh_thu_thang_2 = isset($thang_2[0]['tong_doanh_thu']) ? (float)$thang_2[0]['tong_doanh_thu'] : 0;
                $doanh_thu_thang_3 = isset($thang_3[0]['tong_doanh_thu']) ? (float)$thang_3[0]['tong_doanh_thu'] : 0;
                $doanh_thu_thang_4 = isset($thang_4[0]['tong_doanh_thu']) ? (float)$thang_4[0]['tong_doanh_thu'] : 0;
                $doanh_thu_thang_5 = isset($thang_5[0]['tong_doanh_thu']) ? (float)$thang_5[0]['tong_doanh_thu'] : 0;
                $doanh_thu_thang_6 = isset($thang_6[0]['tong_doanh_thu']) ? (float)$thang_6[0]['tong_doanh_thu'] : 0;
                $doanh_thu_thang_7 = isset($thang_7[0]['tong_doanh_thu']) ? (float)$thang_7[0]['tong_doanh_thu'] : 0;
                $doanh_thu_thang_8 = isset($thang_8[0]['tong_doanh_thu']) ? (float)$thang_8[0]['tong_doanh_thu'] : 0;
                $doanh_thu_thang_9 = isset($thang_9[0]['tong_doanh_thu']) ? (float)$thang_9[0]['tong_doanh_thu'] : 0;
                $doanh_thu_thang_10 = isset($thang_10[0]['tong_doanh_thu']) ? (float)$thang_10[0]['tong_doanh_thu'] : 0;
                $doanh_thu_thang_11 = isset($thang_11[0]['tong_doanh_thu']) ? (float)$thang_11[0]['tong_doanh_thu'] : 0;
                $doanh_thu_thang_12 = isset($thang_12[0]['tong_doanh_thu']) ? (float)$thang_12[0]['tong_doanh_thu'] : 0;
        
                include 'view/admin/home.php';
                break;
        }
    }
}
