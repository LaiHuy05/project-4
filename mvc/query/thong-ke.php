<?php
function count_product()
{
    return pdo_query('SELECT COUNT(*) FROM product');
}

function count_category()
{
    return pdo_query('SELECT COUNT(*) FROM category');
}

function count_account()
{
    return pdo_query('SELECT COUNT(*) FROM account');
}

function count_order()
{
    return pdo_query('SELECT COUNT(*) FROM `order`');
}

function count_profit()
{
    return pdo_query('SELECT SUM(dh_totalamount) AS tong_gia FROM `order`');
}
