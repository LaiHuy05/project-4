<?php
final class AdminHomeController
{
    public static function handle(string $admin, $id = null): void
    {
        $summary = DashboardModel::summary();

        $productCount = (int) $summary['product_count'];
        $categoryCount = (int) $summary['category_count'];
        $orderCount = (int) $summary['order_count'];
        $accountCount = (int) $summary['account_count'];
        $profit = (float) $summary['profit'];

        $top_selling = ProductModel::topSelling();

        $monthlyRevenue = array_fill(1, 12, 0);
        foreach (OrderModel::currentYearMonthlyRevenue() as $month => $revenue) {
            $monthlyRevenue[(int) $month] = (float) $revenue;
        }

        include 'view/admin/home.php';
    }
}
