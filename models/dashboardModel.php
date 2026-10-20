<?php
  // スクリプト全体のタイムゾーンを日本時間に設定
    date_default_timezone_set('Asia/Tokyo');
class dashboardModel{
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

    // レポート送信処理
    public function submitReport($userId, $writing) {
        $insertStmt = $this->pdo->prepare('INSERT INTO reports (user_id, writing, status,created_at, updated_at) 
            VALUES (:user_id, :writing, :status, :created_at, :updated_at)');
        $insertStmt->execute([
            ':user_id' => $userId,
            ':writing' => $writing,
            ':status' => 0, // 例: 0は未処理、1は処理済み
            ':created_at' => $this->now,
            ':updated_at' => $this->now
        ]);
    }
    // 日報確認処理
    public function staffCheckReport($reportId) {
        $updateStmt = $this->pdo->prepare('UPDATE reports SET status = 3, updated_at = :updated_at WHERE id = :id');
        $updateStmt->execute([
            ':id' => $reportId,
            ':updated_at' => $this->now
        ]);
    }
    // 日報修正処理
    public function modifyReport($reportId, $writing) {
        $updateStmt = $this->pdo->prepare('UPDATE reports SET writing = :writing, status = 0, updated_at = :updated_at WHERE id = :id');
        $updateStmt->execute([
            ':id' => $reportId,
            ':writing' => $writing,
            ':updated_at' => $this->now
        ]);
    }
    //休暇申請処理 
    public function submitRest($userId, $leave_type, $target_date, $reason, $memo) {
        try {
            // 1. トランザクション開始
            $this->pdo->beginTransaction();
            // 3. 発行された base_requests の id (PRIMARY KEY) を取得
            $requestId = $this->insertApplications($userId,1);
           

            // 4. 有休詳細テーブル（rest_applications）へ登録
            $sqlLeave = "INSERT INTO rest_applications (application_id, leave_type, target_date, reason, memo
                         ) VALUES (:application_id, :leave_type, :target_date, :reason, :memo)";

            $stmtLeave = $this->pdo->prepare($sqlLeave);
            $stmtLeave->bindValue(':application_id', $requestId, PDO::PARAM_INT);
            $stmtLeave->bindValue(':leave_type', $leave_type, PDO::PARAM_STR); // 'full', 'am', 'pm'
            $stmtLeave->bindValue(':target_date', $target_date, PDO::PARAM_STR);
            $stmtLeave->bindValue(':reason', $reason, PDO::PARAM_STR);
            $stmtLeave->bindValue(':memo', $memo, PDO::PARAM_STR);
            $stmtLeave->execute();

            // 5. すべて成功したらコミット（DBへ確定保存）
            $this->pdo->commit();
            return '休暇申請が送信されました。'; // 成功した場合は true を返す
        } catch (Exception $e) {
            // エラーが発生した場合はロールバック（途中の書き込みを取り消し）
            $this->pdo->rollBack();
            error_log("有休申請登録エラー: " . $e->getMessage());
            return error_log("有休申請登録エラー: " . $e->getMessage());;
        }
    }
    // 出張申請処理
    public function submitTrip($userId, $start_date, $end_date, $destination, 
                                    $purpose, $estimated_cost, $has_lodging, $detail) {
        try {
            // 1. トランザクション開始
            $this->pdo->beginTransaction();

            // 3. 共通親テーブル（applications）へ登録して発行された base_requests の id (PRIMARY KEY) を取得
            $requestId = $this->insertApplications($userId,2);
            // 4. 出張詳細テーブル（trip_applications）へ登録
            $sqlTrip = "INSERT INTO trip_applications (application_id, start_date, end_date, destination, purpose, cost, lodging, detail
                         ) VALUES (:application_id, :start_date, :end_date, :destination, :purpose, :estimated_cost, :has_lodging, :detail)";

            $stmtTrip = $this->pdo->prepare($sqlTrip);
            $stmtTrip->bindValue(':application_id', $requestId, PDO::PARAM_INT);
            $stmtTrip->bindValue(':start_date', $start_date, PDO::PARAM_STR);
            $stmtTrip->bindValue(':end_date', $end_date, PDO::PARAM_STR);
            $stmtTrip->bindValue(':destination', $destination, PDO::PARAM_STR);
            $stmtTrip->bindValue(':purpose', $purpose, PDO::PARAM_STR);
            $stmtTrip->bindValue(':estimated_cost', $estimated_cost, PDO::PARAM_INT);
            $stmtTrip->bindValue(':has_lodging', $has_lodging, PDO::PARAM_INT);
            $stmtTrip->bindValue(':detail', $detail, PDO::PARAM_STR);
            $stmtTrip->execute();
            
            $this->pdo->commit();
            return '出張申請が送信されました。'; // 成功した場合はメッセージを返す
        } catch (Exception $e) {
            // エラーが発生した場合はロールバック（途中の書き込みを取り消し）
            $this->pdo->rollBack();
            return "出張申請登録エラー: " . $e->getMessage();
        }
    }
    // 経費精算申請処理
    public function submitExpense($userId, $expense_date, $category, $payee, $amount, $purpose, $receipt,$memo) {
        try {
            // 1. トランザクション開始
            $this->pdo->beginTransaction();
            // 3. 発行された base_requests の id (PRIMARY KEY) を取得
            $requestId = $this->insertApplications($userId,3);
            // 4. 経費精算詳細テーブル（expense_applications）へ登録
            $sqlLeave = "INSERT INTO expense_applications (application_id, expense_date, category, payee, amount, purpose, receipt_path, detail
                         ) VALUES (:application_id, :expense_date, :category, :payee, :amount, :purpose, :receipt, :memo)";

            $stmtLeave = $this->pdo->prepare($sqlLeave);
            $stmtLeave->bindValue(':application_id', $requestId, PDO::PARAM_INT);
            $stmtLeave->bindValue(':expense_date', $expense_date, PDO::PARAM_STR);
            $stmtLeave->bindValue(':category', $category, PDO::PARAM_STR);
            $stmtLeave->bindValue(':payee', $payee, PDO::PARAM_STR);
            $stmtLeave->bindValue(':amount', $amount, PDO::PARAM_INT);
            $stmtLeave->bindValue(':purpose', $purpose, PDO::PARAM_STR);
            $stmtLeave->bindValue(':receipt', $receipt ? base64_encode(file_get_contents($receipt['tmp_name'])) : null, PDO::PARAM_STR);
            $stmtLeave->bindValue(':memo', $memo, PDO::PARAM_STR);
            $stmtLeave->execute();

            // 5. すべて成功したらコミット（DBへ確定保存）
            $this->pdo->commit();
            return '経費申請が送信されました。'; // 成功した場合はメッセージを返す
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return "経費申請登録エラー: " . $e->getMessage();
        }
    }
    // 定期申請処理
    public function submitRegular($userId, $apply_type, $departure, $destination, $viaRoute, $period, $start_date, $amount, $receipt) {
        try {
            // 1. トランザクション開始
            $this->pdo->beginTransaction();
            // 3. 発行された base_requests の id (PRIMARY KEY) を取得
            $requestId = $this->insertApplications($userId,4);
            // 4. 定期申請詳細テーブル（regular_applications）へ登録
            $sqlLeave = "INSERT INTO regular_applications (application_id, apply_type, departure, destination, viaRoute, period, start_date, passAmount, receipt_path
                         ) VALUES (:application_id, :apply_type, :departure, :destination, :viaRoute, :period, :start_date, :amount, :receipt)";

            $stmtLeave = $this->pdo->prepare($sqlLeave);
            $stmtLeave->bindValue(':application_id', $requestId, PDO::PARAM_INT);
            $stmtLeave->bindValue(':apply_type', $apply_type, PDO::PARAM_STR);
            $stmtLeave->bindValue(':departure', $departure  , PDO::PARAM_STR);
            $stmtLeave->bindValue(':destination', $destination, PDO::PARAM_STR);
            $stmtLeave->bindValue(':viaRoute', $viaRoute, PDO::PARAM_STR);
            $stmtLeave->bindValue(':period', $period, PDO::PARAM_STR);
            $stmtLeave->bindValue(':start_date', $start_date, PDO::PARAM_STR);
            $stmtLeave->bindValue(':amount', $amount, PDO::PARAM_INT);
            $stmtLeave->bindValue(':receipt', $receipt ? base64_encode(file_get_contents($receipt['tmp_name'])) : null, PDO::PARAM_STR);
                    
            $stmtLeave->execute();  
            $this->pdo->commit();
            return '定期申請が送信されました。'; // 成功した場合はメッセージを返す
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return "定期申請登録エラー: " . $e->getMessage();
        }
    }
    private function insertApplications($userId, $category) {
        $sqlBase = "INSERT INTO applications (user_id, category, status, created_at, updated_at
                    ) VALUES (:user_id, :category, :status, :created_at, :updated_at)";
        
        $stmtBase = $this->pdo->prepare($sqlBase);
        $stmtBase->bindValue(':user_id', $userId, PDO::PARAM_STR);
        $stmtBase->bindValue(':category', $category, PDO::PARAM_INT); // 例: 1は有休申請、2は出張申請
        $stmtBase->bindValue(':status', 0, PDO::PARAM_INT); // 例: 0は未処理
        $stmtBase->bindValue(':created_at', $this->now, PDO::PARAM_STR);
        $stmtBase->bindValue(':updated_at', $this-> now, PDO::PARAM_STR);
        $stmtBase->execute();

        // 発行された base_requests の id (PRIMARY KEY) を取得
        return $this->pdo->lastInsertId();
    }

    // 申請確認処理
    public function staffCheckApplication($id) {
        $sqlBase = "UPDATE applications SET status = :status, updated_at = :updated_at WHERE id = :id";
        
        $stmtBase = $this->pdo->prepare($sqlBase);
        $stmtBase->bindValue(':id', $id, PDO::PARAM_INT);
        $stmtBase->bindValue(':status', 3, PDO::PARAM_INT); // 例: 3は部下確認済み
        $stmtBase->bindValue(':updated_at', $this-> now, PDO::PARAM_STR);
        $stmtBase->execute();
    }

    // 休暇申請修正のデータを取得する処理
    public function getRejectedRests($id) {
        $sqlBase = 'SELECT * FROM applications app
                        INNER JOIN rest_applications rest ON app.id = rest.application_id where user_id = :user_id';
        return $this->getRejected($sqlBase, $id);
    }
    //出張修正申請のデータを取得する処理
    public function getRejectedTrips($id) {
        $stmt = $this->pdo->prepare('SELECT * FROM applications app
                        INNER JOIN trip_applications trip ON app.id = trip.application_id where user_id = :user_id');
        $stmt->bindValue(':user_id', $id, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);     
    }
    //経費申請修正のデータを取得する処理
    public function getRejectedExpens($id) {
        $stmt = $this->pdo->prepare('SELECT * FROM applications app
                        INNER JOIN expense_applications exp ON app.id = exp.application_id where user_id = :user_id');
        $stmt->bindValue(':user_id', $id, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);  
    }

    //定期申請修正のデータを取得する処理
    public function getRejectedRegulars($userId){
        $stmt = $this->pdo->prepare('SELECT * FROM applications app
                        INNER JOIN regular_applications reg ON app.id = reg.application_id where user_id = :user_id');
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);  
    }

    private function getRejected( $sqlBase, $id ) {
        $stmt = $this->pdo->prepare($sqlBase);
        $stmt->bindValue(':user_id', $id, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);  
    }
    public function getApplication($id) {
        $sql = "SELECT category FROM applications WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        switch ($category['category']) {
            case 1:
                $sql = "SELECT * FROM applications app
                        INNER JOIN rest_applications rest ON app.id = rest.application_id
                        WHERE app.id = :id";
                break;
            case 2:
                $sql = "SELECT * FROM applications app
                        INNER JOIN trip_applications trip ON app.id = trip.application_id
                        WHERE app.id = :id";
                break;
            case 3:
                $sql = "SELECT * FROM applications app
                        INNER JOIN expense_applications exp ON app.id = exp.application_id
                        WHERE app.id = :id";
                break;
            case 4:
                $sql = "SELECT * FROM applications app
                        INNER JOIN regular_applications reg ON app.id = reg.application_id
                        WHERE app.id = :id";
                break; 
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateRest($id, $leave_type, $target_date, $reason, $memo) {
        try {
            // 1. トランザクション開始
            $this->pdo->beginTransaction();
            // 3. 発行された base_requests の id (PRIMARY KEY) を取得
            $this->updateApplications($id);
           
            // 4. 有休詳細テーブル（rest_applications）へ登録
            $sqlLeave = "UPDATE rest_applications SET  leave_type=:leave_type, target_date=:target_date, reason=:reason, memo=:memo
                          WHERE application_id=:application_id";

            $stmtLeave = $this->pdo->prepare($sqlLeave);
            $stmtLeave->bindValue(':application_id', $id, PDO::PARAM_INT);
            $stmtLeave->bindValue(':leave_type', $leave_type, PDO::PARAM_STR); // 'full', 'am', 'pm'
            $stmtLeave->bindValue(':target_date', $target_date, PDO::PARAM_STR);
            $stmtLeave->bindValue(':reason', $reason, PDO::PARAM_STR);
            $stmtLeave->bindValue(':memo', $memo, PDO::PARAM_STR);
            $stmtLeave->execute();

            // 5. すべて成功したらコミット（DBへ確定保存）
            $this->pdo->commit();
            return '休暇申請が修正されました。'; // 成功した場合は true を返す
        } catch (Exception $e) {
            // エラーが発生した場合はロールバック（途中の書き込みを取り消し）
            $this->pdo->rollBack();
            return error_log("有休申請修正エラー: " . $e->getMessage());;
        }
    }

    public function updateTrip($id,  $start_date, $end_date, $destination, $purpose, $estimated_cost, $has_lodging, $detail) {
        try {
            // 1. トランザクション開始
            $this->pdo->beginTransaction();
            // 3. 発行された base_requests の id (PRIMARY KEY) を取得
            $this->updateApplications($id);
           
            // 4. 出張詳細テーブル（trip_applications）へ登録
            $sqlTrip = "UPDATE trip_applications SET start_date=:start_date, end_date=:end_date, destination=:destination, purpose=:purpose, cost=:estimated_cost, lodging=:has_lodging, detail=:detail
                          WHERE application_id=:application_id";

            $stmtTrip = $this->pdo->prepare($sqlTrip);
            $stmtTrip->bindValue(':application_id', $id, PDO::PARAM_INT);
            $stmtTrip->bindValue(':start_date', $start_date, PDO::PARAM_STR);
            $stmtTrip->bindValue(':end_date', $end_date, PDO::PARAM_STR);
            $stmtTrip->bindValue(':destination', $destination, PDO::PARAM_STR);
            $stmtTrip->bindValue(':purpose', $purpose, PDO::PARAM_STR);
            $stmtTrip->bindValue(':estimated_cost', $estimated_cost, PDO::PARAM_INT);
            $stmtTrip->bindValue(':has_lodging', $has_lodging, PDO::PARAM_INT);
            $stmtTrip->bindValue(':detail', $detail, PDO::PARAM_STR);
            $stmtTrip->execute();

            // 5. すべて成功したらコミット（DBへ確定保存）
            $this->pdo->commit();
            return '出張申請が修正されました。'; // 成功した場合は true を返す
        } catch (Exception $e) {
            // エラーが発生した場合はロールバック（途中の書き込みを取り消し）
            $this->pdo->rollBack();
            return error_log("出張申請修正エラー: " . $e->getMessage());;
        }
    }    

    public function updateExpense($id, $expense_date, $category, $payee, $amount, $purpose, $receipt,$memo) {  
        try {
            // 1. トランザクション開始
            $this->pdo->beginTransaction();
            // 3. 発行された base_requests の id (PRIMARY KEY) を取得
            $this->updateApplications($id);
           
            // 4. 経費詳細テーブル（trip_applications）へ登録
            $sqlExp = "UPDATE expense_applications SET expense_date=:expense_date, category=:category, payee=:payee, amount=:amount, purpose=:purpose, receipt_path=:receipt, detail=:memo
                          WHERE application_id=:application_id";

            $stmtTrip = $this->pdo->prepare($sqlExp);
            $stmtTrip->bindValue(':application_id', $id, PDO::PARAM_INT);
            $stmtTrip->bindValue(':expense_date', $expense_date, PDO::PARAM_STR);
            $stmtTrip->bindValue(':category', $category, PDO::PARAM_STR);
            $stmtTrip->bindValue(':payee', $payee, PDO::PARAM_STR);
            $stmtTrip->bindValue(':amount', $amount, PDO::PARAM_INT);
            $stmtTrip->bindValue(':purpose', $purpose, PDO::PARAM_STR);
            $stmtTrip->bindValue(':receipt', $receipt ? base64_encode(file_get_contents($receipt['tmp_name'])) : null, PDO::PARAM_STR);
            $stmtTrip->bindValue(':memo', $memo, PDO::PARAM_STR);
            $stmtTrip->execute();

            // 5. すべて成功したらコミット（DBへ確定保存）
            $this->pdo->commit();
            return '経費申請が修正されました。'; // 成功した場合は true を返す
        } catch (Exception $e) {
            // エラーが発生した場合はロールバック（途中の書き込みを取り消し）
            $this->pdo->rollBack();
            return error_log("経費申請修正エラー: " . $e->getMessage());;
        }
    }

    public function updateRegular($id, $apply_type, $departure, $destination, $viaRoute, $period, $start_date, $amount, $receipt){
        try {
            // 1. トランザクション開始
            $this->pdo->beginTransaction();
            // 3. 発行された base_requests の id (PRIMARY KEY) を取得
            $this->updateApplications($id);
           
            // 4. 定期詳細テーブル（regular_applications）へ登録
            $sqlLeave = "UPDATE regular_applications SET apply_type=:apply_type, departure=:departure, destination=:destination, viaRoute=:viaRoute, period=:period, start_date=:start_date, passAmount=:amount, receipt_path=:receipt
                          WHERE application_id=:application_id";

            $stmtLeave = $this->pdo->prepare($sqlLeave);
            $stmtLeave->bindValue(':application_id', $id, PDO::PARAM_INT);
            $stmtLeave->bindValue(':apply_type', $apply_type, PDO::PARAM_STR);
            $stmtLeave->bindValue(':departure', $departure  , PDO::PARAM_STR);
            $stmtLeave->bindValue(':destination', $destination, PDO::PARAM_STR);
            $stmtLeave->bindValue(':viaRoute', $viaRoute, PDO::PARAM_STR);
            $stmtLeave->bindValue(':period', $period, PDO::PARAM_STR);
            $stmtLeave->bindValue(':start_date', $start_date, PDO::PARAM_STR);
            $stmtLeave->bindValue(':amount', $amount, PDO::PARAM_INT);
            $stmtLeave->bindValue(':receipt', $receipt ? base64_encode(file_get_contents($receipt['tmp_name'])) : null, PDO::PARAM_STR);
            $stmtLeave->execute();
           
            // 5. すべて成功したらコミット（DBへ確定保存）
            $this->pdo->commit();
            return '定期申請が修正されました。'; // 成功した場合は true を返す
        } catch (Exception $e) {
            // エラーが発生した場合はロールバック（途中の書き込みを取り消し）
            $this->pdo->rollBack();
            return error_log("定期申請修正エラー: " . $e->getMessage());;
        }
    }

    private function updateApplications($id){
        $sqlBase = "UPDATE applications SET status=:status,  updated_at=:updated_at WHERE id = :id";
        
        $stmtBase = $this->pdo->prepare($sqlBase);
        $stmtBase->bindValue(':id', $id, PDO::PARAM_STR);
        $stmtBase->bindValue(':status', 0, PDO::PARAM_INT); // 例: 0は未処理
        $stmtBase->bindValue(':updated_at', $this-> now, PDO::PARAM_STR);
        $stmtBase->execute();
        return;
    }
}