<?php
final class CommentModel
{
    public static function all(): array
    {
        return Database::query(
            'SELECT c.*, a.tk_user, p.sp_name
             FROM comment c
             JOIN account a ON c.id_tk = a.tk_id
             JOIN product p ON c.id_sp = p.sp_id
             ORDER BY c.bl_id ASC'
        );
    }

    public static function create(string $content, $accountId, $productId): void
    {
        Database::execute(
            'INSERT INTO comment(bl_content, id_tk, id_sp) VALUES (?, ?, ?)',
            $content,
            $accountId,
            $productId
        );
    }

    public static function delete($id): void
    {
        Database::execute('DELETE FROM comment WHERE bl_id = ?', $id);
    }
}
