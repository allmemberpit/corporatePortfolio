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
        // ユーザーテーブルの作成
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id TEXT PRIMARY KEY,
            name TEXT NOT NULL,
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
            $insertStmt = $this->pdo->prepare('INSERT INTO users (id, name, password) VALUES (:id, :name, :password)');
            $insertStmt->bindValue(':id', 'admin', PDO::PARAM_STR);
            $insertStmt->bindValue(':name', 'アドミン', PDO::PARAM_STR);
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
            $stmt = $this->pdo->prepare('SELECT name FROM users WHERE id = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
            $name = $stmt->fetch(PDO::FETCH_ASSOC);
            return $name['name'];
        }
        return false;
    }

    // 日報の提出状況を取得
    public function getReportStatus($userId) {
        $stmt = $this->pdo->prepare('SELECT id,writing,status,created_at FROM reports WHERE user_id = :user_id and status IN (0, 1, 2) ORDER BY created_at DESC');
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAppStatus($id) {
        $stmt = $this->pdo->prepare('SELECT writing FROM reports WHERE user_id = :user_id');
        $stmt->bindValue(':user_id', $id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}