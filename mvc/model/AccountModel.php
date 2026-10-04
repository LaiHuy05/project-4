<?php
final class AccountModel
{
    public static function all(): array
    {
        return Database::query(
            'SELECT a.*, r.role_name
             FROM account a
             JOIN role r ON a.id_role = r.role_id'
        );
    }

    public static function roles(): array
    {
        return Database::query('SELECT * FROM role');
    }

    public static function find($id)
    {
        return Database::one('SELECT * FROM account WHERE tk_id = ?', $id);
    }

    public static function findByUsername(string $username)
    {
        return Database::one(
            'SELECT * FROM account WHERE tk_user = ? LIMIT 1',
            $username
        );
    }

    public static function usernameExists(string $username, $excludeId = null): bool
    {
        if ($excludeId !== null) {
            return (int) Database::value(
                'SELECT COUNT(*) FROM account WHERE tk_user = ? AND tk_id <> ?',
                $username,
                $excludeId
            ) > 0;
        }

        return (int) Database::value(
            'SELECT COUNT(*) FROM account WHERE tk_user = ?',
            $username
        ) > 0;
    }

    public static function emailExists(string $email, $excludeId = null): bool
    {
        if ($excludeId !== null) {
            return (int) Database::value(
                'SELECT COUNT(*) FROM account WHERE tk_email = ? AND tk_id <> ?',
                $email,
                $excludeId
            ) > 0;
        }

        return (int) Database::value(
            'SELECT COUNT(*) FROM account WHERE tk_email = ?',
            $email
        ) > 0;
    }

    public static function create(
        string $username,
        string $password,
        string $email,
        string $address,
        $roleId
    ): void {
        Database::execute(
            'INSERT INTO account(tk_user, tk_password, tk_email, tk_address, id_role)
             VALUES (?, ?, ?, ?, ?)',
            $username,
            password_hash($password, PASSWORD_BCRYPT),
            $email,
            $address,
            $roleId
        );
    }

    public static function update(
        $id,
        string $username,
        string $password,
        string $email,
        string $address,
        $roleId
    ): void {
        if ($password === '') {
            Database::execute(
                'UPDATE account
                 SET tk_user = ?, tk_email = ?, tk_address = ?, id_role = ?
                 WHERE tk_id = ?',
                $username,
                $email,
                $address,
                $roleId,
                $id
            );
            return;
        }

        Database::execute(
            'UPDATE account
             SET tk_user = ?, tk_password = ?, tk_email = ?, tk_address = ?, id_role = ?
             WHERE tk_id = ?',
            $username,
            password_hash($password, PASSWORD_BCRYPT),
            $email,
            $address,
            $roleId,
            $id
        );
    }

    public static function delete($id): void
    {
        Database::execute('DELETE FROM account WHERE tk_id = ?', $id);
    }

    public static function verifyPassword(array $account, string $password): bool
    {
        $storedPassword = (string) ($account['tk_password'] ?? '');

        if ($storedPassword === '') {
            return false;
        }

        $isHashed = (password_get_info($storedPassword)['algoName'] ?? 'unknown') !== 'unknown';

        if ($isHashed) {
            $valid = password_verify($password, $storedPassword);

            if ($valid && password_needs_rehash($storedPassword, PASSWORD_BCRYPT)) {
                self::replacePasswordHash(
                    $account['tk_id'],
                    $storedPassword,
                    password_hash($password, PASSWORD_BCRYPT)
                );
            }

            return $valid;
        }

        // Tương thích tạm với tài khoản cũ còn lưu mật khẩu thường.
        if (!hash_equals($storedPassword, $password)) {
            return false;
        }

        self::replacePasswordHash(
            $account['tk_id'],
            $storedPassword,
            password_hash($password, PASSWORD_BCRYPT)
        );

        return true;
    }

    private static function replacePasswordHash($id, string $oldValue, string $newValue): void
    {
        Database::execute(
            'UPDATE account SET tk_password = ? WHERE tk_id = ? AND tk_password = ?',
            $newValue,
            $id,
            $oldValue
        );
    }
}
