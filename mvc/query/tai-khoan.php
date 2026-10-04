<?php
function load_all_account()
{
    return pdo_query(
        'SELECT a.*, r.role_name
         FROM account a
         JOIN role r ON a.id_role = r.role_id'
    );
}

function load_all_role()
{
    return pdo_query('SELECT * FROM role');
}

function load_one_account($id)
{
    return pdo_query_one('SELECT * FROM account WHERE tk_id = ?', $id);
}

function find_account_by_username($username)
{
    return pdo_query_one(
        'SELECT * FROM account WHERE tk_user = ? LIMIT 1',
        $username
    );
}

function check_duplicate_account($username, $excludeId = null)
{
    if ($excludeId !== null) {
        return (int) pdo_query_value(
            'SELECT COUNT(*) FROM account WHERE tk_user = ? AND tk_id <> ?',
            $username,
            $excludeId
        ) > 0;
    }

    return (int) pdo_query_value(
        'SELECT COUNT(*) FROM account WHERE tk_user = ?',
        $username
    ) > 0;
}

function check_duplicate_email($email, $excludeId = null)
{
    if ($excludeId !== null) {
        return (int) pdo_query_value(
            'SELECT COUNT(*) FROM account WHERE tk_email = ? AND tk_id <> ?',
            $email,
            $excludeId
        ) > 0;
    }

    return (int) pdo_query_value(
        'SELECT COUNT(*) FROM account WHERE tk_email = ?',
        $email
    ) > 0;
}

function insert_account($username, $password, $email, $address, $roleId)
{
    pdo_execute(
        'INSERT INTO account(tk_user, tk_password, tk_email, tk_address, id_role)
         VALUES (?, ?, ?, ?, ?)',
        $username,
        password_hash($password, PASSWORD_BCRYPT),
        $email,
        $address,
        $roleId
    );
}

function update_account($id, $username, $password, $email, $address, $roleId)
{
    if ($password === '') {
        pdo_execute(
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

    pdo_execute(
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

function delete_account($id)
{
    pdo_execute('DELETE FROM account WHERE tk_id = ?', $id);
}

function verify_account_password(array $account, $password)
{
    $storedPassword = (string) ($account['tk_password'] ?? '');

    if ($storedPassword === '') {
        return false;
    }

    $isHashed = (password_get_info($storedPassword)['algoName'] ?? 'unknown') !== 'unknown';

    if ($isHashed) {
        $valid = password_verify($password, $storedPassword);

        if ($valid && password_needs_rehash($storedPassword, PASSWORD_BCRYPT)) {
            pdo_execute(
                'UPDATE account SET tk_password = ? WHERE tk_id = ? AND tk_password = ?',
                password_hash($password, PASSWORD_BCRYPT),
                $account['tk_id'],
                $storedPassword
            );
        }

        return $valid;
    }

    // Hỗ trợ tạm tài khoản cũ đang lưu mật khẩu thường.
    if (!hash_equals($storedPassword, $password)) {
        return false;
    }

    pdo_execute(
        'UPDATE account SET tk_password = ? WHERE tk_id = ? AND tk_password = ?',
        password_hash($password, PASSWORD_BCRYPT),
        $account['tk_id'],
        $storedPassword
    );

    return true;
}
