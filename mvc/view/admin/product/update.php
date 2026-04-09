<?php
include 'view/admin/nav.php';
?>
<article>
    <div class="container-add">
        <h1>Sửa Sản Phẩm</h1>
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="name">Tên sản phẩm:</label>
            <input type="text" name="name" id="name" value="<?= $sp_name ?>">
            <span class="error-message"><?= $thongBaoLoiTen ?></span>

            <label for="image">Ảnh sản phẩm:</label>
            <input type="text" name="image" id="image" value="<?= $sp_image ?>">
            <input type="file" name="file_upload">
            <span class="error-message"><?= $thongBaoLoiAnh ?></span>

            <label for="price">Giá sản phẩm:</label>
            <input type="number" name="price" id="price" value="<?= $sp_price ?>">
            <span class="error-message"><?= $thongBaoLoiGia ?></span>

            <label for="quantity">Số lượng:</label>
            <input type="text" name="quantity" id="quantity" value="<?= $sp_quantity ?>">
            <span class="error-message"><?= $thongBaoLoiSoLuong ?></span>

            <label for="describe">Mô tả:</label>
            <input type="text" name="describe" id="describe" value="<?= $sp_describe ?>">
            <span class="error-message"><?= $thongBaoLoiMoTa ?></span>

            <label for="sp_pricedel">Giá biến thể:</label>
            <input type="text" name="sp_pricedel" id="sp_pricedel" value="<?= $sp_pricedel ?>">
            <span class="error-message"><?= $thongBaoLoiMoTa ?></span>

            <label for="dm_id">Danh mục:</label>
            <select name="dm_id" id="dm_id" required>
                <?php foreach ($list_category as $category): ?>
                    <option value="<?= $category['dm_id'] ?>" <?= ($category['dm_id'] == $id_dm) ? 'selected' : '' ?>>
                        <?= $category['dm_name'] ?>
                    </option>
                <?php endforeach ?>
            </select>
            <span class="error-message"><?= $thongBaoLoiDM ?></span>
            <div class="return">
                <button name="submit" type="submit" class="btn btn-primary">Sửa Sản Phẩm</button>
                <a href="?act=admin&admin=productList">Quay lại</a>
            </div>
        </form>

        <div class="mt-3">
            <?= $thongBao ?>
        </div>
    </div>
    <div class="container-add">
        <h1>Thêm Màu Sản Phẩm</h1>
        <form action="" method="POST">
            <label for="name">Nhập Tên:</label>
            <input type="text" name="name_color" id="name" value="<?= $_POST["name_color"] ?? "" ?>">
            <div class="error-message"><?= $thongBaoLoiTenMau ?></div>



            <button name="submit_color" type="submit" class="btn btn-primary">Thêm SP</button>
        </form>

        <div class="mt-3">
            <?= $thongBaoMau ?>
        </div>
    </div>
    <div class="container-add">
        <h1>Thêm Bộ Nhớ Sản Phẩm</h1>
        <form action="" method="POST">
            <label for="name">Nhập Tên:</label>
            <input type="text" name="name_memory" id="name_memory" value="<?= $_POST["name_memory"] ?? "" ?>">
            <div class="error-message"><?= $thongBaoLoiTenBN ?></div>



            <button name="submit_memory" type="submit" class="btn btn-primary">Thêm SP</button>
        </form>
        <div class="mt-3">
            <?= $thongBaoBN ?>
        </div>
    </div>
</article>