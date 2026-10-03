<?php
function require_admin() {
    $id=$_SESSION['user_id']??null;
    if (!is_scalar($id)||filter_var($id,FILTER_VALIDATE_INT)===false) {
        header('Location: ?client=login');
        exit;
    }
    $account=load_one_account((int)$id);
    if (!$account||(int)$account['id_role']!==1) {
        http_response_code(403);
        exit('Không có quyền truy cập trang quản trị.');
    }
    $_SESSION['role_id']=1;
}
