<?php
require_once 'controller.php';
require_once 'controllers/admin.php';
require_once 'controllers/dashboard.php';
require_once 'controllers/report.php';
require_once 'controllers/applications.php';

$controller = new AuthController();
$adminController = new adminController();
$dashboardController = new DashboardController();
$appController = new appController();

$action = $_GET['action'] ?? 'login';

switch ($action) {
    case 'admin':
        $adminController->showAdmin();
        break;
    case 'login':
        $controller->showLoginForm();
        break;
    case 'login_process':
        $controller->login();
        break;
    case 'newUser':
        $adminController->newUser();
        break;
    case 'dashboard':
        $controller->showDashboard();
        break;
    case 'logout':
        $controller->logout();
        break;
    case 'submitReport':
        $dashboardController->submitReport();
        break;
    /*case 'checkReport':
        $adminController = new adminController();
        $adminController->checkReport();
        break;*/
    case 'checkApplication':
        $adminController->checkApplication();
        break;
    case 'allReports':
        $adminController->checkAllReports();    
        break;
    case 'allApplications':
        $adminController->checkAllApplications();
        break;
    case 'acceptReport':
        $reportController = new reportController();
        $reportController->acceptReport();
        break;
    case 'rejectReport':
        $reportController = new reportController();
        $reportController->rejectReport();   
        break;
    case 'resetPw':
        $adminController->resetPassword();
        break;
    case 'changePw':
        $dashboardController->changePassword();
        break;
    case 'changePwAdmin':
        $adminController->changePassword();
        break;
    case 'staffCheckReport':
        $dashboardController->staffCheckReport();
        break;
    case 'modifyReport':
        $dashboardController->modifyReport();
        break;
    case 'staffCheckApplication':
        $dashboardController->staffCheckApplication();
        break;
    case 'submitRest':
        $dashboardController->submitRest();
        break;
    case 'submitTrip':
        $dashboardController->submitTrip();
        break;
    case 'submitExpense':
        $dashboardController->submitExpense();
        break;
    case 'submitRegular':
        $dashboardController->submitRegular();
        break;
    case 'acceptApp':
        $appController->acceptApp();
        break;
    case 'rejectApp':
        $appController->rejectApp();
        break;
    case 'modifyRest':
        $dashboardController->modifyRest();
        break;
    case 'modifyTrip':
        $dashboardController->modifyTrip();
        break;
    case 'modifyExpense':
        $dashboardController->modifyExpense();
        break;
    case 'modifyRegular':
        $dashboardController->modifyRegular();
        break;
    default:
        $controller->showLoginForm();
        break;
}