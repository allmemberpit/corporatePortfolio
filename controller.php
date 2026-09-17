<?php
require_once 'model.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
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

        if ($this->userModel->authenticate($id, $password)) {
            $_SESSION['user_id'] = $id;
            if ($id === $admin) {
                header('Location: index.php?action=admin');
                exit;
            }
            header('Location: index.php?action=dashboard');
            exit;
        } else {
            $error = 'IDまたはパスワードが正しくありません。';
            $this->showLoginForm($error);
        }
    }

    // ダッシュボード画面表示
    public function showDashboard() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
        $userId = $_SESSION['user_id'];
        require 'views/dashboard.php';
    }

    // アドミン（管理者）画面表示
    public function showAdmin() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
        $userId = $_SESSION['user_id'];
        require 'views/admin.php';
    }

    // ログアウト処理
    public function logout() {
        $_SESSION = array();
        session_destroy();
        header('Location: index.php?action=login');
        exit;
    }
}