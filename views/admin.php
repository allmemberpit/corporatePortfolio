<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>管理者</title>
    <link rel="stylesheet" href="/views/css/input.css">
    <script src="/views/js/admin.js"></script>
</head>
<body>
    <div class="container">
        <h2>ダッシュボード</h2>
        <p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
        <h3>ログイン成功</h3>
        <p>ようこそ、<strong><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></strong> さん！</p>
        <h3>日報申請書確認</h3>
        <div>
            <form action="index.php?action=allReports" method="POST">
                <input type="submit" value="日報を確認" class="btn">
            </form>
            <form action="index.php?action=allApplications" method="POST">
                <input type="submit" value="申請を確認" class="btn">
            </form>
        </div>
        
        <?php if (!empty($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
        <h3>ユーザー一覧</h3>
        <table class="userTable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>ユーザーID</th>
                    <th>ユーザー名</th>
                    <th>パスワード変更</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $index => $user): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($user['id'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <input type="submit" value="パスワード変更" data-id="<?= htmlspecialchars($user['id'], ENT_QUOTES, 'UTF-8') ?>" class="btn changePw">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">ユーザーが登録されていません。</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <h3>新規ユーザー登録</h3>
        <form action="index.php/?action=newUser" method="POST">
            <div>
                <label for="id">ID:</label>
                <input type="text" id="id" name="id" required>
            </div>
            <div>
                <label for="name">名前:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div>
                <label for="password">PW:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <input id="adminName" type="hidden" style="display:none" name="adminName" value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>">
            <button type="submit" class="btn">新規登録</button>
        </form>
        
        <a href="index.php?action=logout">ログアウト</a>
    </div>
    <div id="pwModal" class="modal">
        <div class="modal-content">
            <h3>パスワード変更</h3>
            <form id="changePw" action="/index.php?action=changePw" method="post">
                <input id="pwId" type="hidden" name="id">
                <label for="oldPw">元のパスワード:</label><br>
                <input type="password" id="oldPw" name="oldPw" rows="1" cols="20"><br>
                <label for="oldPw">新しいパスワード:</label><br>
                <input type="password" id="newPw" name="newPw" rows="1" cols="20"><br>
                <p id="npCaution" class="caution">元のパスワードと同じです</p>
                <label for="oldPw">新しいパスワードの確認:</label><br>
                <input type="password" id="confPw" cols="20" rows="1"><br>
                <p id="cpCaution" class="caution">新しいパスワードが一致しません</p>
                <input id="submitBtn" type="submit" class="btn submit" value="送信">
            </form>
             <button class="btn" id="cancelBtn">キャンセル</button>
        </div>
    </div>
</body>
</html>