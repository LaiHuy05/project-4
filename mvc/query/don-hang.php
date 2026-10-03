<?php
function load_all_order()
{
    $sql = "SELECT * FROM `order`;";
    $list_order = pdo_query($sql);
    return $list_order;
} //trả về danh sách đơn hàng

function load_one_order($dh_id)
{
    return pdo_query_one("SELECT * FROM `order` WHERE dh_id=?", $dh_id);
} //trả về 1 đơn hàng khi tìm kiếm
function load_one_order_totalamount($dh_totalamount)
{
    return pdo_query_one("SELECT * FROM `order` WHERE dh_totalamount=?", $dh_totalamount);
} //trả về 1 đơn hàng khi tìm kiếm tổng tiền
function insert_order($namePay, $emailPay, $phonePay, $addressPay, $countryPay, $cityPay, $districtPay, $communePay, $messagePay, $dh_status, $dh_totalamount, $id_tk, $sp_quantity, $dh_ma)
{
    $sql = "INSERT INTO `order`(`dh_nameUser`, `dh_emailUser`, `dh_phoneUser`, `dh_addressUser`, `dh_countryPay`, `dh_cityPay`, `dh_districtPay`, `dh_communePay`, `dh_messagePay`, `dh_orderdate`, `dh_status`, `dh_totalamount`, `id_tk`, `sp_quantity`, `dh_ma`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?)";

    // Sử dụng hàm trả về ID
    return pdo_execute_return_last_insert_id(
        $sql,
        $namePay,
        $emailPay,
        $phonePay,
        $addressPay,
        $countryPay,
        $cityPay,
        $districtPay,
        $communePay,
        $messagePay,
        $dh_status,
        $dh_totalamount,
        $id_tk,
        $sp_quantity,
        $dh_ma
    );
}
//thêm mới đơn hàng

function delete_order($dh_id)
{
    pdo_execute("DELETE FROM `order` WHERE dh_id=?", $dh_id);
}
//xóa đơn hàng


function update_order($dh_id, $dh_status)
{
    pdo_execute("UPDATE `order` SET dh_status=? WHERE dh_id=?",$dh_status,$dh_id);
}
//cập nhật đơn hàng
function load_all_orderdetail()
{
    $sql = "SELECT * FROM `orderdetail`;";
    $list_orderdetail = pdo_query($sql);
    return $list_orderdetail;
} //trả về danh sách đơn hàng
function insert_orderdetail($id_dh, $id_sp, $ct_quantity, $od_option, $od_optionColor)
{
    pdo_execute("INSERT INTO orderdetail(id_dh,id_sp,ct_quantity,od_option,od_optionColor) VALUES(?,?,?,?,?)",$id_dh,$id_sp,$ct_quantity,$od_option,$od_optionColor);
} //trả về danh sách đơn hàng

function pdo_execute_return_last_insert_id($sql, ...$args)
{
    $conn=pdo_get_connection();
    $stmt=$conn->prepare($sql);
    $stmt->execute($args);
    return $conn->lastInsertId();
}
function report_totalamount()
{
    $sql = "SELECT 
    MONTH(dh_orderdate) AS thang,
    YEAR(dh_orderdate) AS nam,
    SUM(dh_totalamount) AS tong_doanh_thu
FROM 
    `order`
WHERE 
    YEAR(dh_orderdate) = 2024
GROUP BY 
    YEAR(dh_orderdate), MONTH(dh_orderdate)
ORDER BY 
    YEAR(dh_orderdate) ASC, MONTH(dh_orderdate) ASC;
";
    return pdo_query($sql);
}

function load_orders_for_user($userId)
{
    return pdo_query("SELECT * FROM `order` WHERE id_tk=? ORDER BY dh_orderdate DESC",$userId);
}
function load_order_details_for_user($userId)
{
    return pdo_query("SELECT d.* FROM orderdetail d JOIN `order` o ON o.dh_id=d.id_dh WHERE o.id_tk=?",$userId);
}
