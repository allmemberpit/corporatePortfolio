<?php
require_once 'controller.php';

$controller = new AuthController();
$action = $_GET['action'] ?? 'login';

switch ($action) {
    case 'admin':
        $controller->showAdmin();
        break;
    case 'login':
        $controller->showLoginForm();
        break;
    case 'login_process':
        $controller->login();
        break;
    case 'dashboard':
        $controller->showDashboard();
        break;
    case 'logout':
        $controller->logout();
        break;
    default:
        $controller->showLoginForm();
        break;
}