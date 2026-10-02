<?php
class adminModel{
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

    public function create_user($id, $name, $password){
        //idがかぶってないかを確認する
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM users WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() > 0){
            return false; // すでに存在する場合はfalseを返す
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $insertStmt = $this->pdo->prepare('INSERT INTO users (id, name, password) VALUES (:id, :name, :password)');
        $insertStmt->bindValue(':id', $id, PDO::PARAM_STR);
        $insertStmt->bindValue(':name', $name, PDO::PARAM_STR);
        $insertStmt->bindValue(':password', $hashedPassword, PDO::PARAM_STR);
        $insertStmt->execute();
        return true;
    }

    public function show_user(){
        $stmt = $this->pdo->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function show_name($id){
        $stmt = $this->pdo->prepare("SELECT name FROM users WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ? $user['name'] : false;
    }

    public function getReportByUserId($userId) {
        $stmt = $this->pdo->prepare("SELECT id,writing,created_at 
            FROM reports WHERE user_id = :user_id AND status = :status");
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_STR);
        $stmt->bindValue(':status', '0', PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}