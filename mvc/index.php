<?php
// Front controller: keep the existing ?act=admin / ?client=... URL scheme.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/query/pdo.php';

$act = $_GET['act'] ?? 'client';
if ($act === 'logout') {
    require __DIR__ . '/view/client/login/logout.php';
} elseif ($act === 'admin' || isset($_GET['admin'])) {
    require __DIR__ . '/controller/admin/admin_controller.php';
} else {
    require __DIR__ . '/controller/client/client_controller.php';
}
