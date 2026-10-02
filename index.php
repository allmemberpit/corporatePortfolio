<?php
require_once 'controller.php';
require_once 'controllers/admin.php';
require_once 'controllers/dashboard.php';
require_once 'controllers/report.php';

$controller = new AuthController();


$action = $_GET['action'] ?? 'login';

switch ($action) {
    case 'admin':
        $adminController = new adminController();
        $adminController->showAdmin();
        break;
    case 'login':
        $controller->showLoginForm();
        break;
    case 'login_process':
        $controller->login();
        break;
    case 'newUser':
        $adminController = new adminController();
        $adminController->newUser();
        break;
    case 'dashboard':
        $controller->showDashboard();
        break;
    case 'logout':
        $controller->logout();
        break;
    case 'submitReport':
        $dashboardController = new dashboardController();
        $dashboardController->submitReport();
        break;
    case 'checkReport':
        $adminController = new adminController();
        $adminController->checkReport();
        break;
    case 'acceptReport':
        $reportController = new reportController();
        $reportController->acceptReport();
        break;
    case 'rejectReport':
        $reportController = new reportController();
        $reportController->rejectReport();   
        break;
   case 'staffCheckReport':
        $dashboardController = new dashboardController();
        $dashboardController->staffCheckReport();
        break;
    case 'modifyReport':
        $dashboardController = new dashboardController();
        $dashboardController->modifyReport();
        break;
    default:
        $controller->showLoginForm();
        break;
}