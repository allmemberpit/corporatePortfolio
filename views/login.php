<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
    <link rel="stylesheet" href="views/css/input.css">
</head>
<body>
    <div class="container">
        <h2>ログイン画面</h2>
        <?php if (!empty($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <form action="index.php?action=login_process" method="POST">
            <div>
                <label for="id">ID:</label>
                <input type="text" id="id" name="id" required>
            </div>
            <div>
                <label for="password">PW:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">ログイン</button>
        </form>
    </div>
</body>
</html>