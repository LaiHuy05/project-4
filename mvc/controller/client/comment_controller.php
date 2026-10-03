<?php
final class ClientCommentController
{
    public static function handle(string $client, $iduser = null): void
    {
        $uid = $_SESSION['user_id'] ?? null;
        if ($uid === null || filter_var($uid,FILTER_VALIDATE_INT) === false) {
            header('Location: ?client=login'); exit;
        }
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            http_response_code(405); header('Allow: POST'); exit('Chỉ chấp nhận POST.');
        }
        if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
            http_response_code(403); exit('Phiên biểu mẫu không hợp lệ.');
        }
        $productId = filter_var($_POST['idsp'] ?? null,FILTER_VALIDATE_INT);
        $content = trim((string)($_POST['comment'] ?? ''));
        if (!$productId || !load_one_product($productId) || $content === '' || mb_strlen($content) > 1000) {
            http_response_code(400); exit('Đánh giá không hợp lệ.');
        }
        insert_comment($content,(int)$uid,$productId);
        header('Location: ?client=detail&iduser='.(int)$uid.'&id='.$productId); exit;
    }
}
