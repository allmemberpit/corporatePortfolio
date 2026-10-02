<?php
require_once __DIR__ . '/../models/reportModel.php';
require_once __DIR__ . '/../models/adminModel.php';

class reportController {
    private $reportModel;
    private $adminModel;
    public function __construct() {
        $this->reportModel = new reportModel();
        $this->adminModel  = new adminModel();
        
    }
    //レポートの承認
    public function acceptReport() {
        $this->report(1,'日報を承認しました。');
       
    }
    //レポートの却下
    public function rejectReport() {
        $this->report(2,'日報を却下しました。');
    }

    //レポートの承認却下の共通処理
    private function report($status, $msg) {
        $message = $msg;
        $id = $_GET['id'] ?? '';
        $this->reportModel->acceptReport($id, $status);
        $user_id = $user_id = $_GET['user_id'] ?? '';
        $name    = $this->adminModel->show_name($user_id);
        $report  = $this->adminModel->getReportByUserId($user_id);
        require __DIR__ . '/../views/report.php';
    }
 
}