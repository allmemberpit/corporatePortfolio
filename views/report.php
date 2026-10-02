<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>日報一覧</title>
    <link rel="stylesheet" href="/views/css/input.css">
</head>
<body>
    <div class="container">
        <h2>日報一覧</h2>
        <p>
            <strong><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></strong> の日報一覧です
        </p>
        
        <?php if (!empty($message)): ?>
            <p style="color: green;"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
        <table class="userTable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>内容</th>
                    <th>作成日時</th>
                    <th>承認</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($report)): ?>
                    <?php foreach ($report as $index => $report_item): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($report_item['writing'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($report_item['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <form action="index.php?action=acceptReport&id=<?= $report_item['id'] ?>&user_id=<?= $user_id ?>" method="POST">
                                    <input type="submit" value="承認" class="btn">
                                    <p style="display: none;" name="user_id"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
                                </form>
                                <form action="index.php?action=rejectReport&id=<?= $report_item['id'] ?>&user_id=<?= $user_id ?>" method="POST">
                                    <input type="submit" value="却下" class="btn">
                                    <p style="display: none;" name="user_id"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">日報がありません。</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
       
        
        <a href="index.php?action=admin">戻る</a>
        <a href="index.php?action=logout">ログアウト</a>
    </div>
</body>
</html>