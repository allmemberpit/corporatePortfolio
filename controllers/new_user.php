<?php
require_once '../models/new_userModel.php';

class new_userController {
    private $new_userModel;


    public function newUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $password = $_POST['password'] ?? '';

            // ユーザー登録処理
            if ($this->new_userModel->newUser($id, $password)) {
                // 登録成功時の処理（例: ダッシュボードにリダイレクト）
                header('Location: ../index.php?action=dashboard');
                exit;
            } else {
                // 登録失敗時の処理（例: エラーメッセージ表示）
                $error = 'ユーザー登録に失敗しました。';
                require '../views/admin.php';
            }
        }
    }
}