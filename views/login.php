<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
    <link rel="stylesheet" href="css/input.css">
</head>
<body>
    <h2>ログイン画面</h2>
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <form action="index.php?action=login_process" method="POST">
        <div>
            <label for="id">ID:</label>
            <input type="text" id="id" name="id" required>
        </div>
        <br>
        <div>
            <label for="password">パスワード:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <br>
        <button type="submit">ログイン</button>
    </form>
</body>
</html>