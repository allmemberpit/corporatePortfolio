<?php
date_default_timezone_set('Asia/Tokyo');
class UserModel {
    private $pdo;
    private $now;
    public function __construct() {
        $this->now = date('Y-m-d H:i:s');
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

         $sql = "CREATE TABLE IF NOT EXISTS reports (
                id INTEGER PRIMARY KEY,
                user_id TEXT NOT NULL,
                writing TEXT NOT NULL,
                status INTEGER NOT NULL,
                created_at DATETIME default current_timestamp,
                updated_at DATETIME default current_timestamp
            )";
            $this->pdo->exec($sql);

        //申請書全体のテーブルの作成
        $sql = "CREATE TABLE IF NOT EXISTS applications (
            id INTEGER PRIMARY KEY,
            user_id TEXT NOT NULL,
            category integer NOT NULL,
            status integer NOT NULL,
            created_at DATETIME default current_timestamp,
            updated_at DATETIME default current_timestamp,
            FOREIGN KEY (user_id) REFERENCES users(id)
        )";
        $this->pdo->exec($sql);
        //休暇申請のテーブルの作成
        $sql = "CREATE TABLE IF NOT EXISTS rest_applications (
            id INTEGER PRIMARY KEY,
            application_id INTEGER NOT NULL,
            leave_type TEXT NOT NULL,
            target_date DATE NOT NULL,
            reason TEXT,
            memo TEXT,
            FOREIGN KEY (application_id) REFERENCES applications(id)
        )";
        $this->pdo->exec($sql);
        //出張申請のテーブルの作成
        $sql = "CREATE TABLE IF NOT EXISTS trip_applications (
            id INTEGER PRIMARY KEY,
            application_id INTEGER NOT NULL,
            destination TEXT NOT NULL,
            start_date DATE NOT NULL,
            end_date DATE NOT NULL,
            purpose TEXT NOT NULL,
            cost integer NOT NULL,
            lodging integer NOT NULL,
            detail TEXT,
            FOREIGN KEY (application_id) REFERENCES applications(id)
        )";
        $this->pdo->exec($sql);
        //経費精算のテーブルの作成
        $sql =  "CREATE TABLE IF NOT EXISTS expense_applications (
            id INTEGER PRIMARY KEY,
            application_id INTEGER NOT NULL,
            expense_date DATE NOT NULL,
            category TEXT NOT NULL,
            payee TEXT NOT NULL,
            amount integer NOT NULL,
            purpose TEXT NOT NULL,
            detail TEXT,
            receipt_path TEXT,
            FOREIGN KEY (application_id) REFERENCES applications(id)
        )";
        $this->pdo->exec($sql);
        //定期申請のテーブルの作成
        $sql = "CREATE TABLE IF NOT EXISTS regular_applications (
            id INTEGER PRIMARY KEY,
            application_id INTEGER NOT NULL,
            apply_type TEXT NOT NULL,
            departure TEXT NOT NULL,
            destination TEXT NOT NULL,
            viaRoute TEXT,
            period integer NOT NULL,
            start_date DATE NOT NULL,
            passAmount integer NOT NULL,
            receipt_path TEXT,
            FOREIGN KEY (application_id) REFERENCES applications(id)
        )";
        $this->pdo->exec($sql);
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
    // 申請の提出状況を取得
    public function getApplicationStatus($userId) {
        $stmt = $this->pdo->prepare('SELECT id,category,status,created_at FROM applications WHERE user_id = :user_id and status IN (0, 1, 2) ORDER BY created_at DESC');
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}