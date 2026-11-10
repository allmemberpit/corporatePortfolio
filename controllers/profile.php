<?php
require_once __DIR__ . '/../models/profileModel.php';

class profileController {
    private $profileModel;
    public function __construct() {
        $this->profileModel = new profileModel();
        
    }
    //スタッフのプロフィール画面を表示
    public function staffProfile(){
        $name = $_GET['name'] ?? '';
        $id = $_SESSION['user_id'];
        $profile = $this->profileModel->getProfileByName($id);

        require __DIR__ . '/../views/profile.php';
    }
    //スタッフのプロフィール情報を保存
    public function saveProfile() {
        $id = $_SESSION['user_id'];
        $user_name = $_POST['userName'];
        $birthday = $_POST['birthday'];
        $address = $_POST['address'];
        $nearest_station= $_POST['nearest_station'];
        $word = $_POST['word'];
        $excel = $_POST['excel'];
        $powerpoint = $_POST['powerpoint'];
        $outlook = $_POST['outlook'];
        $spreadsheet = $_POST['spreadsheet'];
        $teams = $_POST['teams'];
        $adobe = $_POST['adobe'];
        $cloud = $_POST['cloud'];
        $accounting = $_POST['accounting'];
        $erp = $_POST['erp'];
        $office_other = $_POST['office_other'];
        $python = $_POST['python'];
        $html = $_POST['html'];
        $javascript = $_POST['javascript'];
        $typescript = $_POST['typescript'];
        $php = $_POST['php'];
        $java = $_POST['java'];
        $csharp = $_POST['csharp'];
        $ruby = $_POST['ruby'];
        $cplusplus = $_POST['c++'];
        $sql = $_POST['sql'];
        $go = $_POST['go'];
        $programming_other = $_POST['programming_other'];
        $this->profileModel->saveProfile($id, $user_name, $birthday, $address, $nearest_station, 
            $word, $excel, $powerpoint, $outlook, $spreadsheet,
             $teams, $adobe, $cloud, $accounting, $erp, $office_other, 
             $python, $html, $javascript, $typescript, $php, $java, $csharp, 
             $ruby, $cplusplus, $sql, $go, $programming_other);
        $name = $user_name;
        $profile = $this->profileModel->getProfileByName($id);
        require __DIR__ . '/../views/profile.php';
    }
}