<?php
require_once __DIR__ . '/../models/adminModel.php';

class adminController {
    private $adminModel;

    public function __construct() {
        $this->adminModel = new adminModel();
    }

    // アドミン（管理者）画面表示
    public function showAdmin() {
        $userId = $_SESSION['user_id'] ?? '';
        $name = $this->adminModel->show_name($userId);
        
        $users = $this->adminModel->show_user();
        require __DIR__ . '/../views/admin.php';
    }

    public function newUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id       = $_POST['id'] ?? '';
            $userName = $_POST['name'] ?? '';
            $password = $_POST['password'] ?? '';
            $userId   = $_SESSION['user_id'];
            $name     = $_POST['adminName'] ?? '';

            
            // ユーザー登録処理
            if ($this->adminModel->create_user($id, $userName,$password)) {
                // 登録成功時の処理（例: 成功メッセージ表示）
                $error = 'ユーザー登録に成功しました。';
            } else {
                // 登録失敗時の処理（例: エラーメッセージ表示）
                $error = 'ユーザー登録に失敗しました。';
            }
            $users = $this->adminModel->show_user();
            require __DIR__ . '/../views/admin.php';
        }
    }

    // 日報確認処理
    public function checkReport() {
        $user_id = $_GET['user_id'] ?? '';
        $name = $this->adminModel->show_name($user_id);
        $report = $this->adminModel->getReportByUserId($user_id);

        require __DIR__ . '/../views/report.php';
    }
}