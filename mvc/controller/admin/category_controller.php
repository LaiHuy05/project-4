<?php
final class AdminCategoryController
{
    public static function handle(string $admin, $id = null): void
    {
        switch ($admin) {
            case 'categoryList':
                $list_category = CategoryModel::all();
                include 'view/admin/category/list.php';
                break;

            case 'categoryAdd':
                $thongBao = '';

                if (isset($_POST['submit'])) {
                    $name = trim((string) ($_POST['name'] ?? ''));

                    if ($name === '') {
                        $thongBao = 'Vui lòng điền tên danh mục!';
                    } else {
                        CategoryModel::create($name);
                        $thongBao = 'Thêm thành công!';
                        header("Refresh: 1.5; url='?act=admin&admin=categoryList'");
                    }
                }

                include 'view/admin/category/add.php';
                break;

            case 'categoryUpdate':
                $list_category = CategoryModel::all();
                $thongBao = '';

                if (isset($_POST['submit'])) {
                    $name = trim((string) ($_POST['name'] ?? ''));

                    if ($name === '') {
                        $thongBao = 'Vui lòng điền tên danh mục!';
                    } else {
                        CategoryModel::update($id, $name);
                        $thongBao = 'Sửa thành công!';
                        $list_category = CategoryModel::all();
                        header("Refresh: 1.5; url='?act=admin&admin=categoryList'");
                    }
                }

                include 'view/admin/category/update.php';
                break;

            case 'categoryDelete':
                CategoryModel::delete($id);
                header('Location: ?act=admin&admin=categoryList');
                exit;
        }
    }
}
