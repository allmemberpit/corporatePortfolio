<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>管理者</title>
    <link rel="stylesheet" href="/views/css/input.css">
</head>
<body>
    <div class="container">
        <h2>ダッシュボード</h2>
        <p>ログインに成功しました。</p>
        <h3>ログイン成功</h3>
        <p>ようこそ、<strong><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></strong> さん！</p>
        
        
        <?php if (!empty($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
        <table class="userTable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>ユーザーID</th>
                    <th>ユーザー名</th>
                    <th>日報</th>
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
                                <?php if($index !=0): ?>
                                    <form action="index.php?action=checkReport&user_id=<?= $user['id'] ?>" method="POST">
                                        <input type="submit" value="日報を確認" class="btn">
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">ユーザーが登録されていません。</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
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
</body>
</html>