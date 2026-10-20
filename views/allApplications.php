<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>申請書一覧</title>
    <link rel="stylesheet" href="/views/css/input.css">
</head>
<body>
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
                    <th>No.</th>
                    <th>ユーザー名</th>
                    <th>対象日付</th>
                    <th>休暇区分</th>
                    <th>理由</th>
                    <th>作成日時</th>
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
                        <td colspan="3">休暇申請がありません。</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h3>出張申請</h3>
        <table class="userTable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>ユーザー名</th>
                    <th>開始日</th>
                    <th>終了日</th>
                    <th>訪問先・目的地</th>
                    <th>目的</th>
                    <th>概算交通費</th>
                    <th>宿泊有無</th>
                    <th>訪問内容・日程案</th>
                    <th>作成日時</th>
                    <th>承認</th>
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
                            <td><?= htmlspecialchars($tripApp['cost'], ENT_QUOTES, 'UTF-8') ?>円</td>
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
                    <th>No.</th>
                    <th>ユーザー名</th>
                    <th>勘定科目</th>
                    <th>支払先</th>
                    <th>金額</th>
                    <th>使用目的・内容</th>
                    <th>備考</th>
                    <th>作成日時</th>
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
                            <td><?= htmlspecialchars($expenseApp['amount'], ENT_QUOTES, 'UTF-8') ?>円</td>
                            <td><?= htmlspecialchars($expenseApp['purpose'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($expenseApp['detail'], ENT_QUOTES, 'UTF-8') ?></td>
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
                    <th>No.</th>
                    <th>ユーザー名</th>
                    <th>申請区分</th>
                    <th>乗車駅</th>
                    <th>降車駅</th>
                    <th>利用路線・経由</th>
                    <th>利用開始希望日</th>
                    <th>通用期間</th>
                    <th>定期代</th>
                    <th>作成日時</th>
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
                            <td><?= htmlspecialchars($regularApp['period'], ENT_QUOTES, 'UTF-8') ?>ヶ月</td>
                            <td><?= htmlspecialchars($regularApp['passAmount'], ENT_QUOTES, 'UTF-8') ?>円</td>
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
                        <td colspan="11">定期申請がありません。</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="index.php?action=admin">戻る</a>
        <a href="index.php?action=logout">ログアウト</a>
    </div>
</body>
</html>