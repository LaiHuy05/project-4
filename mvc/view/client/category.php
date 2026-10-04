<?php
include 'nav.php';

$categoryId = (int) ($_GET['iddm'] ?? 0);
$selectedCategory = null;

foreach ($list_category as $category) {
    if ((int) $category['dm_id'] === $categoryId) {
        $selectedCategory = $category;
        break;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh mục sản phẩm</title>
</head>

<body>
    <div class="container">
        <main>
            <div class="main-full">
                <?php if ($selectedCategory): ?>
                    <div class="product-category">
                        <div class="product-brand">
                            <h2><?= htmlspecialchars($selectedCategory['dm_name'], ENT_QUOTES, 'UTF-8') ?></h2>
                        </div>

                        <div class="product">
                            <?php foreach ($listAll_product as $product): ?>
                                <?php if ((int) $product['id_dm'] !== $categoryId) continue; ?>

                                <?php
                                $detailUrl = '?client=detail&id=' . (int) $product['sp_id'];
                                if (isset($_SESSION['user_id'])) {
                                    $detailUrl .= '&iduser=' . (int) $_SESSION['user_id'];
                                }
                                ?>

                                <div class="product-box">
                                    <a href="<?= $detailUrl ?>">
                                        <div class="product-box-tag">
                                            <p>Trả góp 0%</p>
                                        </div>

                                        <br>

                                        <div class="product-box-img">
                                            <img
                                                src="<?= htmlspecialchars($product['sp_image'], ENT_QUOTES, 'UTF-8') ?>"
                                                alt="<?= htmlspecialchars($product['sp_name'], ENT_QUOTES, 'UTF-8') ?>">
                                        </div>

                                        <div class="product-box-title">
                                            <h3><?= htmlspecialchars($product['sp_name'], ENT_QUOTES, 'UTF-8') ?></h3>

                                            <div class="product-price">
                                                <p><?= printPrice($product['sp_price']) ?></p>
                                                <del><?= printPrice($product['sp_pricedel']) ?></del>
                                            </div>

                                            <?php if (!empty($product['sp_title'])): ?>
                                                <div class="product-describe">
                                                    <p><?= htmlspecialchars($product['sp_title'], ENT_QUOTES, 'UTF-8') ?></p>
                                                </div>
                                            <?php endif; ?>

                                            <div class="product-icon">
                                                <div class="icon-star">
                                                    <i class="bx bxs-star"></i>
                                                    <i class="bx bxs-star"></i>
                                                    <i class="bx bxs-star"></i>
                                                    <i class="bx bxs-star"></i>
                                                    <i class="bx bxs-star"></i>
                                                </div>
                                                <div class="icon-cart-like">
                                                    <i class="bx bx-cart-alt"></i>
                                                    <i class="bx bx-heart"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <p>Danh mục không tồn tại.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <br><br>
    <?php include 'footer.php'; ?>
</body>
</html>
