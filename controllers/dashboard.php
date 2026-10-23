<?php
require_once __DIR__ . '/../models/dashboardModel.php';
require_once __DIR__ . '/../models/adminModel.php';
require_once __DIR__ . '/../model.php';

class dashboardController {
    private $dashboardModel;
    private $adminModel;
    private $model;

    public function __construct() {
        $this->dashboardModel = new dashboardModel();
        $this->adminModel = new adminModel();
        $this->model = new UserModel();
    }

    //レポート送信処理
    public function submitReport() {
        $userId = $_SESSION['user_id'] ?? '';
        //$name = $this->adminModel->show_name($userId);

        $writing = $_POST['writing'] ?? '';

        $this->dashboardModel->submitReport($userId, $writing);
        $message = '日報が送信されました。';
        $this->showDashboard($message);
    }

    // 日報確認処理
    public function staffCheckReport() {
        $reportId = $_GET['id'] ?? '';
        $this->dashboardModel->staffCheckReport($reportId);
        $message = '日報を確認しました。';
        $this->showDashboard($message);
    }

    // 日報修正処理
    public function modifyReport() {
        $reportId = $_GET['id'] ?? '';
        $writing = $_POST['writing'] ?? '';
        $this->dashboardModel->modifyReport($reportId, $writing);
        $message = '日報が修正されました。';
        $this->showDashboard($message);
    }

    //申請書確認処理
    public function staffCheckApplication() {
        $applicationId = $_GET['id'] ?? '';
        $this->dashboardModel->staffCheckApplication($applicationId);
        $message = '申請書を確認しました。';
        $this->showDashboard($message);
    }

    // ダッシュボード画面表示
    private function showDashboard($message) {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
        $userId = $_SESSION['user_id'];
        $name = $this->adminModel->show_name($userId);
        $reportStatus = $this->model->getReportStatus($userId);
        $applicationStatus = $this->model->getApplicationStatus($userId);
        $rejectedRests = $this->dashboardModel->getRejectedRests($userId);
        $rejectedTrips = $this->dashboardModel->getRejectedTrips($userId);
        $rejectedExpens = $this->dashboardModel->getRejectedExpens($userId);
        $rejectedRegulars = $this->dashboardModel->getRejectedRegulars($userId);
        require 'views/dashboard.php';
    }
    // 休暇申請処理
    public function submitRest() {
        $userId      = $_SESSION['user_id'] ?? '';
        $leave_type  = $_POST['leave_type'] ?? '';
        $target_date = $_POST['target_date'] ?? '';
        $reason      = $_POST['reason'] ?? '';
        $memo        = $_POST['memo'] ?? '';

        $message = $this->dashboardModel->submitRest($userId, $leave_type, $target_date, $reason, $memo);
        $this->showDashboard($message);
    }
    // 出張申請処理
    public function submitTrip() {
        $userId         = $_SESSION['user_id'] ?? '';
        $start_date     = $_POST['start_date'] ?? '';
        $end_date       = $_POST['end_date'] ?? '';
        $destination    = $_POST['destination'] ?? '';
        $purpose        = $_POST['purpose'] ?? '';
        $estimated_cost = $_POST['estimated_cost'] ?? '';
        $has_lodging    = $_POST['has_lodging'] ?? '';
        $detail         = $_POST['detail'] ?? '';
        
        $message = $this->dashboardModel->submitTrip($userId, $start_date, $end_date, $destination, $purpose, $estimated_cost, $has_lodging, $detail);
        $this->showDashboard($message);
    }
    // 経費申請処理
    public function submitExpense() {
        $userId       = $_SESSION['user_id'] ?? '';
        $expense_date = $_POST['expense_date'] ?? '';
        $category     = $_POST['category'] ?? '';
        $payee        = $_POST['payee'] ?? '';
        $amount       = $_POST['amount'] ?? '';
        $purpose      = $_POST['purpose'] ?? '';
        $receipt      = $_FILES['receipt'] ?? null;
        $memo         = $_POST['memo'] ?? '';

        $message = $this->dashboardModel->submitExpense($userId, $expense_date, $category, $payee, $amount, $purpose, $receipt,$memo);
       
        $this->showDashboard($message);
    }
    // 定期申請処理
    public function submitRegular() {
        $userId = $_SESSION['user_id'] ?? '';
        $apply_type = $_POST['apply_type'] ?? '';
        $departure = $_POST['departure'] ?? '';
        $destination = $_POST['destination'] ?? '';
        $viaRoute = $_POST['via_route'] ?? '';
        $period = $_POST['period'] ?? '';
        $start_date = $_POST['start_date'] ?? '';
        $amount = $_POST['amount'] ?? '';
        $receipt = $_FILES['receipt'] ?? null;
        $message = $this->dashboardModel->submitRegular($userId, $apply_type, $departure, $destination, $viaRoute, $period, $start_date, $amount, $receipt);
        $this->showDashboard($message);
    }
    //休暇修正処理
    public function modifyRest() {
        $id          = $_GET['id'] ?? '';
        $leave_type  = $_POST['leave_type'] ?? '';
        $target_date = $_POST['target_date'] ?? '';
        $reason      = $_POST['reason'] ?? '';
        $memo        = $_POST['memo'] ?? '';

        $message = $this->dashboardModel->updateRest($id, $leave_type, $target_date, $reason, $memo);
        $this->showDashboard($message);
    }
    //出張修正処理
    public function modifyTrip(){
        $id             = $_GET['id'] ?? '';
        $start_date     = $_POST['start_date'] ?? '';
        $end_date       = $_POST['end_date'] ?? '';
        $destination    = $_POST['destination'] ?? '';
        $purpose        = $_POST['purpose'] ?? '';
        $estimated_cost = $_POST['estimated_cost'] ?? '';
        $has_lodging    = $_POST['has_lodging'] ?? '';
        $detail         = $_POST['detail'] ?? '';
        
        $message = $this->dashboardModel->updateTrip($id, $start_date, $end_date, $destination, $purpose, $estimated_cost, $has_lodging, $detail);
        $this->showDashboard($message);
    }
    //経費修正処理
    public function modifyExpense(){
        $id           = $_GET['id'] ?? '';
        $expense_date = $_POST['expense_date'] ?? '';
        $category     = $_POST['category'] ?? '';
        $payee        = $_POST['payee'] ?? '';
        $amount       = $_POST['amount'] ?? '';
        $purpose      = $_POST['purpose'] ?? '';
        $receipt      = $_FILES['receipt'] ?? null;
        $memo         = $_POST['memo'] ?? '';

        $message = $this->dashboardModel->updateExpense($id, $expense_date, $category, $payee, $amount, $purpose, $receipt,$memo);
        $this->showDashboard($message);
    }    
    //定期修正処理
    public function modifyRegular(){
        $id          = $_GET['id'] ?? '';
        $apply_type  = $_POST['apply_type'] ?? '';
        $departure   = $_POST['departure'] ?? '';
        $destination = $_POST['destination'] ?? '';
        $viaRoute    = $_POST['via_route'] ?? '';
        $period      = $_POST['period'] ?? '';
        $start_date  = $_POST['start_date'] ?? '';
        $amount      = $_POST['amount'] ?? '';
        $receipt     = $_FILES['receipt'] ?? null;

        $message = $this->dashboardModel->updateRegular($id, $apply_type, $departure, $destination, $viaRoute, $period, $start_date, $amount, $receipt);
       
        $this->showDashboard($message);
    }

    //パスワード変更処理
    public function changePassword() {
        $id    = $_SESSION['user_id'];;
        $oldPw = $_POST['oldPw'] ?? '';
        $newPw = $_POST['newPw'] ?? '';

        if ($this->dashboardModel->confirmationPassword($id, $oldPw)) {
            $message = $this->dashboardModel->changePassword($id, $newPw);
        }else{
            $message = '元のパスワードが違います';
        }
        $this->showDashboard($message);
    }

}