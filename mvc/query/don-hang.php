<?php
function load_all_order()
{
    return pdo_query('SELECT * FROM `order`');
}

function load_one_order($orderId)
{
    return pdo_query_one('SELECT * FROM `order` WHERE dh_id = ?', $orderId);
}

function insert_order(
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
) {
    $sql = 'INSERT INTO `order` (
        dh_nameUser, dh_emailUser, dh_phoneUser, dh_addressUser,
        dh_countryPay, dh_cityPay, dh_districtPay, dh_communePay,
        dh_messagePay, dh_orderdate, dh_status, dh_totalamount,
        id_tk, sp_quantity, dh_ma
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?)';

    return pdo_insert(
        $sql,
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

function delete_order($orderId)
{
    pdo_execute('DELETE FROM `order` WHERE dh_id = ?', $orderId);
}

function update_order($orderId, $status)
{
    pdo_execute('UPDATE `order` SET dh_status = ? WHERE dh_id = ?', $status, $orderId);
}

function load_all_orderdetail()
{
    return pdo_query('SELECT * FROM orderdetail');
}

function insert_orderdetail($orderId, $productId, $quantity, $memory, $color)
{
    pdo_execute(
        'INSERT INTO orderdetail(id_dh, id_sp, ct_quantity, od_option, od_optionColor)
         VALUES (?, ?, ?, ?, ?)',
        $orderId,
        $productId,
        $quantity,
        $memory,
        $color
    );
}

function load_orders_for_user($userId)
{
    return pdo_query(
        'SELECT * FROM `order` WHERE id_tk = ? ORDER BY dh_orderdate DESC',
        $userId
    );
}

function load_order_details_for_user($userId)
{
    return pdo_query(
        'SELECT d.*
         FROM orderdetail d
         JOIN `order` o ON o.dh_id = d.id_dh
         WHERE o.id_tk = ?',
        $userId
    );
}
