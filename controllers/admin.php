<?php
require_once __DIR__ . '/../models/adminModel.php';
require_once __DIR__ . '/../models/dashboardModel.php';

class adminController {
    private $adminModel;
    private $dashboardModel;

    public function __construct() {
        $this->adminModel     = new adminModel();
        $this->dashboardModel = new dashboardModel();
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
    //パスワードのリセット処理
    public function resetPassword()  {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id       = $_POST['id'] ?? '';
            $password = $_POST['pw'] ?? '';
            $name     = $_POST['adminName'] ?? '';
            // パスワードリセット処理
            $message =$this->adminModel->resetPw($id,$password);

            $users = $this->adminModel->show_user();
            require __DIR__ . '/../views/admin.php';
        }
    }

    //adminのパスワード変更処理
    public function changePassword(){
        $id    = $_SESSION['user_id'];;
        $oldPw = $_POST['oldPw'] ?? '';
        $newPw = $_POST['newPw'] ?? '';
        $name  = $_POST['name'] ?? '';

        if ($this->dashboardModel->confirmationPassword($id, $oldPw)) {
            $message = $this->dashboardModel->changePassword($id, $newPw);
        }else{
            $message = '元のパスワードが違います';
        }
        $users = $this->adminModel->show_user();
        require __DIR__ . '/../views/admin.php';
    }
}