<?php
 // スクリプト全体のタイムゾーンを日本時間に設定
    date_default_timezone_set('Asia/Tokyo');
class profileModel{
    private $pdo,$now;

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

    public function getProfileByName($id) {
        try {
            $sql = "SELECT * FROM profiles WHERE user_id = :user_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':user_id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('プロフィール情報の取得エラー: ' . $e->getMessage());
        }
    }

    public function saveProfile($id, $user_name, $birthday, $address, 
        $nearest_station, $word, $excel, $powerpoint, 
        $outlook, $spreadsheet, $teams, $adobe, $cloud, 
        $accounting, $erp, $office_other, $python, $html, 
        $javascript, $typescript, $php, $java, $csharp, $ruby, 
        $cplusplus, $mysql, $go, $programming_other) {
        // プロフィール情報をデータベースに保存する処理
        try {
            //userの名前も変更
            $sql = "UPDATE users SET name = :name WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':name', $user_name);
            $stmt->execute();

            // プロフィール情報を更新するSQL文
            $sql = "UPDATE profiles SET name = :name, birthday = :birthday, address = :address, nearest_station = :nearest_station, word = :word, excel = :excel, powerpoint = :powerpoint, outlook = :outlook, spreadsheet = :spreadsheet, teams = :teams, adobe = :adobe, cloud = :cloud, accounting = :accounting, erp = :erp, office_other = :office_other, python = :python, html = :html, javascript = :javascript, typescript = :typescript, php = :php, java = :java, csharp = :csharp, ruby = :ruby, cplusplus = :cplusplus, sql = :sql, go = :go, programming_other = :programming_other, updated_at = :updated_at WHERE user_id = :user_id";
            $stmt = $this->pdo->prepare($sql);
            // パラメータをバインド
            $stmt->bindValue(':user_id', $id);
            $stmt->bindValue(':name', $user_name);
            $stmt->bindValue(':birthday', $birthday);
            $stmt->bindValue(':address', $address);
            $stmt->bindValue(':nearest_station', $nearest_station);
            $stmt->bindValue(':word', $word);
            $stmt->bindValue(':excel', $excel);
            $stmt->bindValue(':powerpoint', $powerpoint);
            $stmt->bindValue(':outlook', $outlook);
            $stmt->bindValue(':spreadsheet', $spreadsheet);
            $stmt->bindValue(':teams', $teams);
            $stmt->bindValue(':adobe', $adobe);
            $stmt->bindValue(':cloud', $cloud);
            $stmt->bindValue(':accounting', $accounting);
            $stmt->bindValue(':erp', $erp);
            $stmt->bindValue(':office_other', $office_other);
            $stmt->bindValue(':python', $python);
            $stmt->bindValue(':html', $html);
            $stmt->bindValue(':javascript', $javascript);
            $stmt->bindValue(':typescript', $typescript);
            $stmt->bindValue(':php', $php);
            $stmt->bindValue(':java', $java);
            $stmt->bindValue(':csharp', $csharp);
            $stmt->bindValue(':ruby', $ruby);
            $stmt->bindValue(':cplusplus', $cplusplus);
            $stmt->bindValue(':sql', $mysql);
            $stmt->bindValue(':go', $go);
            $stmt->bindValue(':programming_other', $programming_other);
            $stmt->bindValue(':updated_at', $this->now);
            // SQL文を実行
            return $stmt->execute();
        } catch (PDOException $e) {
            die('プロフィール情報の保存エラー: ' . $e->getMessage());
        }
    }

}