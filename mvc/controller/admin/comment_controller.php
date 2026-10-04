<?php
final class AdminCommentController
{
    public static function handle(string $admin, $id = null): void
    {
        if ($admin === 'commentList') {
            $list_comment = load_all_comment();
            include 'view/admin/comment/list.php';
            return;
        }

        delete_comment($id);
        header('Location: ?act=admin&admin=commentList');
        exit;
    }
}
