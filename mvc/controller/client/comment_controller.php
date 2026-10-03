<?php
/**
 * Client comment actions.
 * Database functions remain in mvc/query during this behavior-preserving refactor.
 */
final class ClientCommentController
{
    public static function handle(string $client, $iduser = null): void
    {
        switch ($client) {
          case 'addComment':
              $thongBao = '';
              $id_tk = $_GET['iduser'];
              $id_sp = $_GET['idsp'];
              $bl_content = $_GET['comment'];
              if ($_GET['comment'] == '') {
                  header("location: ?client=payfinal&iduser=$id_tk");
                  $thongBao = 'Vui lòng nhập đánh giá !';
                  exit();
                  // echo `<script>alert('Vui lòng điền đánh giá!')</script>`;
              } else {
                  insert_comment($bl_content, $id_tk, $id_sp);
                  header("location: ?client=detail&iduser=$id_tk&id=$id_sp");
                  exit();
              }
              break;
        }
    }
}
