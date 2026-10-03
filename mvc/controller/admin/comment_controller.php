<?php
/**
 * Admin comment actions.
 * Database functions remain in mvc/query during this behavior-preserving refactor.
 */
final class AdminCommentController
{
    public static function handle(string $admin, $id = null): void
    {
        switch ($admin) {
            case 'commentList':
                $list_comment = load_all_comment();
                include 'view/admin/comment/list.php';
                break;
            case 'commentDelete':
                delete_comment($id);
                header("location: ?act=admin&admin=commentList");
                break;
        }
    }
}
