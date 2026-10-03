<?php
/**
 * Client cart actions.
 * Database functions remain in mvc/query during this behavior-preserving refactor.
 */
final class ClientCartController
{
    public static function handle(string $client, $iduser = null): void
    {
        switch ($client) {
          case 'cart':
              $listAll_product = load_all_product();
              $list_account = load_all_account(); // Lấy dữ liệu từ CSDL
              $list_cart = load_all_cart();
              $list_cartDetail = load_all_cartDetail();
              $list_category = load_all_category();
        
              include 'view/client/cart.php';
              break;
          case 'cartdetail':
              $list_cart = load_all_cart();
              $idsp = $_GET['id'];
              $idgh = $_GET['idgh'];
              $option = $_GET['option'];
              $colorOption  = $_GET['colorOption'];
              // echo $option;
              insert_cartDetail($idgh, $idsp, $option, $colorOption);
              // addOneCart($idgh);
              $idtk = 0;
              foreach ($list_cart as $cart) {
                  extract($cart);
                  if ($idgh == $gh_id) {
                      $idtk = $id_tk;
                      break;
                  }
              }
              header("Location: ?client=cart&iduser=$idtk");
              break;
          case 'Cartdetailadd':
              $idcd = $_GET['idcd'];
              $idgh = $_GET['idgh'];
              addOneCartdetail($idcd);
              $list_cart = load_all_cart();
              // addOneCart($idgh);
              $idtk = 0;
              foreach ($list_cart as $cart) {
                  extract($cart);
                  if ($idgh == $gh_id) {
                      $idtk = $id_tk;
                      break;
                  }
              }
              header("Location: ?client=cart&iduser=$idtk");
              break;
          case 'Cartdetailoss':
              $idcd = $_GET['idcd'];
              $idgh = $_GET['idgh'];
              lossOneCartdetail($idcd);
              $list_cart = load_all_cart();
              // addOneCart($idgh);
              $idtk = 0;
              foreach ($list_cart as $cart) {
                  extract($cart);
                  if ($idgh == $gh_id) {
                      $idtk = $id_tk;
                      break;
                  }
              }
              header("Location: ?client=cart&iduser=$idtk");
              break;
          case 'cartdelete':
              $list_cart = load_all_cart();
              $id = $_GET['id'];
              // $idsp = $_GET['id'];
              $idgh = $_GET['idgh'];
              delete_cartdetail($id);
              $idtk = 0;
              foreach ($list_cart as $cart) {
                  extract($cart);
                  if ($idgh == $gh_id) {
                      $idtk = $id_tk;
                      break;
                  }
              }
              header("Location: ?client=cart&iduser=$idtk");
              break;
        }
    }
}
