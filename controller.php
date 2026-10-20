<?php
    require_once 'model.php';
    require_once 'models/adminModel.php';
    require_once 'models/dashboardModel.php';
class AuthController {
    private $userModel;
    private $dashBoardModel;

    public function __construct() {
        $this->userModel      = new UserModel();
        $this->dashBoardModel = new dashBoardModel();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // ログイン画面表示
    public function showLoginForm($error = '') {
        if (isset($_SESSION['user_id'])) {
            header('Location: index.php?action=dashboard');
            exit;
        }
        require 'views/login.php';
    }

    // ログイン実行処理
    public function login() {
        $id = $_POST['id'] ?? '';
        $password = $_POST['password'] ?? '';

        $admin = 'admin';

        if (!$this->userModel->authenticate($id, $password)) {
            $error = 'IDまたはパスワードが正しくありません。';
            $this->showLoginForm($error);
        } else {
            $_SESSION['user_id'] = $id;
            if ($id === $admin) {
                $adminController = new adminController();
                $adminController->showAdmin();
                exit;
            }
            header('Location: index.php?action=dashboard');
            exit;
        }
    }

    // ダッシュボード画面表示
    public function showDashboard() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
        $userId = $_SESSION['user_id'];
        $adminModel = new adminModel();   
        $name = $adminModel->show_name($userId);
        $reportStatus = $this->userModel->getReportStatus($userId);
        $applicationStatus = $this->userModel->getApplicationStatus($userId);
        $message = 'ログインに成功しました。';
        $applicationStatus = $this->userModel->getApplicationStatus($userId);
        $rejectedRests = $this->dashBoardModel->getRejectedRests($userId);
        $rejectedTrips = $this->dashBoardModel->getRejectedTrips($userId);
        $rejectedExpens = $this->dashBoardModel->getRejectedExpens($userId);
        $rejectedRegulars = $this->dashBoardModel->getRejectedRegulars($userId);
        require 'views/dashboard.php';
    }

    
    // ログアウト処理
    public function logout() {
        $_SESSION = array();
        session_destroy();
        header('Location: index.php?action=login');
        exit;
    }
}