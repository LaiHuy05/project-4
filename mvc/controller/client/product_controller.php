<?php
final class ClientProductController
{
    public static function handle(string $client, $iduser = null): void
    {
        $list_category = load_all_category();
        $list_account = load_all_account();

        switch ($client) {
            case 'detail':
                $list_cartDetail = load_all_cartDetail();
                $list_comment = load_all_comment();
                $listAll_product = load_all_product();
                $list_cart = load_all_cart();
                $list_product_color = load_all_product_color();
                $list_product_memory = load_all_product_memory();

                include 'view/client/productDetail.php';
                break;

            case 'categoryShow':
                $listAll_product = load_all_product();

                include 'view/client/category.php';
                break;

            case 'search':
                $keyword = trim((string) ($_GET['search'] ?? ''));
                $listAll_product = load_all_product();
                $productTM = [];

                foreach ($listAll_product as $product) {
                    if (stripos($product['sp_name'], $keyword) !== false) {
                        $productTM[] = $product;
                    }
                }

                include 'view/client/homeSearch.php';
                break;
        }
    }
}
