<?php
/**
 * Client product actions.
 * Database functions remain in mvc/query during this behavior-preserving refactor.
 */
final class ClientProductController
{
    public static function handle(string $client, $iduser = null): void
    {
        switch ($client) {
          case 'detail':
              $list_cartDetail = load_all_cartDetail();
              $list_comment = load_all_comment();
              $list_category = load_all_category();
              $listAll_product = load_all_product();
              $list_account = load_all_account(); // Lấy dữ liệu từ CSDL
              $list_cart = load_all_cart();
              $list_product_color = load_all_product_color();
              $list_product_memory = load_all_product_memory();
              include 'view/client/productDetail.php';
              break;
          case 'categoryShow':
              $list_account = load_all_account(); // Lấy dữ liệu từ CSDL
              $list_category = load_all_category();
              $listAll_product = load_all_product();
              $list_cart = load_all_cart();
        
              include 'view/client/category.php';
              break;
          case 'search':
              $list_category = load_all_category();
        
              $list_account = load_all_account(); // Lấy dữ liệu từ CSDL
              $productTM = [];
              $listAll_product = load_all_product();
              foreach ($listAll_product as $name) {
                  extract($name);
                  if (stripos(strtolower($sp_name), strtolower($_GET['search'])) !== false) {
                      $productTM[] = $name;
                  }
              }
              include 'view/client/homeSearch.php';
              break;
        }
    }
}
