<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>申請書一覧</title>
    <link rel="stylesheet" href="/views/css/input.css">
    <script src="/views/js/table.js"></script>
    <script src="/views/js/toggle.js"></script>
</head>
<body>
    <header class="portal-header">
        <div class="header-container">
            <!-- ロゴやサイト名 -->
            <div class="header-logo">
                <a href="index.php?action=dashboard">申請一覧</a>
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
                <li><a href="index.php?action=dashboard">ホーム</a></li>
                <li><a href="index.php?action=admin">戻る</a></li>
                <li><a href="index.php?action=logout" class="logout-link">ログアウト</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <h2>申請書一覧</h2>
        
        <?php if (!empty($message)): ?>
            <p style="color: green;"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
        <h3>休暇申請</h3>
        <table class="userTable">
            <thead>
                <tr>
                    <th onclick="sortTable(this,0)">No.<span class="sort-icon">↕</span></th>
                    <th onclick="sortTable(this,1)">ユーザー名<span class="sort-icon">↕</span></th>
                    <th onclick="sortTable(this,2)">対象日付<span class="sort-icon">↕</span></th>
                    <th onclick="sortTable(this,3)">休暇区分<span class="sort-icon">↕</span></th>
                    <th onclick="sortTable(this,4)">理由<span class="sort-icon">↕</span></th>
                    <th onclick="sortTable(this,5)">作成日時<span class="sort-icon">↕</span></th>
                    <th>承認</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($restApps)): ?>
                    <?php foreach ($restApps as $index => $restApp): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($restApp['applicant_name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($restApp['target_date'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?php if ($restApp['leave_type']=='full'): ?>
                                全日
                            <?php elseif ($restApp['leave_type']=='am'): ?>
                                午前
                            <?php elseif ($restApp['leave_type']=='pm'): ?>
                                午後
                            <?php endif; ?></td>
                            <td><?= htmlspecialchars($restApp['reason'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($restApp['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <form action="index.php?action=acceptApp&id=<?= $restApp['application_id'] ?>" method="POST">
                                    <input type="submit" value="承認" class="btn">
                                    <p style="display: none;" name="user_id"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
                                </form>
                                <form action="index.php?action=rejectApp&id=<?= $restApp['application_id'] ?>" method="POST">
                                    <input type="submit" value="却下" class="btn">
                                    <p style="display: none;" name="user_id"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">休暇申請がありません。</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h3>出張申請</h3>
        <table class="userTable">
            <thead>
                <tr>
                    <th onclick="sortTable(this,0)">No.<span class="sort-icon">↕</span></th>
                    <th onclick="sortTable(this,1)">ユーザー名<span class="sort-icon">↕</span></th>
                    <th onclick="sortTable(this,2)">開始日<span class="sort-icon">↕</span></th>
                    <th onclick="sortTable(this,3)">終了日<span class="sort-icon">↕</span></th>
                    <th onclick="sortTable(this,4)">訪問先・目的地<span class="sort-icon">↕</span></th>
                    <th onclick="sortTable(this,5)">目的<span class="sort-icon">↕</span></th>
                    <th onclick="sortTable(this,6)">概算交通費<span class="sort-icon">↕</span></th>
                    <th onclick="sortTable(this,7)">宿泊有無<span class="sort-icon">↕</span></th>
                    <th onclick="sortTable(this,8)">訪問内容・日程案<span class="sort-icon">↕</span></th>
                    <th onclick="sortTable(this,9)">作成日時<span class="sort-icon">↕</span></th>
                    <th onclick="sortTable(this,10)">承認<span class="sort-icon">↕</span></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($tripApps)): ?>
                    <?php foreach ($tripApps as $index => $tripApp): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($tripApp['applicant_name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($tripApp['start_date'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($tripApp['end_date'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($tripApp['destination'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($tripApp['purpose'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td data-value="<?= htmlspecialchars($tripApp['cost'], ENT_QUOTES, 'UTF-8') ?>">
                                <?= htmlspecialchars($tripApp['cost'], ENT_QUOTES, 'UTF-8') ?>円
                            </td>
                            <td>
                                <?php if ($tripApp['lodging']==0): ?>
                                    日帰り
                                <?php elseif ($tripApp['lodging']==1): ?>
                                    宿泊
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($tripApp['detail'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($tripApp['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <form action="index.php?action=acceptApp&id=<?= $tripApp['application_id'] ?>" method="POST">
                                    <input type="submit" value="承認" class="btn">
                                    <p style="display: none;" name="user_id"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
                                </form>
                                <form action="index.php?action=rejectApp&id=<?= $tripApp['application_id'] ?>" method="POST">
                                    <input type="submit" value="却下" class="btn">
                                    <p style="display: none;" name="user_id"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="11">出張申請がありません。</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h3>経費精算</h3>
        <table class="userTable">
            <thead>
                <tr>
                    <th onclick="sortTable(this,0)">No.<span class="sort-icon">↕</span></th>
                    <th onclick="sortTable(this,1)">ユーザー名<span class="sort-icon">↕</span></th>
                    <th onclick="sortTable(this,2)">勘定科目<span class="sort-icon">↕</span></th>
                    <th onclick="sortTable(this,3)">支払先<span class="sort-icon">↕</span></th>
                    <th onclick="sortTable(this,4)">金額<span class="sort-icon">↕</span></th>
                    <th onclick="sortTable(this,5)">使用目的・内容<span class="sort-icon">↕</span></th>
                    <th onclick="sortTable(this,6)">備考<span class="sort-icon">↕</span></th>
                    <th>領収書</th>
                    <th onclick="sortTable(this,7)">作成日時<span class="sort-icon">↕</span></th>
                    <th>承認</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($expenseApps)): ?>
                    <?php foreach ($expenseApps as $index => $expenseApp): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($expenseApp['applicant_name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($expenseApp['category'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($expenseApp['payee'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td data-value="<?= htmlspecialchars($expenseApp['amount'], ENT_QUOTES, 'UTF-8') ?>">
                                <?= htmlspecialchars($expenseApp['amount'], ENT_QUOTES, 'UTF-8') ?>円
                            </td>
                            <td><?= htmlspecialchars($expenseApp['purpose'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($expenseApp['detail'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <button type="button" 
                                        class="view-receipt-btn btn" 
                                        data-receipt="data:image/jpeg;base64,<?= htmlspecialchars($expenseApp['receipt_path'], ENT_QUOTES, 'UTF-8') ?>">
                                    確認
                                </button>
                            </td>
                            <td><?= htmlspecialchars($expenseApp['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <form action="index.php?action=acceptApp&id=<?= $expenseApp['application_id'] ?>" method="POST">
                                    <input type="submit" value="承認" class="btn">
                                    <p style="display: none;" name="user_id"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
                                </form>
                                <form action="index.php?action=rejectApp&id=<?= $expenseApp['application_id'] ?>" method="POST">
                                    <input type="submit" value="却下" class="btn">
                                    <p style="display: none;" name="user_id"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9">経費精算申請がありません。</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
       
        <h3>定期申請</h3>
        <table class="userTable">
            <thead>
                <tr>
                    <th onclick="sortTable(this,0)">No.<span class="sort-icon">↕</span></th>
                    <th onclick="sortTable(this,1)">ユーザー名<span class="sort-icon">↕</span></th>
                    <th onclick="sortTable(this,2)">申請区分<span class="sort-icon">↕</span></th>
                    <th onclick="sortTable(this,3)">乗車駅<span class="sort-icon">↕</span></th>
                    <th onclick="sortTable(this,4)">降車駅<span class="sort-icon">↕</span></th>
                    <th onclick="sortTable(this,5)">利用路線・経由<span class="sort-icon">↕</span></th>
                    <th onclick="sortTable(this,6)">利用開始希望日<span class="sort-icon">↕</span></th>
                    <th onclick="sortTable(this,7)">通用期間<span class="sort-icon">↕</span></th>
                    <th onclick="sortTable(this,8)">定期代<span class="sort-icon">↕</span></th>
                    <th>定期券写し・領収書</th>
                    <th onclick="sortTable(this,9)">作成日時<span class="sort-icon">↕</span></th>
                    <th>承認</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($regularApps)): ?>
                    <?php foreach ($regularApps as $index => $regularApp): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($regularApp['applicant_name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?php if ($regularApp['apply_type']=='new'): ?>
                                    新規
                                <?php elseif ($regularApp['apply_type']=='renew'): ?>
                                    継続
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($regularApp['departure'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($regularApp['destination'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($regularApp['viaRoute'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($regularApp['start_date'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td data-value="<?= htmlspecialchars($regularApp['period'], ENT_QUOTES, 'UTF-8') ?>">
                                <?= htmlspecialchars($regularApp['period'], ENT_QUOTES, 'UTF-8') ?>ヶ月
                            </td>
                            <td data-value="<?= htmlspecialchars($regularApp['passAmount'], ENT_QUOTES, 'UTF-8') ?>">
                                <?= htmlspecialchars($regularApp['passAmount'], ENT_QUOTES, 'UTF-8') ?>円
                            </td>
                            <td>
                                <button type="button" 
                                        class="view-receipt-btn btn" 
                                        data-receipt="data:image/jpeg;base64,<?= htmlspecialchars($regularApp['receipt_path'], ENT_QUOTES, 'UTF-8') ?>">
                                    確認
                                </button>
                            </td>
                            <td><?= htmlspecialchars($regularApp['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <form action="index.php?action=acceptApp&id=<?= $regularApp['application_id'] ?>" method="POST">
                                    <input type="submit" value="承認" class="btn">
                                    <p style="display: none;" name="user_id"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
                                </form>
                                <form action="index.php?action=rejectApp&id=<?= $regularApp['application_id'] ?>" method="POST">
                                    <input type="submit" value="却下" class="btn">
                                    <p style="display: none;" name="user_id"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="12">定期申請がありません。</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="index.php?action=admin">戻る</a>
        <a href="index.php?action=logout">ログアウト</a>
    </div>
    <!-- 画像表示用モーダル（ -->
    <div id="receiptModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <img id="modalImage" src="" alt="書類画像" style="max-width: 100%; height: auto;">
        </div>
    </div>
</body>
</html>