<?php
final class ClientHomeController
{
    public static function handle(string $client, $iduser = null): void
    {
        $list_category = CategoryModel::all();
        $listAll_product = ProductModel::all();

        if (isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id'])) {
            $userId = (int) $_SESSION['user_id'];

            if (CartModel::findIdForUser($userId) === null) {
                CartModel::create($userId);
            }

            $iduser = $userId;
        }

        include 'view/client/home.php';
    }
}
