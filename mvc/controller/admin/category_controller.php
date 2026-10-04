<?php
final class AdminCategoryController
{
    public static function handle(string $admin, $id = null): void
    {
        switch ($admin) {
            case 'categoryList':
                $list_category = load_all_category();
                include 'view/admin/category/list.php';
                break;

            case 'categoryAdd':
                $thongBao = '';

                if (isset($_POST['submit'])) {
                    $name = trim((string) ($_POST['name'] ?? ''));

                    if ($name === '') {
                        $thongBao = 'Vui lòng điền tên danh mục!';
                    } else {
                        insert_category($name);
                        $thongBao = 'Thêm thành công!';
                        header("Refresh: 1.5; url='?act=admin&admin=categoryList'");
                    }
                }

                include 'view/admin/category/add.php';
                break;

            case 'categoryUpdate':
                $list_category = load_all_category();
                $thongBao = '';

                if (isset($_POST['submit'])) {
                    $name = trim((string) ($_POST['name'] ?? ''));

                    if ($name === '') {
                        $thongBao = 'Vui lòng điền tên danh mục!';
                    } else {
                        update_category($id, $name);
                        $thongBao = 'Sửa thành công!';
                        $list_category = load_all_category();
                        header("Refresh: 1.5; url='?act=admin&admin=categoryList'");
                    }
                }

                include 'view/admin/category/update.php';
                break;

            case 'categoryDelete':
                delete_category($id);
                header('Location: ?act=admin&admin=categoryList');
                exit;
        }
    }
}
