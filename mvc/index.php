<?php
// mvc/index.php
session_start(); 

$act = $_GET['act'] ?? 'client';

switch ($act) {
    case 'admin':
        // Gọi file controller admin vừa tạo
        include_once 'controller/admin/admin_controller.php'; 
        break;
    case 'client':
        include 'view/client/index.php';
        break;
    case 'logout':
        include './view/client/login/logout.php';
        break;
    default:
        include 'view/client/index.php'; 
        break;
}