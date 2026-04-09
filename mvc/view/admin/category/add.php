<?php
include 'view/admin/nav.php';
?>
<article>
    <div class="container-add">
        <h1>Thêm Danh mục</h1>
        <form action="" method="POST">
            <input type="text" name="name" id="">
            <div class="return">
                <button name="submit" type="submit">Thêm danh Mục</button>
                <a href="?act=admin&admin=categoryList">Quay lại</a>
            </div>
        </form>
        <?= $thongBao ?>
    </div>
</article>