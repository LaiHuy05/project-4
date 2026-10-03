<?php
function load_all_comment()
{
    return pdo_query("SELECT a.*,b.tk_user,c.sp_name FROM comment a
        JOIN account b ON a.id_tk=b.tk_id JOIN product c ON a.id_sp=c.sp_id ORDER BY a.bl_id ASC");
}
function delete_comment($id) { pdo_execute("DELETE FROM comment WHERE bl_id=?", $id); }
function insert_comment($content,$accountId,$productId)
{
    pdo_execute("INSERT INTO comment(bl_content,id_tk,id_sp) VALUES(?,?,?)",$content,$accountId,$productId);
}
