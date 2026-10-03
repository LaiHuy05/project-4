<?php
require_once dirname(__DIR__, 2) . '/service/CheckoutService.php';
final class ClientSingleCheckoutController
{
    public static function handle(string $client, $iduser = null): void
    {
        if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
            header('Location: ?client=login'); exit;
        }
        $id_tk = (int) $_SESSION['user_id'];
        if (isset($_GET['iduser']) && (string) $id_tk !== (string) $_GET['iduser']) {
            http_response_code(403); exit('Không thể thanh toán cho tài khoản khác.');
        }
        try {
            $id_sp = CheckoutService::positiveInt($_GET['idsp'] ?? null, 'Sản phẩm');
            $sp_quantity = CheckoutService::positiveInt($_GET['quantity'] ?? null, 'Số lượng', 100);
            $cd_option = trim((string) ($_GET['optionb'] ?? ''));
            $cd_optionColor = trim((string) ($_GET['optioncolorb'] ?? ''));
            if (strlen($cd_option) > 100 || strlen($cd_optionColor) > 100) {
                throw new InvalidArgumentException('Tùy chọn sản phẩm không hợp lệ.');
            }
            $product = load_one_product($id_sp);
            if (!$product) throw new InvalidArgumentException('Sản phẩm không tồn tại.');
            $summary = CheckoutService::totals([[
                'cd_quantity' => $sp_quantity, 'sp_price' => $product['sp_price']
            ]]);
        } catch (InvalidArgumentException $invalid) {
            http_response_code(400); exit($invalid->getMessage());
        }
        $singleTotal = $summary['total'];
        $list_category = load_all_category();
        $list_cartDetail = []; // No need to load other users' carts.
        $listAll_product = load_all_product();
        $current = load_one_account($id_tk);
        $list_account = $current ? [$current] : [];
        $list_cart = load_all_cart();
        $mess = $messNamePay = $messEmailPay = $messPhonePay = '';
        $messAddressPay = $messCountryPay = $messCityPay = '';
        $messDistrictPay = $messCommunePay = '';
        if (isset($_POST['btnPay'])) {
            if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
                http_response_code(403); exit('Phiên biểu mẫu không hợp lệ. Hãy tải lại trang.');
            }
            $errors = CheckoutService::validateForm($_POST);
            $messNamePay = $errors['namePay'] ?? '';
            $messEmailPay = $errors['emailPay'] ?? '';
            $messPhonePay = $errors['phonePay'] ?? '';
            $messAddressPay = $errors['addressPay'] ?? '';
            $messCountryPay = $errors['countryPay'] ?? '';
            $messCityPay = $errors['cityPay'] ?? '';
            $messDistrictPay = $errors['districtPay'] ?? '';
            $messCommunePay = $errors['communePay'] ?? '';
            if (!$errors) {
                try {
                    CheckoutService::createSingleOrder($id_tk, $id_sp, $sp_quantity,
                        $cd_option, $cd_optionColor, $_POST);
                    unset($_SESSION['csrf_token']);
                    echo '<script>window.addEventListener("load",function(){showNotification();});</script>';
                } catch (InvalidArgumentException $invalid) {
                    $mess = $invalid->getMessage();
                }
            }
        }
        include 'view/client/payOne.php';
    }
}
