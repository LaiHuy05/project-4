<?php
final class DashboardModel
{
    public static function summary(): array
    {
        $row = Database::one(
            'SELECT
                (SELECT COUNT(*) FROM product) AS product_count,
                (SELECT COUNT(*) FROM category) AS category_count,
                (SELECT COUNT(*) FROM `order`) AS order_count,
                (SELECT COUNT(*) FROM account) AS account_count,
                COALESCE((SELECT SUM(dh_totalamount) FROM `order`), 0) AS profit'
        );

        return $row ?: [
            'product_count' => 0,
            'category_count' => 0,
            'order_count' => 0,
            'account_count' => 0,
            'profit' => 0,
        ];
    }
}
