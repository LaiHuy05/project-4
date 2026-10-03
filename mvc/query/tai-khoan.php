<?php
function load_all_account()
{
    return pdo_query("SELECT a.*, r.role_name FROM account a JOIN role r ON a.id_role = r.role_id");
}
function load_all_role() { return pdo_query("SELECT * FROM role"); }
function load_one_account($id) { return pdo_query_one("SELECT * FROM account WHERE tk_id = ?", $id); }
function find_account_by_username($username) { return pdo_query_one("SELECT * FROM account WHERE tk_user = ? LIMIT 1", $username); }
function check_duplicate_account($username, $excludeId = null)
{
    if ($excludeId !== null) return (int) pdo_query_value("SELECT COUNT(*) FROM account WHERE tk_user = ? AND tk_id <> ?", $username, $excludeId) > 0;
    return (int) pdo_query_value("SELECT COUNT(*) FROM account WHERE tk_user = ?", $username) > 0;
}
function check_duplicate_email($email, $excludeId = null)
{
    if ($excludeId !== null) return (int) pdo_query_value("SELECT COUNT(*) FROM account WHERE tk_email = ? AND tk_id <> ?", $email, $excludeId) > 0;
    return (int) pdo_query_value("SELECT COUNT(*) FROM account WHERE tk_email = ?", $email) > 0;
}
function insert_account($user, $pass, $email, $address, $role)
{
    pdo_execute("INSERT INTO account(tk_user,tk_password,tk_email,tk_address,id_role) VALUES (?,?,?,?,?)",
        $user, password_hash($pass, PASSWORD_BCRYPT), $email, $address, $role);
}
function update_account($id, $user, $pass, $email, $address, $role)
{
    if ($pass === '') {
        pdo_execute("UPDATE account SET tk_user=?,tk_email=?,tk_address=?,id_role=? WHERE tk_id=?",
            $user,$email,$address,$role,$id);
    } else {
        pdo_execute("UPDATE account SET tk_user=?,tk_password=?,tk_email=?,tk_address=?,id_role=? WHERE tk_id=?",
            $user,password_hash($pass,PASSWORD_BCRYPT),$email,$address,$role,$id);
    }
}
function delete_account($id) { pdo_execute("DELETE FROM account WHERE tk_id=?", $id); }
/** Temporary legacy plaintext migration. Remove after all accounts are migrated. */
function verify_account_password(array $account, $password)
{
    $stored = (string) ($account['tk_password'] ?? '');
    if ($stored === '') return false;
    if ((password_get_info($stored)['algoName'] ?? 'unknown') !== 'unknown') {
        $valid = password_verify($password, $stored);
        if ($valid && password_needs_rehash($stored, PASSWORD_BCRYPT)) {
            pdo_execute("UPDATE account SET tk_password=? WHERE tk_id=? AND tk_password=?",
                password_hash($password,PASSWORD_BCRYPT),$account['tk_id'],$stored);
        }
        return $valid;
    }
    if (!hash_equals($stored, $password)) return false;
    pdo_execute("UPDATE account SET tk_password=? WHERE tk_id=? AND tk_password=?",
        password_hash($password,PASSWORD_BCRYPT),$account['tk_id'],$stored);
    return true;
}
