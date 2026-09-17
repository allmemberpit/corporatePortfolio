<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ダッシュボード</title>
    <link rel="stylesheet" href="views/css/input.css">
</head>
<body>
    <div class="container">
        <h2>ダッシュボード</h2>
        <p>ログインに成功しました。</p>
        <h2>ログイン成功</h2>
        <p>ようこそ、<strong><?= htmlspecialchars($userId, ENT_QUOTES, 'UTF-8') ?></strong> さん！</p>

        <a href="index.php?action=logout">ログアウト</a>
    </div>
</body>
</html>