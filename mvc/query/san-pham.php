<?php
function load_all_product()
{
    return pdo_query('SELECT * FROM product');
}

function load_one_product($id)
{
    return pdo_query_one('SELECT * FROM product WHERE sp_id = ?', $id);
}

function insert_product($name, $image, $price, $quantity, $description, $categoryId, $code, $oldPrice)
{
    pdo_execute(
        'INSERT INTO product(
            sp_name, sp_image, sp_price, sp_quantity,
            sp_describe, id_dm, sp_ma, sp_pricedel
         ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
        $name,
        $image,
        $price,
        $quantity,
        $description,
        $categoryId,
        $code,
        $oldPrice
    );
}

function update_product($id, $name, $image, $price, $quantity, $description, $categoryId, $oldPrice)
{
    pdo_execute(
        'UPDATE product
         SET sp_name = ?, sp_image = ?, sp_price = ?, sp_quantity = ?,
             sp_describe = ?, sp_pricedel = ?, id_dm = ?
         WHERE sp_id = ?',
        $name,
        $image,
        $price,
        $quantity,
        $description,
        $oldPrice,
        $categoryId,
        $id
    );
}

function delete_product($id)
{
    pdo_execute('DELETE FROM product WHERE sp_id = ?', $id);
}

function load_all_product_color()
{
    return pdo_query('SELECT * FROM productcolor');
}

function load_one_product_color($id)
{
    return pdo_query_one('SELECT * FROM productcolor WHERE pc_id = ?', $id);
}

function insert_product_color($name, $productId)
{
    pdo_execute(
        'INSERT INTO productcolor(pc_name, id_sp) VALUES (?, ?)',
        $name,
        $productId
    );
}

function update_product_color($id, $name, $productId)
{
    pdo_execute(
        'UPDATE productcolor SET pc_name = ?, id_sp = ? WHERE pc_id = ?',
        $name,
        $productId,
        $id
    );
}

function delete_product_color($id)
{
    pdo_execute('DELETE FROM productcolor WHERE pc_id = ?', $id);
}

function load_all_product_memory()
{
    return pdo_query('SELECT * FROM productmemory');
}

function load_one_product_memory($id)
{
    return pdo_query_one('SELECT * FROM productmemory WHERE pm_id = ?', $id);
}

function insert_product_memory($name, $productId)
{
    pdo_execute(
        'INSERT INTO productmemory(pm_name, id_sp) VALUES (?, ?)',
        $name,
        $productId
    );
}

function update_product_memory($id, $name, $productId)
{
    pdo_execute(
        'UPDATE productmemory SET pm_name = ?, id_sp = ? WHERE pm_id = ?',
        $name,
        $productId,
        $id
    );
}

function delete_product_memory($id)
{
    pdo_execute('DELETE FROM productmemory WHERE pm_id = ?', $id);
}

function top_product_selling()
{
    return pdo_query(
        'SELECT
            p.sp_id AS id_sp,
            p.sp_ma,
            p.sp_name AS ten_sp,
            SUM(od.ct_quantity) AS so_luong_ban,
            SUM(od.ct_quantity * p.sp_price) AS doanh_thu
         FROM product p
         INNER JOIN orderdetail od ON p.sp_id = od.id_sp
         GROUP BY p.sp_id, p.sp_ma, p.sp_name
         ORDER BY so_luong_ban DESC
         LIMIT 10'
    );
}
