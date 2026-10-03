<?php
function load_all_cart() { return pdo_query("SELECT * FROM cart"); }
function insert_cart($userId) { pdo_execute("INSERT INTO cart(id_tk) VALUES(?)", $userId); }
function addOneCartdetail($id) { pdo_execute("UPDATE cartdetail SET cd_quantity=cd_quantity+1 WHERE cd_id=?", $id); }
function lossOneCartdetail($id) { pdo_execute("UPDATE cartdetail SET cd_quantity=cd_quantity-1 WHERE cd_id=? AND cd_quantity>1",$id); }
function delete_cartdetail($id) { pdo_execute("DELETE FROM cartdetail WHERE cd_id=?", $id); }
function insert_cartDetail($cartId,$productId,$memory,$color)
{
    pdo_execute("INSERT INTO cartdetail(id_gh,id_sp,cd_option,cd_optionColor) VALUES(?,?,?,?)",
        $cartId,$productId,$memory,$color);
}
function load_all_cartDetail() { return pdo_query("SELECT * FROM cartdetail"); }
function cart_belongs_to_user($cartId,$userId)
{
    return (int) pdo_query_value("SELECT COUNT(*) FROM cart WHERE gh_id=? AND id_tk=?",$cartId,$userId)>0;
}
function cartdetail_belongs_to_user($detailId,$userId)
{
    return (int) pdo_query_value("SELECT COUNT(*) FROM cartdetail d JOIN cart c ON c.gh_id=d.id_gh WHERE d.cd_id=? AND c.id_tk=?",$detailId,$userId)>0;
}
function load_cart_items_by_ids_for_user($userId,array $ids,$lock=false)
{
    if (!$ids) return [];
    $marks=implode(',',array_fill(0,count($ids),'?'));
    $sql="SELECT d.*,p.sp_price FROM cartdetail d JOIN cart c ON c.gh_id=d.id_gh
         JOIN product p ON p.sp_id=d.id_sp WHERE c.id_tk=? AND d.cd_id IN ($marks)";
    if ($lock) $sql.=" FOR UPDATE";
    return pdo_query($sql,...array_merge([$userId],$ids));
}

function find_cart_id_for_user($userId)
{
    $id = pdo_query_value("SELECT gh_id FROM cart WHERE id_tk=? LIMIT 1", $userId);
    return $id === false ? null : (int)$id;
}
