<?php
include "nav.php";
?>
<!DOCTYPE html>
<html lang="en">
<!-- <style>
    .filter-buttons {
        margin: 5px 0;
        text-align: left;
        /* Đảm bảo rằng các nút không căn giữa */
    }

    .filter-buttons legend {
        font-weight: bold;
        color: var(--primary-bg);
    }

    .filter-buttons button {
        background-color: #e74c3c;
        color: #fff;
        border: none;
        border-radius: 5px;
        padding: 12px 24px;
        margin: 10px 0;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
        font-weight: bold;
        text-transform: uppercase;
        outline: none;
    }

    .filter-buttons button:hover {
        background-color: #c0392b;
        /* Đổi màu khi hover */
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .filter-buttons button:active {
        transform: translateY(0);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }
</style> -->

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
                    <!-- <div class="filter-buttons">
                        <legend>Sắp Xếp Theo Giá Sản Phẩm</legend>
                        <button type="button" onclick="updateSort('asc')">Tăng dần</button>
                        <button type="button" onclick="updateSort('desc')">Giảm dần</button>
                    </div> -->

                    <?php foreach ($list_category as $category) : extract($category);
                        if ($dm_id == $_GET['iddm']) {
                    ?>
                            <div class="product-category">
                                <div class="product-brand">
                                    <h2><?= $dm_name ?></h2>
                                </div>
                                <br>
                                <div class="product">
                                    <?php foreach ($listAll_product as $product) : extract($product);
                                        if ($id_dm == $dm_id) {
                                    ?>
                                            <div class="product-box">
                                                <a href="?client=detail&id=<?= $sp_id ?>">
                                                    <div class="product-box-tag">
                                                        <p>Trả góp 0%</p>
                                                    </div>
                                                    <br />
                                                    <div class="product-box-img">
                                                        <img
                                                            src="<?= $sp_image ?>"
                                                            alt="" />
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
                                    <?php }
                                    endforeach ?>
                                </div>
                            </div>
                    <?php  }
                    endforeach ?>
                </div>
            <?php } else { ?>
                <div class="main-full">
                    <!-- <div class="filter-buttons">
                        <legend>Sắp Xếp Theo Giá Sản Phẩm</legend>
                        <button type="button" onclick="updateSort('asc')">Tăng dần</button>
                        <button type="button" onclick="updateSort('desc')">Giảm dần</button>
                    </div> -->

                    <?php
                    foreach ($list_category as $category) : extract($category);
                        if ($dm_id == $_GET['iddm']) {
                    ?>
                            <div class="product-category">
                                <div class="product-brand">
                                    <h2><?= $dm_name ?></h2>
                                </div>
                                <div class="product">
                                    <?php foreach ($listAll_product as $product) : extract($product);
                                        if ($id_dm == $dm_id) {
                                            foreach ($list_account as $account): extract($account);
                                                if ($_GET['iduser'] == $tk_id) {
                                    ?>
                                                    <div class="product-box">
                                                        <a href="?client=detail&iduser=<?= $tk_id ?>&id=<?= $sp_id ?>">
                                                            <div class="product-box-tag">
                                                                <p>Trả góp 0%</p>
                                                            </div>
                                                            <br />
                                                            <div class="product-box-img">
                                                                <img
                                                                    src="<?= $sp_image ?>"
                                                                    alt="" />
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
                                            <?php }
                                            endforeach ?>
                                    <?php }
                                    endforeach ?>
                                </div>
                            </div>
                    <?php  }
                    endforeach ?>
                </div>
            <?php } ?>
        </main>
    </div>
    <br><br>
    <?php
    include 'footer.php' ?>
</body>

</html>