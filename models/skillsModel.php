<?php
// スクリプト全体のタイムゾーンを日本時間に設定
    date_default_timezone_set('Asia/Tokyo');
class skillsModel {
    private $pdo;

    public function __construct() {
        try {
            // SQLiteデータベースファイルに接続
            $this->pdo = new PDO('sqlite:' . __DIR__ . '/../database.sqlite');
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('データベース接続エラー: ' . $e->getMessage());
        }
    }

    public function getAllSkills() {
        $stmt = $this->pdo->query("SELECT * FROM profiles");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}