<?php
final class OrderModel
{
    public static function all(): array
    {
        return Database::query('SELECT * FROM `order`');
    }

    public static function find($id)
    {
        return Database::one('SELECT * FROM `order` WHERE dh_id = ?', $id);
    }

    public static function create(
        string $name,
        string $email,
        string $phone,
        string $address,
        string $country,
        string $city,
        string $district,
        string $commune,
        string $message,
        string $status,
        $totalAmount,
        $accountId,
        $quantity,
        string $orderCode
    ): string {
        return Database::insert(
            'INSERT INTO `order` (
                dh_nameUser, dh_emailUser, dh_phoneUser, dh_addressUser,
                dh_countryPay, dh_cityPay, dh_districtPay, dh_communePay,
                dh_messagePay, dh_orderdate, dh_status, dh_totalamount,
                id_tk, sp_quantity, dh_ma
             ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?)',
            $name,
            $email,
            $phone,
            $address,
            $country,
            $city,
            $district,
            $commune,
            $message,
            $status,
            $totalAmount,
            $accountId,
            $quantity,
            $orderCode
        );
    }

    public static function delete($id): void
    {
        Database::execute('DELETE FROM `order` WHERE dh_id = ?', $id);
    }

    public static function updateStatus($id, string $status): void
    {
        Database::execute(
            'UPDATE `order` SET dh_status = ? WHERE dh_id = ?',
            $status,
            $id
        );
    }

    public static function details(): array
    {
        return Database::query('SELECT * FROM orderdetail');
    }

    public static function addDetail(
        $orderId,
        $productId,
        $quantity,
        string $memory,
        string $color
    ): void {
        Database::execute(
            'INSERT INTO orderdetail(id_dh, id_sp, ct_quantity, od_option, od_optionColor)
             VALUES (?, ?, ?, ?, ?)',
            $orderId,
            $productId,
            $quantity,
            $memory,
            $color
        );
    }

    public static function forUser($userId): array
    {
        return Database::query(
            'SELECT * FROM `order` WHERE id_tk = ? ORDER BY dh_orderdate DESC',
            $userId
        );
    }

    public static function detailsForUser($userId): array
    {
        return Database::query(
            'SELECT d.*
             FROM orderdetail d
             JOIN `order` o ON o.dh_id = d.id_dh
             WHERE o.id_tk = ?',
            $userId
        );
    }

    public static function currentYearMonthlyRevenue(): array
    {
        $rows = Database::query(
            'SELECT
                MONTH(dh_orderdate) AS month_no,
                SUM(dh_totalamount) AS tong_doanh_thu
             FROM `order`
             WHERE dh_orderdate >= MAKEDATE(YEAR(CURDATE()), 1)
               AND dh_orderdate < MAKEDATE(YEAR(CURDATE()) + 1, 1)
             GROUP BY MONTH(dh_orderdate)'
        );

        return self::mapMonthlyRevenue($rows);
    }

    public static function mapMonthlyRevenue(array $rows): array
    {
        $months = [];

        foreach ($rows as $row) {
            $months[(int) $row['month_no']] = (float) $row['tong_doanh_thu'];
        }

        return $months;
    }
}
