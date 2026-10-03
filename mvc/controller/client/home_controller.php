<?php
/**
 * Client home actions.
 * Database functions remain in mvc/query during this behavior-preserving refactor.
 */
final class ClientHomeController
{
    public static function handle(string $client, $iduser = null): void
    {
        switch ($client) {
          case 'home':
              // if (isset($_SESSION['login'])) {
              //     // Nếu session tồn tại nhưng URL không có 'iduser'
              //     if (!isset($_GET['iduser'])) {
              //         // Chuyển hướng đến trang đăng xuất hoặc trang xử lý lỗi
              //         header("Location: ?act=logout");
              //         exit();
              //     }
              // }
              $list_account = load_all_account(); // Lấy dữ liệu từ CSDL
              $list_category = load_all_category();
              $listAll_product = load_all_product();
              $list_cart = load_all_cart();
        
        
              include 'view/client/home.php';
              break;
        }
    }
}
