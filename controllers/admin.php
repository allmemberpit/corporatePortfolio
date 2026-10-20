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
        $message = 'ログインに成功しました。';
        $name = $this->adminModel->show_name($userId);
        $this->showAdminScreen($message, $name);
        
    }

    private function showAdminScreen($message, $name){
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
                $message = 'ユーザー登録に成功しました。';
            } else {
                // 登録失敗時の処理（例: エラーメッセージ表示）
                $message = 'ユーザー登録に失敗しました。';
            }
            $users = $this->adminModel->show_user();
            require __DIR__ . '/../views/admin.php';
        }
    }
    //パスワード変更処理
    public function changePassword() {
        $id    = $_POST['id'] ?? '';
        $oldPw = $_POST['oldPw'] ?? '';
        $newPw = $_POST['newPw'] ?? '';

        if ($this->adminModel->confirmationPassword($id, $oldPw)) {
            $message = $this->adminModel->changePassword($id, $newPw);
        }else{
            $message = '元のパスワードが違います';
        }
        $name = $this->adminModel->show_name($_SESSION['user_id']);
        $this->showAdminScreen($message,$name);
    }

    // 申請確認処理
    public function checkApplication() {
        $user_id = $_GET['user_id'] ?? '';
        $name = $this->adminModel->show_name($user_id);
        $application = $this->adminModel->getApplicationByUserId($user_id);

        require __DIR__ . '/../views/application.php';
    }
    // 全ての日報確認処理
    public function checkAllReports() {
        $report = $this->adminModel->getAllReports();

        require __DIR__ . '/../views/allReports.php';
    }
    // 全ての申請確認処理
    public function checkAllApplications() {
        $restApps = $this->adminModel->getRestApps();
        $tripApps  = $this->adminModel->getTripApps();
        $expenseApps  = $this->adminModel->getExpenseApps();
        $regularApps  = $this->adminModel->getRegularApps();

        require __DIR__ . '/../views/allApplications.php';
    }
}