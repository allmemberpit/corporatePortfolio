<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ダッシュボード</title>
</head>
<body>
    <h2>ログイン成功</h2>
    <p>ようこそ、<strong><?= htmlspecialchars($userId, ENT_QUOTES, 'UTF-8') ?></strong> さん！</p>

    <a href="index.php?action=logout">ログアウト</a>
</body>
</html>