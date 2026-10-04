<?php
require_once dirname(__DIR__, 2) . '/service/CheckoutService.php';

final class ClientCheckoutController
{
    public static function handle(string $client, $iduser = null): void
    {
        if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
            header('Location: ?client=login');
            exit;
        }

        $id_tk = (int) $_SESSION['user_id'];

        if (isset($_GET['iduser']) && (string) $_GET['iduser'] !== (string) $id_tk) {
            http_response_code(403);
            exit('Không thể thanh toán giỏ hàng của tài khoản khác.');
        }

        try {
            $arrayID = CheckoutService::parseItemIds($_GET['cartdetailid'] ?? null);
            $list_cartDetail = CartModel::itemsByIdsForUser($id_tk, $arrayID);

            if (count($list_cartDetail) !== count($arrayID)) {
                throw new InvalidArgumentException('Giỏ hàng không hợp lệ hoặc không thuộc tài khoản.');
            }

            $summary = CheckoutService::totals($list_cartDetail);
        } catch (InvalidArgumentException $error) {
            http_response_code(400);
            exit($error->getMessage());
        }

        $dh_totalamount = $summary['total'];
        $sp_quantity = $summary['quantity'];
        $list_category = CategoryModel::all();
        $listAll_product = ProductModel::all();


        $mess = '';
        $messNamePay = '';
        $messEmailPay = '';
        $messPhonePay = '';
        $messAddressPay = '';
        $messCountryPay = '';
        $messCityPay = '';
        $messDistrictPay = '';
        $messCommunePay = '';

        if (isset($_POST['btnPay'])) {
            if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
                http_response_code(403);
                exit('Phiên biểu mẫu không hợp lệ. Hãy tải lại trang.');
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
                    CheckoutService::createCartOrder($id_tk, $arrayID, $_POST);
                    unset($_SESSION['csrf_token']);
                    echo '<script>window.addEventListener("load", function () { showNotification(); });</script>';
                } catch (InvalidArgumentException $error) {
                    $mess = $error->getMessage();
                }
            }
        }

        include 'view/client/pay.php';
    }
}
