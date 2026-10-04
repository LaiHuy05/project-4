<?php
function pdo_get_connection()
{
    static $connection = null;

    if ($connection instanceof PDO) {
        return $connection;
    }

    $host = getenv('DB_HOST') ?: 'localhost';
    $name = getenv('DB_NAME') ?: 'duan1';
    $user = getenv('DB_USER') !== false ? getenv('DB_USER') : 'root';
    $password = getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : '';

    $connection = new PDO(
        "mysql:host={$host};dbname={$name};charset=utf8mb4",
        $user,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_BOTH,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );

    return $connection;
}

function pdo_execute($sql, ...$args)
{
    $statement = pdo_get_connection()->prepare($sql);
    $statement->execute($args);
}

function pdo_insert($sql, ...$args)
{
    $connection = pdo_get_connection();
    $statement = $connection->prepare($sql);
    $statement->execute($args);

    return $connection->lastInsertId();
}

function pdo_query($sql, ...$args)
{
    $statement = pdo_get_connection()->prepare($sql);
    $statement->execute($args);

    return $statement->fetchAll();
}

function pdo_query_one($sql, ...$args)
{
    $statement = pdo_get_connection()->prepare($sql);
    $statement->execute($args);

    return $statement->fetch(PDO::FETCH_ASSOC);
}

function pdo_query_value($sql, ...$args)
{
    $statement = pdo_get_connection()->prepare($sql);
    $statement->execute($args);

    return $statement->fetchColumn();
}

function pdo_transaction(callable $callback)
{
    $connection = pdo_get_connection();

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
