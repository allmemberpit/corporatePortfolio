<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>日報一覧</title>
    <link rel="stylesheet" href="/views/css/input.css">
    <script src="/views/js/table.js"></script>
    <script src="/views/js/toggle.js"></script>
</head>
<body>
    <header class="portal-header">
        <div class="header-container">
            <!-- ロゴやサイト名 -->
            <div class="header-logo">
                <a href="#">日報一覧</a>
            </div>

            <!-- ユーザー名
            <div class="header-name">
                <p><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></p>
            </div>-->
            <!-- クリック用のメニューボタン（ハンバーガーボタンなど） -->
            <button type="button" class="menu-toggle-btn" id="menuToggle">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="btn-text">メニュー</span>
            </button>
        </div>

        <!-- 下に伸びるメニュー領域（初期状態は閉じている） -->
        <nav class="dropdown-menu" id="dropdownMenu">
            <ul>
                <li><a href="#">ホーム</a></li>
                <li><a href="index.php?action=admin">戻る</a></li>
                <li><a href="index.php?action=logout" class="logout-link">ログアウト</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <h2>日報一覧</h2>
       
        <?php if (!empty($message)): ?>
            <p style="color: green;"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
        <table class="userTable">
            <thead>
                <tr>
                    <th onclick="sortTable(this,0)">No.<span class="sort-icon">↕</span></th>
                    <th onclick="sortTable(this,1)">ユーザー名<span class="sort-icon">↕</span></th>
                    <th onclick="sortTable(this,2)">内容<span class="sort-icon">↕</span></th>
                    <th onclick="sortTable(this,3)">作成日時<span class="sort-icon">↕</span></th>
                    <th>承認</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($report)): ?>
                    <?php foreach ($report as $index => $report_item): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($report_item['name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($report_item['writing'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($report_item['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <form action="index.php?action=acceptReport&id=<?= $report_item['id'] ?>" method="POST">
                                    <input type="submit" value="承認" class="btn">
                                    <p style="display: none;" name="user_id"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
                                </form>
                                <form action="index.php?action=rejectReport&id=<?= $report_item['id'] ?>" method="POST">
                                    <input type="submit" value="却下" class="btn">
                                    <p style="display: none;" name="user_id"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">日報がありません。</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
       
        
        <a href="index.php?action=admin">戻る</a>
        <a href="index.php?action=logout">ログアウト</a>
    </div>
</body>
</html>