<?php
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
                $list_role = load_all_role();
                $errors = [];
                $thongBao = '';

                if (isset($_POST['submit'])) {
                    $user = trim((string) ($_POST['user'] ?? ''));
                    $pass = (string) ($_POST['pass'] ?? '');
                    $email = trim((string) ($_POST['email'] ?? ''));
                    $address = trim((string) ($_POST['address'] ?? ''));
                    $id_role = trim((string) ($_POST['id_role'] ?? ''));

                    if ($user === '') {
                        $errors['user'] = 'Vui lòng điền tên tài khoản!';
                    } elseif (strlen($user) < 5) {
                        $errors['user'] = 'Tên tài khoản phải dài ít nhất 5 ký tự!';
                    } elseif (check_duplicate_account($user)) {
                        $errors['user'] = 'Tên tài khoản đã tồn tại!';
                    }

                    if (strlen($pass) < 6) {
                        $errors['pass'] = 'Mật khẩu phải dài ít nhất 6 ký tự!';
                    }

                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $errors['email'] = 'Địa chỉ email không hợp lệ!';
                    } elseif (check_duplicate_email($email)) {
                        $errors['email'] = 'Email đã tồn tại!';
                    }

                    if ($address === '') {
                        $errors['address'] = 'Vui lòng điền địa chỉ!';
                    }

                    if ($id_role === '') {
                        $errors['id_role'] = 'Vui lòng chọn role cho tài khoản!';
                    }

                    if (!$errors) {
                        insert_account($user, $pass, $email, $address, $id_role);
                        $thongBao = 'Thêm thành công!';
                        header("Refresh: 1.5; url='?act=admin&admin=accountList'");
                    }
                }

                include 'view/admin/account/add.php';
                break;

            case 'accountUpdate':
                $list_role = load_all_role();
                $account = load_one_account($id);

                if (!$account) {
                    http_response_code(404);
                    exit('Tài khoản không tồn tại.');
                }

                $id_role = $account['id_role'];
                $errors = [];
                $thongBao = '';

                if (isset($_POST['submit'])) {
                    $user = trim((string) ($_POST['user'] ?? ''));
                    $pass = (string) ($_POST['pass'] ?? '');
                    $email = trim((string) ($_POST['email'] ?? ''));
                    $address = trim((string) ($_POST['address'] ?? ''));
                    $id_role = trim((string) ($_POST['id_role'] ?? ''));

                    if ($user === '') {
                        $errors['user'] = 'Vui lòng điền tên tài khoản!';
                    } elseif (strlen($user) < 5) {
                        $errors['user'] = 'Tên tài khoản phải dài ít nhất 5 ký tự!';
                    } elseif (check_duplicate_account($user, $id)) {
                        $errors['user'] = 'Tên tài khoản đã tồn tại!';
                    }

                    if ($pass !== '' && strlen($pass) < 6) {
                        $errors['pass'] = 'Mật khẩu phải dài ít nhất 6 ký tự!';
                    }

                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $errors['email'] = 'Địa chỉ email không hợp lệ!';
                    } elseif (check_duplicate_email($email, $id)) {
                        $errors['email'] = 'Email đã tồn tại!';
                    }

                    if ($address === '') {
                        $errors['address'] = 'Vui lòng điền địa chỉ!';
                    }

                    if ($id_role === '') {
                        $errors['id_role'] = 'Vui lòng chọn role cho tài khoản!';
                    }

                    if (!$errors) {
                        update_account($id, $user, $pass, $email, $address, $id_role);
                        $account = load_one_account($id);
                        $thongBao = 'Sửa thành công!';
                        header("Refresh: 1.5; url='?act=admin&admin=accountList'");
                    }
                }

                include 'view/admin/account/update.php';
                break;

            case 'accountDelete':
                delete_account($id);
                header('Location: ?act=admin&admin=accountList');
                exit;
        }
    }
}
