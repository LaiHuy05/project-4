<?php
include "nav.php";
include 'slideshow.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div class="container">
        <main>
            <?php if (!isset($_SESSION['login'])) { ?>
                <div class="main-full">
                    <?php
                    foreach ($list_category as $category) :
                        extract($category);
                        // Kiểm tra xem có sản phẩm nào trong danh mục hay không
                        $hasProducts = false;
                        foreach ($listAll_product as $product) {
                            extract($product);
                            if ($id_dm == $dm_id) {
                                $hasProducts = true;
                                break;
                            }
                        }
                        if ($hasProducts) {
                    ?>
                            <div class="product-category">
                                <div class="product-brand">
                                    <h2><?= $dm_name ?></h2>
                                </div>
                                <br>
                                <div class="product">
                                    <?php
                                    foreach ($listAll_product as $product) :
                                        extract($product);
                                        if ($id_dm == $dm_id) {
                                    ?>
                                            <div class="product-box">
                                                <a href="?client=detail&id=<?= $sp_id ?>">
                                                    <div class="product-box-tag">
                                                        <p>Trả góp 0%</p>
                                                    </div>
                                                    <br />
                                                    <div class="product-box-img">
                                                        <img src="<?= $sp_image ?>" alt="" />
                                                    </div>
                                                    <div class="product-box-title">
                                                        <h3><?= $sp_name ?></h3>
                                                        <div class="product-price">
                                                            <p><?= printPrice($sp_price) ?></p>
                                                            <del><?= printPrice($sp_pricedel) ?></del>
                                                        </div>
                                                        <div class="product-describe">
                                                            <p><?= $sp_title ?></p>
                                                        </div>
                                                        <div class="product-icon">
                                                            <div class="icon-star">
                                                                <i class="bx bxs-star"></i>
                                                                <i class="bx bxs-star"></i>
                                                                <i class="bx bxs-star"></i>
                                                                <i class="bx bxs-star"></i>
                                                                <i class="bx bxs-star"></i>
                                                            </div>
                                                            <div class="icon-cart-like">
                                                                <i id="cart" onchange="changeClass()" class="bx bx-cart-alt"></i>
                                                                <i id="heart" onclick="changeClass2()" onclick="" class="bx bx-heart"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                    <?php
                                        }
                                    endforeach;
                                    ?>
                                </div>
                            </div>
                    <?php
                        }
                    endforeach;
                    ?>
                </div>
                <?php } else { ?>
                <div class="main-full">
                    <?php
                    foreach ($list_category as $category) :
                        extract($category);
                        $hasProducts = false;
                        foreach ($listAll_product as $product) {
                            if ($product['id_dm'] == $dm_id) {
                                $hasProducts = true;
                                break;
                            }
                        }
                        if ($hasProducts) {
                    ?>
                            <div class="product-category">
                                <div class="product-brand">
                                    <h2><?= $dm_name ?></h2>
                                </div>
                                <div class="product">
                                    <?php
                                    foreach ($listAll_product as $product) :
                                        extract($product);
                                        if ($id_dm == $dm_id) {
                                    ?>
                                            <div class="product-box">
                                                <a href="?client=detail&iduser=<?= $iduser ?? '' ?>&id=<?= $sp_id ?>">
                                                    <div class="product-box-tag">
                                                        <p>Trả góp 0%</p>
                                                    </div>
                                                    <br />
                                                    <div class="product-box-img">
                                                        <img src="<?= $sp_image ?>" alt="" />
                                                    </div>
                                                    <div class="product-box-title">
                                                        <h3><?= $sp_name ?></h3>
                                                        <div class="product-price">
                                                            <p><?= printPrice($sp_price) ?></p>
                                                            <del><?= printPrice($sp_pricedel) ?></del>
                                                        </div>
                                                        <div class="product-describe">
                                                            <p><?= $sp_title ?></p>
                                                        </div>
                                                        <div class="product-icon">
                                                            <div class="icon-star">
                                                                <i class="bx bxs-star"></i>
                                                                <i class="bx bxs-star"></i>
                                                                <i class="bx bxs-star"></i>
                                                                <i class="bx bxs-star"></i>
                                                                <i class="bx bxs-star"></i>
                                                            </div>
                                                            <div class="icon-cart-like">
                                                                <i id="cart" onclick="changeClass()" class="bx bx-cart-alt"></i>
                                                                <i id="heart" onclick="changeClass2()" onclick="" class="bx bx-heart"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                    <?php
                                        }
                                    endforeach;
                                    ?>
                                </div>
                            </div>
                    <?php
                        }
                    endforeach;
                    ?>
                </div>
            <?php } ?>
        </main>
    </div>
    <br><br>
    <?php include 'footer.php'; ?>
</body>

</html>