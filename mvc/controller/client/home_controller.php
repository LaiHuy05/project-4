<?php
final class ClientHomeController
{
    public static function handle(string $client, $iduser = null): void
    {
        $list_account = load_all_account();
        $list_category = load_all_category();
        $listAll_product = load_all_product();
        $list_cart = load_all_cart();

        include 'view/client/home.php';
    }
}
