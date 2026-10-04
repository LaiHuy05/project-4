<?php
function load_all_comment()
{
    return pdo_query(
        'SELECT c.*, a.tk_user, p.sp_name
         FROM comment c
         JOIN account a ON c.id_tk = a.tk_id
         JOIN product p ON c.id_sp = p.sp_id
         ORDER BY c.bl_id ASC'
    );
}

function insert_comment($content, $accountId, $productId)
{
    pdo_execute(
        'INSERT INTO comment(bl_content, id_tk, id_sp) VALUES (?, ?, ?)',
        $content,
        $accountId,
        $productId
    );
}

function delete_comment($id)
{
    pdo_execute('DELETE FROM comment WHERE bl_id = ?', $id);
}
