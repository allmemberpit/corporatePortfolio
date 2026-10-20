<?php
// スクリプト全体のタイムゾーンを日本時間に設定
    date_default_timezone_set('Asia/Tokyo');
class adminModel{
    private $pdo;
    private $now;

    public function __construct() {
        $this->now = date('Y-m-d H:i:s');
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

    public function getReportById($id) {
        $stmt = $this->pdo->prepare("SELECT id,writing,created_at 
            FROM reports WHERE id = :id AND status = :status");
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->bindValue(':status', '0', PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getApplicationByUserId($userId) {
        $stmt = $this->pdo->prepare("SELECT id,category,created_at 
            FROM applications WHERE user_id = :user_id AND status = :status");
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_STR);
        $stmt->bindValue(':status', '0', PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllReports(){
        $stmt = $this->pdo->prepare("SELECT reports.id, reports.user_id, reports.writing, reports.created_at, users.name 
            FROM reports 
            JOIN users ON reports.user_id = users.id 
            WHERE reports.status = :status");
        $stmt->bindValue(':status', '0', PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRestApps(){
        $stmt = $this->pdo->prepare("SELECT app.id AS application_id,u.name AS applicant_name, 
            rest.reason,app.created_at,rest.leave_type,rest.target_date
            FROM applications app
            INNER JOIN rest_applications rest ON app.id = rest.application_id
            LEFT JOIN users u ON app.user_id = u.id
            WHERE app.category = :category and app.status = :status ORDER BY app.created_at DESC");
        $stmt->bindValue(':category', 1, PDO::PARAM_INT);
        $stmt->bindValue(':status', 0, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTripApps(){
        $stmt = $this->pdo->prepare("SELECT app.id AS application_id,u.name AS applicant_name, 
            trip.purpose,app.created_at,trip.destination,trip.start_date,trip.end_date,trip.cost,trip.lodging,trip.detail
            FROM applications app
            INNER JOIN trip_applications trip ON app.id = trip.application_id
            LEFT JOIN users u ON app.user_id = u.id
            WHERE app.category = :category and app.status = :status ORDER BY app.created_at DESC");
        $stmt->bindValue(':category', 2, PDO::PARAM_INT);
        $stmt->bindValue(':status', 0, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getExpenseApps(){
        $stmt = $this->pdo->prepare("SELECT app.id AS application_id,u.name AS applicant_name, 
            expense.purpose,app.created_at,expense.amount,expense.expense_date,expense.payee,expense.category,expense.detail
            FROM applications app
            INNER JOIN expense_applications expense ON app.id = expense.application_id
            LEFT JOIN users u ON app.user_id = u.id
            WHERE app.category = :category and app.status = :status ORDER BY app.created_at DESC");
        $stmt->bindValue(':category', 3, PDO::PARAM_INT);
        $stmt->bindValue(':status', 0, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRegularApps(){
        $stmt = $this->pdo->prepare("SELECT app.id AS application_id,u.name AS applicant_name, 
            app.created_at,regular.start_date,regular.apply_type,regular.departure,regular.destination,regular.viaRoute,regular.period,regular.passAmount
            FROM applications app
            INNER JOIN regular_applications regular ON app.id = regular.application_id
            LEFT JOIN users u ON app.user_id = u.id
            WHERE app.category = :category and app.status = :status ORDER BY app.created_at DESC");
        $stmt->bindValue(':category', 4, PDO::PARAM_INT);
        $stmt->bindValue(':status', 0, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function confirmationPassword($id, $oldPw){
        $stmt = $this->pdo->prepare('SELECT password FROM users where id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        $password = $stmt->fetch(PDO::FETCH_ASSOC)['password'];
        if(password_verify($oldPw, $password)){
            return true;
        }
        return false;
    }

    public function changePassword($id, $newPw ){
        try {
            // 1. トランザクション開始
            $this->pdo->beginTransaction();
           
            // 4. userテーブルを変更
            $sqlChange = "UPDATE users SET password = :password WHERE id=:id";

            $stmtChange = $this->pdo->prepare($sqlChange);
            $stmtChange->bindValue(':id', $id, PDO::PARAM_STR);
            $stmtChange->bindValue(':password', password_hash($newPw, PASSWORD_DEFAULT), PDO::PARAM_STR);
            $stmtChange->execute();
           
            // 5. すべて成功したらコミット（DBへ確定保存）
            $this->pdo->commit();
            return 'パスワードが変更されました'; 
        } catch (Exception $e) {
            // エラーが発生した場合はロールバック（途中の書き込みを取り消し）
            $this->pdo->rollBack();
            return error_log("パスワード変更エラー: " . $e->getMessage());;
        }
    }
}