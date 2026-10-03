<?php

// if (!isset($_SESSION['login'])) {
//     header("Location: ?client=login");
//     exit();
// }

include 'view/client/profile/profile.php';
?>

<article>
    <h2>Đơn mua hàng</h2>
    <div class="order-full">
        <?php foreach ($list_order as $order): extract($order);
            if ($_GET['iduser'] == $id_tk) {
                if ($dh_status !== 'Đã nhận hàng') { ?>
                    <div class="order-item">
                        <p style="border: 1px solid gray; text-align: center; border-radius: 5px; margin-bottom: 5px;">Mã Đơn Hàng:
                            <?= htmlspecialchars($dh_ma, ENT_QUOTES, 'UTF-8') ?>
                        </p>
                        <?php foreach ($list_orderdetail as $orderdetail) : extract($orderdetail);
                            if ($id_dh == $dh_id) {
                                foreach ($listAll_product as $product): extract($product);
                                    if ($id_sp == $sp_id) {
                        ?>
                                        <div class="order-box">
                                            <div class="oder-info">
                                                <img src="<?= $sp_image ?>" alt="Ảnh sản phẩm" class="product-image">
                                                <div class="product-details">
                                                    <h3 class="product-name"><?= $sp_name ?></h3>
                                                    <p class="product-category">Phân loại:<?= htmlspecialchars($od_option, ENT_QUOTES, 'UTF-8') ?> + <?= htmlspecialchars($od_optionColor, ENT_QUOTES, 'UTF-8') ?></p>
                                                    <p class="product-quantity">Số lượng: <?= $orderdetail['ct_quantity'] ?></p>
                                                </div>
                                            </div>
                                        </div>
                                <?php }
                                endforeach ?>
                        <?php }
                        endforeach ?>
                        <div class="oder-status-comment">
                            <div>
                                <p class="product-status">Trạng thái: <span class="status-label"><?= $dh_status ?></span></p>
                            </div>
                            <?php if ($dh_status == 'Giao Hàng Thành Công') { ?>
                                <div class="final">
                                    <a href=""><button class="review-btn">Liên hệ</button></a>
                                    <form action="?client=finaldh" method="POST" style="display:inline">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(Csrf::token()) ?>">
                                        <input type="hidden" name="id" value="<?= (int)$dh_id ?>">
                                        <button type="submit" class="review-btn">Xác Nhận</button>
                                    </form>
                                </div>
                            <?php } ?>
                        </div>
                        <p>Thành tiền: <?= printPrice($dh_totalamount) ?></p>
                    </div>
        <?php   }
            }
        endforeach ?>
    </div>
</article>

</div>