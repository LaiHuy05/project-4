<?php
final class ClientProductController
{
    public static function handle(string $client, $iduser = null): void
    {
        $list_category = CategoryModel::all();

        switch ($client) {
            case 'detail':
                $list_comment = CommentModel::all();
                $listAll_product = ProductModel::all();
                $list_product_color = ProductModel::colors();
                $list_product_memory = ProductModel::memories();

                $idgh = 0;
                if (isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id'])) {
                    $userId = (int) $_SESSION['user_id'];
                    $idgh = CartModel::findIdForUser($userId);

                    if ($idgh === null) {
                        CartModel::create($userId);
                        $idgh = CartModel::findIdForUser($userId);
                    }
                }

                include 'view/client/productDetail.php';
                break;

            case 'categoryShow':
                $listAll_product = ProductModel::all();
                include 'view/client/category.php';
                break;

            case 'search':
                $keyword = trim((string) ($_GET['search'] ?? ''));
                $sort = (string) ($_GET['sort'] ?? '');

                $listAll_product = ProductModel::all();
                $productTM = [];

                foreach ($listAll_product as $product) {
                    if ($keyword === '' || stripos($product['sp_name'], $keyword) !== false) {
                        $productTM[] = $product;
                    }
                }

                if ($sort === 'asc') {
                    usort($productTM, function ($a, $b) {
                        return $a['sp_price'] <=> $b['sp_price'];
                    });
                } elseif ($sort === 'desc') {
                    usort($productTM, function ($a, $b) {
                        return $b['sp_price'] <=> $a['sp_price'];
                    });
                }

                include 'view/client/homeSearch.php';
                break;
        }
    }
}
