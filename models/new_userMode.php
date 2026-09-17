<?php
class new_userModel{
    private $pdo;

    public function __construct() {
        try {
            // SQLiteデータベースファイルに接続（なければ自動作成）
            $this->pdo = new PDO('sqlite:' . __DIR__ . '/database.sqlite');
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            
        } catch (PDOException $e) {
            die('データベース接続エラー: ' . $e->getMessage());
        }
    }

    public function create_user($id, $password){
        //idがかぶってないかを確認する
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM users WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() > 0){
            return false; // すでに存在する場合はfalseを返す
        }


        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}