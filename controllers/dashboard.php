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
        //require 'views/dashboard.php';
    }

    // 日報確認処理
    public function staffCheckReport() {
        $reportId = $_GET['id'] ?? '';
        $this->dashboardModel->staffCheckReport($reportId);
        $message = '日報を確認しました。';
        $this->showDashboard($message);
        //require 'views/dashboard.php';
    }

    // 日報修正処理
    public function modifyReport() {
        $reportId = $_GET['id'] ?? '';
        $writing = $_POST['writing'] ?? '';
        $this->dashboardModel->modifyReport($reportId, $writing);
        $message = '日報が修正されました。';
        $this->showDashboard($message);
        //require 'views/dashboard.php';
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
        require 'views/dashboard.php';
    }
}