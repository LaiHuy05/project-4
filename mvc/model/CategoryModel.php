<?php
final class CategoryModel
{
    public static function all(): array
    {
        return Database::query('SELECT * FROM category');
    }

    public static function create(string $name): void
    {
        Database::execute('INSERT INTO category(dm_name) VALUES (?)', $name);
    }

    public static function update($id, string $name): void
    {
        Database::execute(
            'UPDATE category SET dm_name = ? WHERE dm_id = ?',
            $name,
            $id
        );
    }

    public static function delete($id): void
    {
        Database::execute('DELETE FROM category WHERE dm_id = ?', $id);
    }
}
