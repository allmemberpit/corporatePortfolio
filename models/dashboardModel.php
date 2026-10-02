<?php
class dashboardModel{
    private $pdo;

    public function __construct() {
        try {
            // SQLiteデータベースファイルに接続
            $this->pdo = new PDO('sqlite:' . __DIR__ . '/../database.sqlite');
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "CREATE TABLE IF NOT EXISTS reports (
                id INTEGER PRIMARY KEY,
                user_id TEXT NOT NULL,
                writing TEXT NOT NULL,
                status INTEGER NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )";
            $this->pdo->exec($sql);
        } catch (PDOException $e) {
            die('データベース接続エラー: ' . $e->getMessage());
        }
    }

    // レポート送信処理
    public function submitReport($userId, $writing) {
        $insertStmt = $this->pdo->prepare('INSERT INTO reports (user_id, writing, status) 
            VALUES (:user_id, :writing, :status)');
        $insertStmt->execute([
            ':user_id' => $userId,
            ':writing' => $writing,
            ':status' => 0 // 例: 0は未処理、1は処理済み
        ]);
    }
    // 日報確認処理
    public function staffCheckReport($reportId) {
        $updateStmt = $this->pdo->prepare('UPDATE reports SET status = 3, updated_at = CURRENT_TIMESTAMP WHERE id = :id');
        $updateStmt->execute([':id' => $reportId]);
    }
    // 日報修正処理
    public function modifyReport($reportId, $writing) {
        $updateStmt = $this->pdo->prepare('UPDATE reports SET writing = :writing, status = 0, updated_at = CURRENT_TIMESTAMP WHERE id = :id');
        $updateStmt->execute([
            ':id' => $reportId,
            ':writing' => $writing
        ]);
    }
}