<?php
final class ProductModel
{
    public static function all(): array
    {
        return Database::query('SELECT * FROM product');
    }

    public static function find($id)
    {
        return Database::one('SELECT * FROM product WHERE sp_id = ?', $id);
    }

    public static function findForUpdate($id)
    {
        return Database::one(
            'SELECT sp_id, sp_price FROM product WHERE sp_id = ? FOR UPDATE',
            $id
        );
    }

    public static function create(
        string $name,
        string $image,
        $price,
        $quantity,
        string $description,
        $categoryId,
        string $code,
        $oldPrice
    ): void {
        Database::execute(
            'INSERT INTO product(
                sp_name, sp_image, sp_price, sp_quantity,
                sp_describe, id_dm, sp_ma, sp_pricedel
             ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
            $name,
            $image,
            $price,
            $quantity,
            $description,
            $categoryId,
            $code,
            $oldPrice
        );
    }

    public static function update(
        $id,
        string $name,
        string $image,
        $price,
        $quantity,
        string $description,
        $categoryId,
        $oldPrice
    ): void {
        Database::execute(
            'UPDATE product
             SET sp_name = ?, sp_image = ?, sp_price = ?, sp_quantity = ?,
                 sp_describe = ?, sp_pricedel = ?, id_dm = ?
             WHERE sp_id = ?',
            $name,
            $image,
            $price,
            $quantity,
            $description,
            $oldPrice,
            $categoryId,
            $id
        );
    }

    public static function delete($id): void
    {
        Database::execute('DELETE FROM product WHERE sp_id = ?', $id);
    }

    public static function colors(): array
    {
        return Database::query('SELECT * FROM productcolor');
    }

    public static function findColor($id)
    {
        return Database::one('SELECT * FROM productcolor WHERE pc_id = ?', $id);
    }

    public static function addColor(string $name, $productId): void
    {
        Database::execute(
            'INSERT INTO productcolor(pc_name, id_sp) VALUES (?, ?)',
            $name,
            $productId
        );
    }

    public static function updateColor($id, string $name, $productId): void
    {
        Database::execute(
            'UPDATE productcolor SET pc_name = ?, id_sp = ? WHERE pc_id = ?',
            $name,
            $productId,
            $id
        );
    }

    public static function deleteColor($id): void
    {
        Database::execute('DELETE FROM productcolor WHERE pc_id = ?', $id);
    }

    public static function memories(): array
    {
        return Database::query('SELECT * FROM productmemory');
    }

    public static function findMemory($id)
    {
        return Database::one('SELECT * FROM productmemory WHERE pm_id = ?', $id);
    }

    public static function addMemory(string $name, $productId): void
    {
        Database::execute(
            'INSERT INTO productmemory(pm_name, id_sp) VALUES (?, ?)',
            $name,
            $productId
        );
    }

    public static function updateMemory($id, string $name, $productId): void
    {
        Database::execute(
            'UPDATE productmemory SET pm_name = ?, id_sp = ? WHERE pm_id = ?',
            $name,
            $productId,
            $id
        );
    }

    public static function deleteMemory($id): void
    {
        Database::execute('DELETE FROM productmemory WHERE pm_id = ?', $id);
    }

    public static function topSelling(): array
    {
        return Database::query(
            'SELECT
                p.sp_id AS id_sp,
                p.sp_ma,
                p.sp_name AS ten_sp,
                SUM(od.ct_quantity) AS so_luong_ban,
                SUM(od.ct_quantity * p.sp_price) AS doanh_thu
             FROM product p
             INNER JOIN orderdetail od ON p.sp_id = od.id_sp
             GROUP BY p.sp_id, p.sp_ma, p.sp_name
             ORDER BY so_luong_ban DESC
             LIMIT 10'
        );
    }
}
