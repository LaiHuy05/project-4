<?php
final class Database
{
    private static $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $host = getenv('DB_HOST') ?: 'localhost';
        $name = getenv('DB_NAME') ?: 'duan1';
        $user = getenv('DB_USER') !== false ? getenv('DB_USER') : 'root';
        $password = getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : '';

        self::$connection = new PDO(
            "mysql:host={$host};dbname={$name};charset=utf8mb4",
            $user,
            $password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_BOTH,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );

        return self::$connection;
    }

    public static function execute(string $sql, ...$args): void
    {
        $statement = self::connection()->prepare($sql);
        $statement->execute($args);
    }

    public static function insert(string $sql, ...$args): string
    {
        $connection = self::connection();
        $statement = $connection->prepare($sql);
        $statement->execute($args);

        return (string) $connection->lastInsertId();
    }

    public static function query(string $sql, ...$args): array
    {
        $statement = self::connection()->prepare($sql);
        $statement->execute($args);

        return $statement->fetchAll();
    }

    public static function one(string $sql, ...$args)
    {
        $statement = self::connection()->prepare($sql);
        $statement->execute($args);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        return $row === false ? null : $row;
    }

    public static function value(string $sql, ...$args)
    {
        $statement = self::connection()->prepare($sql);
        $statement->execute($args);

        return $statement->fetchColumn();
    }

    public static function transaction(callable $callback)
    {
        $connection = self::connection();

        if ($connection->inTransaction()) {
            throw new LogicException('Nested transactions are not supported');
        }

        $connection->beginTransaction();

        try {
            $result = $callback($connection);
            $connection->commit();
            return $result;
        } catch (Throwable $error) {
            if ($connection->inTransaction()) {
                $connection->rollBack();
            }
            throw $error;
        }
    }
}
