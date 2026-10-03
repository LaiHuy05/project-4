<?php
/**
 * Client checkout actions.
 * Database functions remain in mvc/query during this behavior-preserving refactor.
 */
final class ClientCheckoutController
{
    public static function handle(string $client, $iduser = null): void
    {
        switch ($client) {
          case 'pay':
              // Lấy dữ liệu từ URL
              $list_category = load_all_category(); // Load danh mục
              $list_cartDetail = load_all_cartDetail(); // Load danh mục
              $list_account = load_all_account(); // Load tài khoản
              $listAll_product = load_all_product();
              $id_cd = isset($_GET['cartdetailid']) ? explode(',', $_GET['cartdetailid']) : []; // Tách ID sản phẩm thành mảng
              $id_tk = isset($_GET['iduser']) ? intval($_GET['iduser']) : null; // ID tài khoản người dùng
              // $dh_totalamount = isset($_GET['totalamount']) ? floatval($_GET['totalamount']) : 0; // Tổng tiền
              $dh_totalamount = $_GET['totalamount'];
              $mess = "";
              $messNamePay = $messEmailPay = $messPhonePay = $messAddressPay = $messCountryPay = "";
              $messCityPay = $messDistrictPay = $messCommunePay = "";
              $arrayID = $id_cd;
              if (isset($_POST['btnPay'])) {
                  // Lấy dữ liệu từ form thanh toán
                  $namePay = trim($_POST['namePay']);
                  $emailPay = trim($_POST['emailPay']);
                  $phonePay = trim($_POST['phonePay']);
                  $addressPay = trim($_POST['addressPay']);
                  $countryPay = trim($_POST['countryPay']);
                  $cityPay = trim($_POST['cityPay']);
                  $districtPay = trim($_POST['districtPay']);
                  $communePay = trim($_POST['communePay']);
                  $messagePay = trim($_POST['messagePay']);
                  $check_valid_order = true;
                  // Kiểm tra từng trường dữ liệu
                  if (empty($namePay)) {
                      $messNamePay = "Tên không được trống!";
                      $check_valid_order = false;
                  }
                  if (empty($emailPay)) {
                      $messEmailPay = "Email không được để trống!";
                      $check_valid_order = false;
                  } elseif (!filter_var($emailPay, FILTER_VALIDATE_EMAIL)) {
                      $messEmailPay = "Địa chỉ email không hợp lệ!";
                      $check_valid_order = false;
                  }
                  if (empty($phonePay)) {
                      $messPhonePay = "SĐT không được để trống!";
                      $check_valid_order = false;
                  } elseif (!preg_match('/^[0-9]{10,11}$/', $phonePay)) {
                      $messPhonePay = "SĐT không hợp lệ!";
                      $check_valid_order = false;
                  }
                  if (empty($addressPay)) {
                      $messAddressPay = "Địa chỉ không được trống!";
                      $check_valid_order = false;
                  }
                  if (empty($countryPay)) {
                      $messCountryPay = "Quốc gia không được trống!";
                      $check_valid_order = false;
                  }
                  if (empty($cityPay)) {
                      $messCityPay = "Thành phố không được trống!";
                      $check_valid_order = false;
                  }
                  if (empty($districtPay)) {
                      $messDistrictPay = "Quận / Huyện không được trống!";
                      $check_valid_order = false;
                  }
                  if (empty($communePay)) {
                      $messCommunePay = "Xã / Phường không được trống!";
                      $check_valid_order = false;
                  }
                  // Nếu hợp lệ, thêm thông tin đơn hàng
                  if ($check_valid_order) {
                      // Trạng thái đơn hàng (chờ xác nhận)
                      $dh_status = "chờ xác nhận";
                      $sp_quantity = $_GET['totalquantity'];
                      $dh_ma = 'FS_' . mt_rand(100000, 999999);
                      // Lặp qua từng sản phẩm trong giỏ hàng
                      // Gọi hàm insert_order để lưu đơn hàng vào cơ sở dữ liệu
                      $id_dh = insert_order(
                          $namePay,
                          $emailPay,
                          $phonePay,
                          $addressPay,
                          $countryPay,
                          $cityPay,
                          $districtPay,
                          $communePay,
                          $messagePay,
                          $dh_status,
                          $dh_totalamount,
                          $id_tk,
                          $sp_quantity,
                          $dh_ma
                      );
                      // Lấy ID sản phẩm từ URL
                      foreach ($list_cartDetail as $item) {
                          extract($item);
                          foreach ($arrayID as $id_cd) {
                              if ($id_cd == $cd_id) {
                                  insert_orderdetail(
                                      $id_dh,
                                      $id_sp,
                                      $cd_quantity,
                                      $cd_option,
                                      $cd_optionColor,
                                  );
                              }
                          }
                      }
                      if (isset($_GET['cartdetailid'])) {
                          // Lấy giá trị từ tham số cartdetailid
                          $cartDetailIdString = $_GET['cartdetailid'];
        
                          // Chuyển chuỗi thành mảng sử dụng explode
                          $cartDetailIds = explode(',', $cartDetailIdString);
                          foreach ($cartDetailIds as $value) {
                              delete_cartdetail($value);
                          }
                      }
                      echo '<script> window.addEventListener("load", function() { showNotification(); }); </script>';
                  }
              }
              // Hiển thị giao diện form thanh toán
              include 'view/client/pay.php';
              break;
        }
    }
}
