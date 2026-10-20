<?php
require_once __DIR__ . '/../models/applicationModel.php';
require_once __DIR__ . '/../models/adminModel.php';

class appController {
    private $applicationModel;
    private $adminModel;
    public function __construct() {
        $this->applicationModel = new applicationModel();
        $this->adminModel  = new adminModel();       
    }
    //アプリケーションの承認
    public function acceptApp() {
        $this->application(1,'申請を承認しました。');
    }
    //アプリケーションの却下
    public function rejectApp() {
        $this->application(2,'申請を却下しました。');
    }

    //アプリケーションの承認却下の共通処理
    private function application($status, $msg) {
        $message = $msg;
        $id = $_GET['id'] ?? '';
        $this->applicationModel->acceptApplication($id, $status);
        $restApps    = $this->adminModel->getRestApps();
        $tripApps    = $this->adminModel->getTripApps();
        $expenseApps = $this->adminModel->getExpenseApps();
        $regularApps = $this->adminModel->getRegularApps();
        require __DIR__ . '/../views/allApplications.php';
    }
 
}