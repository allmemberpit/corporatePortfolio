<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>管理者</title>
    <link rel="stylesheet" href="/views/css/input.css">
    <script src="/views/js/admin.js"></script>
    <script src="/views/js/table.js"></script>
    <script src="/views/js/toggle.js"></script>
</head>
<body>
    <?php  $default_reset_password = 'password123';?>
    <header class="portal-header">
        <div class="header-container">
            <!-- ロゴやサイト名 -->
            <div class="header-logo">
                <a href="index.php?action=admin">管理者ページ</a>
            </div>

            <!-- ユーザー名-->
            <div class="header-name">
                <p><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></p>
            </div>
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
                <li><a href="index.php?action=admin">ホーム</a></li>
                <li><a href="index.php?action=logout" class="logout-link">ログアウト</a></li>
                <li><a id="changePw">パスワード変更</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <h2>ダッシュボード</h2>
        <p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
        <h3>ログイン成功</h3>
        <p>ようこそ、<strong><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></strong> さん！</p>
        <h3>日報申請書確認</h3>
        <div class="checkBtns">
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
                    <th onclick="sortTable(this,0)">No.<span class="sort-icon">↕</span></th>
                    <th onclick="sortTable(this,1)">ユーザーID<span class="sort-icon">↕</span></th>
                    <th onclick="sortTable(this,2)">ユーザー名<span class="sort-icon">↕</span></th>
                    <th>パスワードリセット</th>
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
                                <form class="resetPw" action="/index.php?action=resetPw" method="post" 
                                 onsubmit="return confirmReset('<?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8'); ?>', '<?= htmlspecialchars($default_reset_password, ENT_QUOTES, 'UTF-8') ?>');">
                                    <input type="submit" value="リセット" class="btn ">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($user['id'], ENT_QUOTES, 'UTF-8') ?>">
                                    <input type="hidden" name="pw" value="<?= htmlspecialchars($default_reset_password, ENT_QUOTES, 'UTF-8') ?>">
                                    <input type="hidden" name="adminName" value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>">
                                </form>
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
            <form id="changePw" action="/index.php?action=changePwAdmin" method="post">
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
                <input type="hidden" name="name" value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>">
            </form>
             <button class="btn" id="cancelBtn">キャンセル</button>
        </div>
    </div>
</body>
</html>