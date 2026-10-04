<?php
require_once __DIR__ . '/../../core/Csrf.php';

final class ClientAuthController
{
    public static function handle(string $client, $iduser = null): void
    {
        $list_category = load_all_category();
        $list_account = [];

        if (isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id'])) {
            $currentAccount = load_one_account((int) $_SESSION['user_id']);
            if ($currentAccount) {
                $list_account[] = $currentAccount;
            }
        }

        switch ($client) {
            case 'login':
                self::login();
                break;

            case 'register':
                self::register();
                break;
        }
    }

    private static function login(): void
    {
        global $list_category, $list_account;

        $messtk = '';
        $messmk = '';
        $mess = '';
        $user = '';

        if (isset($_POST['btn-login'])) {
            if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
                http_response_code(403);
                exit('Phiên biểu mẫu không hợp lệ. Hãy tải lại trang.');
            }

            $user = trim((string) ($_POST['user'] ?? ''));
            $password = (string) ($_POST['password'] ?? '');

            if ($user === '') {
                $messtk = 'Tài khoản không được để trống';
            }
            if ($password === '') {
                $messmk = 'Mật khẩu không được để trống';
            }

            if ($messtk === '' && $messmk === '') {
                $account = find_account_by_username($user);

                if ($account && verify_account_password($account, $password)) {
                    session_regenerate_id(true);

                    $_SESSION['login'] = $account['tk_user'];
                    $_SESSION['user_id'] = (int) $account['tk_id'];
                    $_SESSION['role_id'] = (int) $account['id_role'];

                    if ((int) $account['id_role'] === 1) {
                        header('Location: ?act=admin');
                        exit;
                    }

                    header('Location: ?act=client&iduser=' . (int) $account['tk_id']);
                    exit;
                }

                $mess = 'Tài khoản hoặc mật khẩu sai!';
            }
        }

        include 'view/client/login/login.php';
    }

    private static function register(): void
    {
        global $list_category, $list_account;

        $messName = '';
        $messAddress = '';
        $messEmailRegister = '';
        $messPassword = '';
        $messConfirmPassword = '';
        $mess = '';

        $name = '';
        $address = '';
        $emailRegister = '';

        if (isset($_POST['btn-register'])) {
            if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
                http_response_code(403);
                exit('Phiên biểu mẫu không hợp lệ. Hãy tải lại trang.');
            }

            $name = trim((string) ($_POST['name'] ?? ''));
            $address = trim((string) ($_POST['address'] ?? ''));
            $emailRegister = trim((string) ($_POST['emailRegister'] ?? ''));
            $password = (string) ($_POST['password'] ?? '');
            $confirmPassword = (string) ($_POST['confirmPassword'] ?? '');

            if ($name === '') {
                $messName = 'Tên đăng nhập không được để trống!';
            }
            if ($address === '') {
                $messAddress = 'Địa chỉ không được để trống!';
            }
            if (!filter_var($emailRegister, FILTER_VALIDATE_EMAIL)) {
                $messEmailRegister = 'Email không hợp lệ!';
            }
            if (strlen($password) < 6) {
                $messPassword = 'Mật khẩu tối thiểu 6 ký tự!';
            }
            if ($confirmPassword !== $password) {
                $messConfirmPassword = 'Mật khẩu không khớp!';
            }

            $valid = $messName === ''
                && $messAddress === ''
                && $messEmailRegister === ''
                && $messPassword === ''
                && $messConfirmPassword === '';

            if ($valid) {
                if (check_duplicate_account($name)) {
                    $mess = 'Tên đăng nhập đã tồn tại!';
                } elseif (check_duplicate_email($emailRegister)) {
                    $mess = 'Email đã tồn tại!';
                } else {
                    insert_account($name, $password, $emailRegister, $address, 2);
                    $mess = 'Đăng ký thành công!';
                    $name = '';
                    $address = '';
                    $emailRegister = '';
                }
            }
        }

        include 'view/client/login/register.php';
    }
}
