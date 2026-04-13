<?php
// mvc/index.php
session_start(); 
include_once './query/pdo.php';

$act = $_GET['act'] ?? 'client';

if ($act === 'logout') {
    // Xử lý logout thẳng ở đây hoặc gọi file logout
    include_once 'view/client/login/logout.php';
} elseif ($act === 'admin' || isset($_GET['admin'])) {
    include_once 'controller/admin/admin_controller.php';
} else {
    // Luồng client
    include_once 'controller/client/client_controller.php';
}