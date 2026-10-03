<?php
/**
 * Admin category actions.
 * Database functions remain in mvc/query during this behavior-preserving refactor.
 */
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
                    $name = trim($_POST['name']);
                    if ($name == '') {
                        $thongBao = 'vui lòng điền tên danh mục!';
                    } else {
                        insert_category($name);
                        $thongBao = "thêm thành công!";
                        header("Refresh: 1.5; url='?act=admin&admin=categoryList'");
                    }
                }
                include 'view/admin/category/add.php';
                break;
            case 'categoryDelete':
                delete_category($id);
                header("location: ?act=admin&admin=categoryList");
                break;
            case 'categoryUpdate':
                $list_category = load_all_category();
        
                $thongBao = '';
                $name = load_one_category($id);
                if (isset($_POST['submit'])) {
                    $name = trim($_POST['name']);
                    if ($name == '') {
                        $thongBao = 'vui lòng điền tên danh mục!';
                    } else {
                        update_category($id, $name);
                        $thongBao = "Sửa thành công!";
                        header("Refresh: 1.5; url='?act=admin&admin=categoryList'");
                    }
                }
                include 'view/admin/category/update.php';
                // header("location: ?act=admin&admin=categoryList");
                break;
        }
    }
}
