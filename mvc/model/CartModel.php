<?php
final class CartModel
{
    public static function all(): array
    {
        return Database::query('SELECT * FROM cart');
    }

    public static function details(): array
    {
        return Database::query('SELECT * FROM cartdetail');
    }

    public static function findForUser($userId)
    {
        return Database::one(
            'SELECT * FROM cart WHERE id_tk = ? LIMIT 1',
            $userId
        );
    }

    public static function detailsForCart($cartId): array
    {
        return Database::query(
            'SELECT * FROM cartdetail WHERE id_gh = ?',
            $cartId
        );
    }

    public static function create($userId): void
    {
        Database::execute('INSERT INTO cart(id_tk) VALUES (?)', $userId);
    }

    public static function addItem($cartId, $productId, string $memory, string $color): void
    {
        Database::execute(
            'INSERT INTO cartdetail(id_gh, id_sp, cd_option, cd_optionColor)
             VALUES (?, ?, ?, ?)',
            $cartId,
            $productId,
            $memory,
            $color
        );
    }

    public static function increaseItem($id): void
    {
        Database::execute(
            'UPDATE cartdetail SET cd_quantity = cd_quantity + 1 WHERE cd_id = ?',
            $id
        );
    }

    public static function decreaseItem($id): void
    {
        Database::execute(
            'UPDATE cartdetail
             SET cd_quantity = cd_quantity - 1
             WHERE cd_id = ? AND cd_quantity > 1',
            $id
        );
    }

    public static function deleteItem($id): void
    {
        Database::execute('DELETE FROM cartdetail WHERE cd_id = ?', $id);
    }

    public static function findIdForUser($userId)
    {
        $id = Database::value(
            'SELECT gh_id FROM cart WHERE id_tk = ? LIMIT 1',
            $userId
        );

        return $id === false ? null : (int) $id;
    }

    public static function belongsToUser($cartId, $userId): bool
    {
        return (int) Database::value(
            'SELECT COUNT(*) FROM cart WHERE gh_id = ? AND id_tk = ?',
            $cartId,
            $userId
        ) > 0;
    }

    public static function detailBelongsToUser($detailId, $userId): bool
    {
        return (int) Database::value(
            'SELECT COUNT(*)
             FROM cartdetail d
             JOIN cart c ON c.gh_id = d.id_gh
             WHERE d.cd_id = ? AND c.id_tk = ?',
            $detailId,
            $userId
        ) > 0;
    }

    public static function itemsByIdsForUser($userId, array $ids, bool $lock = false): array
    {
        if (!$ids) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "SELECT d.*, p.sp_price
                FROM cartdetail d
                JOIN cart c ON c.gh_id = d.id_gh
                JOIN product p ON p.sp_id = d.id_sp
                WHERE c.id_tk = ? AND d.cd_id IN ($placeholders)";

        if ($lock) {
            $sql .= ' FOR UPDATE';
        }

        return Database::query($sql, ...array_merge([$userId], $ids));
    }
}
