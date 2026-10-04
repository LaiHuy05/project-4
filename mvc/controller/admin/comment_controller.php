<?php
final class AdminCommentController
{
    public static function handle(string $admin, $id = null): void
    {
        if ($admin === 'commentList') {
            $list_comment = CommentModel::all();
            include 'view/admin/comment/list.php';
            return;
        }

        CommentModel::delete($id);
        header('Location: ?act=admin&admin=commentList');
        exit;
    }
}
