<?php
function load_all_product()
{
    $sql = "select * from product";
    $list_product = pdo_query($sql);
    return $list_product;
} //trả về danh sách sản phẩm

function load_one_product($sp_id)
{
    return pdo_query_one("SELECT * FROM product WHERE sp_id=?", $sp_id);
} //trả về 1 sản phẩm khi tìm kiếm

function insert_product($sp_name, $sp_image, $sp_price, $sp_quantity, $sp_describe, $id_dm, $sp_ma, $sp_pricedel)
{
    pdo_execute("INSERT INTO product(sp_name,sp_image,sp_price,sp_quantity,sp_describe,id_dm,sp_ma,sp_pricedel) VALUES(?,?,?,?,?,?,?,?)",$sp_name,$sp_image,$sp_price,$sp_quantity,$sp_describe,$id_dm,$sp_ma,$sp_pricedel);
} //thêm mới sản phẩm

function delete_product($sp_id)
{
    pdo_execute("DELETE FROM product WHERE sp_id=?", $sp_id);
} //xóa sản phẩm


function update_product($sp_id, $sp_name, $sp_image, $sp_price, $sp_quantity, $sp_describe, $id_dm, $sp_pricedel)
{
    pdo_execute("UPDATE product SET sp_name=?,sp_image=?,sp_price=?,sp_quantity=?,sp_describe=?,sp_pricedel=?,id_dm=? WHERE sp_id=?",$sp_name,$sp_image,$sp_price,$sp_quantity,$sp_describe,$sp_pricedel,$id_dm,$sp_id);
} //cập nhật sản phẩm


// Biến thể sản phẩm


function load_all_product_color()
{
    $sql = "select * from productcolor";
    $list_product_color = pdo_query($sql);
    return $list_product_color;
}
function load_one_product_color($pc_id)
{
    return pdo_query_one("SELECT * FROM productcolor WHERE pc_id=?", $pc_id);
}
function update_product_color($pc_id, $pc_name, $id_sp)
{
    pdo_execute("UPDATE productcolor SET pc_name=?,id_sp=? WHERE pc_id=?",$pc_name,$id_sp,$pc_id);
}
function insert_product_color($pc_name, $id_sp)
{
    pdo_execute("INSERT INTO productcolor(pc_name,id_sp) VALUES(?,?)",$pc_name,$id_sp);
}
function delete_product_color($pc_id)
{
    pdo_execute("DELETE FROM productcolor WHERE pc_id=?",$pc_id);
}
function load_all_product_memory()
{
    $sql = "select * from productmemory";
    $list_product_memory = pdo_query($sql);
    return $list_product_memory;
}
function load_one_product_memory($pm_id)
{
    return pdo_query_one("SELECT * FROM productmemory WHERE pm_id=?",$pm_id);
}

function insert_product_memory($pm_name, $id_sp)
{
    pdo_execute("INSERT INTO productmemory(pm_name,id_sp) VALUES(?,?)",$pm_name,$id_sp);
}
function update_product_memory($pm_id, $pm_name, $id_sp)
{
    pdo_execute("UPDATE productmemory SET pm_name=?,id_sp=? WHERE pm_id=?",$pm_name,$id_sp,$pm_id);
}

function delete_product_memory($pm_id)
{
    pdo_execute("DELETE FROM productmemory WHERE pm_id=?",$pm_id);
} //xóa sản phẩm
function top_product_selling()
{
    $sql = "SELECT 
    p.sp_id AS id_sp,
    p.sp_ma AS sp_ma,
    p.sp_name AS ten_sp, 
    SUM(od.ct_quantity) AS so_luong_ban, 
    SUM(od.ct_quantity * p.sp_price) AS doanh_thu
FROM 
    product p
INNER JOIN 
    orderdetail od
ON 
    p.sp_id = od.id_sp
GROUP BY 
    p.sp_id, p.sp_name
ORDER BY 
    so_luong_ban DESC
LIMIT 10;

";
    return pdo_query($sql);
}
