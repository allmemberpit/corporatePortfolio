<?php
class UserModel {
    private $pdo;

    public function __construct() {
        try {
            // SQLiteデータベースファイルに接続（なければ自動作成）
            $this->pdo = new PDO('sqlite:' . __DIR__ . '/database.sqlite');
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // テーブルが存在しない場合は作成
            $this->initDatabase();
        } catch (PDOException $e) {
            die('データベース接続エラー: ' . $e->getMessage());
        }
    }

    // テーブル作成と初期ユーザー登録
    private function initDatabase() {
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id TEXT PRIMARY KEY,
            password TEXT NOT NULL
        )";
        $this->pdo->exec($sql);

        // 初期ユーザー(admin)が存在しない場合は登録
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM users WHERE id = :id');
        $stmt->bindValue(':id', 'admin', PDO::PARAM_STR);
        $stmt->execute();
        
        if ($stmt->fetchColumn() == 0) {
            // パスワードをハッシュ化して保存
            $hashedPassword = password_hash('password123', PASSWORD_DEFAULT);
            $insertStmt = $this->pdo->prepare('INSERT INTO users (id, password) VALUES (:id, :password)');
            $insertStmt->bindValue(':id', 'admin', PDO::PARAM_STR);
            $insertStmt->bindValue(':password', $hashedPassword, PDO::PARAM_STR);
            $insertStmt->execute();
        }
    }

    // ユーザー認証ロジック
    public function authenticate($id, $password) {
        $stmt = $this->pdo->prepare('SELECT password FROM users WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // ユーザーが存在し、ハッシュ化したパスワードと一致するか検証
        if ($user && password_verify($password, $user['password'])) {
            return true;
        }
        return false;
    }
}