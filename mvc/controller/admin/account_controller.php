<?php
/**
 * Admin account actions.
 * Database functions remain in mvc/query during this behavior-preserving refactor.
 */
final class AdminAccountController
{
    public static function handle(string $admin, $id = null): void
    {
        switch ($admin) {
            case 'accountList':
                $list_account = load_all_account();
                include 'view/admin/account/list.php';
                break;
            case 'accountAdd':
                $list_account = load_all_account();
                $list_role = load_all_role();
        
                $errors = [];
                $thongBao = '';
        
                if (isset($_POST['submit'])) {
                    $user = trim($_POST['user']);
                    $pass = trim($_POST['pass']);
                    $email = trim($_POST['email']);
                    $address = trim($_POST['address']);
                    $id_role = trim($_POST['id_role']);
        
                    if ($user == '') {
                        $errors['user'] = 'Vui lòng điền tên tài khoản!';
                    } elseif (check_duplicate_account($user)) {
                        $errors['user'] = 'Tên tài khoản đã tồn tại!';
                    } elseif (strlen($user) < 5) {
                        $errors['user'] = 'Tên tài khoản phải dài ít nhất 5 ký tự!';
                    }
        
                    if ($pass == '') {
                        $errors['pass'] = 'Vui lòng điền mật khẩu!';
                    } elseif (strlen($pass) < 6) {
                        $errors['pass'] = 'Mật khẩu phải dài ít nhất 6 ký tự!';
                    }
        
                    if ($email == '') {
                        $errors['email'] = 'Vui lòng điền email!';
                    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $errors['email'] = 'Địa chỉ email không hợp lệ!';
                    }
        
                    if ($address == '') {
                        $errors['address'] = 'Vui lòng điền địa chỉ!';
                    }
        
                    if ($id_role == '') {
                        $errors['id_role'] = 'Vui lòng chọn role cho tài khoản!';
                    }
        
                    if (empty($errors)) {
                        insert_account($user, $pass, $email, $address, $id_role);
                        $thongBao = "Thêm thành công!";
                        header("Refresh: 1.5; url='?act=admin&admin=accountList'");
                    }
                }
                include 'view/admin/account/add.php';
                break;
            case 'accountUpdate':
                $list_role = load_all_role();
                $id = $_GET['id'] ?? '';
        
                $account = load_one_account($id);
                $tk_user = $account['tk_user'];
                // Never reveal stored hashes.
                $tk_email = $account['tk_email'];
                $tk_address = $account['tk_address'];
                $id_role = $account['id_role'];
        
                $list_account = load_all_account();
                $thongBao = '';
                $errors = []; // Mảng lưu lỗi
                $name = load_one_account($id);
                if (isset($_POST['submit'])) {
                    $user = trim($_POST['user']);
                    $pass = trim($_POST['pass']);
                    $email = trim($_POST['email']);
                    $address = trim($_POST['address']);
                    $id_role = trim($_POST['id_role']);
                    if ($user == '') {
                        $errors['user'] = 'Vui lòng điền tên tài khoản!';
                    } elseif (check_duplicate_account($user, $id)) {
                        $errors['user'] = 'Tên tài khoản đã tồn tại!';
                    } elseif (strlen($user) < 5) {
                        $errors['user'] = 'Tên tài khoản phải dài ít nhất 5 ký tự!';
                    }
                    if ($pass !== '' && strlen($pass) < 6) {
                        $errors['pass'] = 'Mật khẩu phải dài ít nhất 6 ký tự!';
                    }
                    if ($email == '') {
                        $errors['email'] = 'Vui lòng điền email!';
                    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $errors['email'] = 'Địa chỉ email không hợp lệ!';
                    }
                    if ($address == '') {
                        $errors['address'] = 'Vui lòng điền địa chỉ!';
                    }
                    if ($id_role == '') {
                        $errors['id_role'] = 'Vui lòng chọn role cho tài khoản!';
                    }
                    if (empty($errors)) {
                        update_account($id, $user, $pass, $email, $address, $id_role);
                        $thongBao = "Sửa thành công!";
                        header("Refresh: 1.5; url='?act=admin&admin=accountList'");
                    }
                }
                include 'view/admin/account/update.php';
                // header("location: ?act=admin&admin=categoryList");
                break;
            case 'accountDelete':
                delete_account($id);
                header("location: ?act=admin&admin=accountList");
                break;
        }
    }
}
