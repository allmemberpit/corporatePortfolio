<?php
require_once __DIR__ . '/../models/skillsModel.php';
require_once __DIR__ . '/../models/adminModel.php';

class skillsController {
    private $skillsModel;
    private $adminModel;

    public function __construct() {
        require_once __DIR__ . '/../models/skillsModel.php';
        require_once __DIR__ . '/../models/adminModel.php';
        $this->skillsModel = new skillsModel();
        $this->adminModel = new adminModel();
    }

    public function showSkillList() {
        $userId = $_SESSION['user_id'];
        $name = $this->adminModel->show_name($userId);
        $skills = $this->skillsModel->getAllSkills();
        require __DIR__ . '/../views/skillList.php';
    }
}