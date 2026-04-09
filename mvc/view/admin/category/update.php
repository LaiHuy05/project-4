<?php
include 'view/admin/nav.php';
?>
<article>
    <div class="container-add">
        <h1>Thêm Danh mục</h1>
        <form action="" method="POST">
            <?php foreach ($list_category as $category): extract($category);
                if ($dm_id == $_GET['id']) { ?>
                    <label for="name">Tên danh mục:</label>
                    <input type="text" name="name" id="name" value="<?= $dm_name ?>">
                    <div class="return">
                        <button name="submit" type="submit">Thêm danh Mục</button>
                        <a href="?act=admin&admin=categoryList">Quay lại</a>
                    </div>
            <?php }
            endforeach ?>
        </form>
        <?= $thongBao ?>
    </div>
</article>