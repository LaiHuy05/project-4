<?php
session_start();
require __DIR__.'/../core/Csrf.php';
require __DIR__.'/../core/ActionRouter.php';
function check_test($pass,$msg) { if (!$pass) throw new RuntimeException($msg); }
$t=Csrf::token();
check_test(strlen($t)===64&&Csrf::verify($t),'valid csrf');
check_test(!Csrf::verify('wrong'),'bad csrf');
$h=password_hash('correct',PASSWORD_BCRYPT);
check_test(password_verify('correct',$h)&&!password_verify('wrong',$h),'bcrypt');
http_response_code(200);
ob_start();
ActionRouter::dispatch('missing',[],[]);
$x=ob_get_clean();
check_test(http_response_code()===404&&strpos($x,'không tồn tại')!==false,'unknown route');
echo "Security smoke tests passed\n";
