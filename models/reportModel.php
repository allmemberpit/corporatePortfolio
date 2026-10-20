<?php
 // スクリプト全体のタイムゾーンを日本時間に設定
    date_default_timezone_set('Asia/Tokyo');
class reportModel{
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

    // レポート承認処理
    public function acceptReport($id,$status) {
        $updatesql = "UPDATE reports 
            SET status = :status, updated_at = :updated_at WHERE id = :id";
        $stmt = $this->pdo->prepare($updatesql);
        $stmt->bindValue(':status', $status, PDO::PARAM_INT);
        $stmt->bindValue(':updated_at', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        return $stmt->execute();
    }
}