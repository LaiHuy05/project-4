<?php
final class AdminHomeController
{
    public static function handle(string $admin, $id = null): void
    {
        $count_product = count_product();
        $count_category = count_category();
        $count_order = count_order();
        $count_account = count_account();
        $count_profit = count_profit();

        $top_selling = top_product_selling();

        $monthlyRevenue = array_fill(1, 12, 0);
        foreach (current_year_monthly_revenue() as $month => $revenue) {
            $monthlyRevenue[(int) $month] = (float) $revenue;
        }

        include 'view/admin/home.php';
    }
}
