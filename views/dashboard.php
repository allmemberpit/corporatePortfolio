<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ダッシュボード</title>
    <link rel="stylesheet" href="/views/css/input.css">
    <script src="/views/js/dashboard.js"></script>
</head>
<body>
    <div class="container">
        <h2>ダッシュボード</h2>
        <p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
        <h3>ログイン成功</h3>
        <p>ようこそ、<strong><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></strong> さん！</p>
        <div>
            <h4>日報の提出状況 </h4>
            <table class="userTable">
                <thead>
                    <tr>
                        <th>日付</th>
                        <th>内容</th>
                        <th>状態</th>
                        <th>確認</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($reportStatus)): ?>
                        <?php foreach ($reportStatus as $index => $reportStatusItem): ?>
                            <tr>
                                <td><?= htmlspecialchars($reportStatusItem['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($reportStatusItem['writing'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <?php if ($reportStatusItem['status'] == 0): ?>
                                        未処理
                                    <?php elseif ($reportStatusItem['status'] == 1): ?>
                                        承認済み
                                    <?php elseif ($reportStatusItem['status'] == 2): ?>
                                        却下
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($reportStatusItem['status'] ==1): ?>
                                        <form action="index.php?action=staffCheckReport&id=<?= $reportStatusItem['id'] ?>" method="POST">
                                            <input type="submit" value="確認" class="btn">
                                        </form>
                                    <?php elseif ($reportStatusItem['status'] == 2): ?>
                                        <input type="submit" class="modifyReportBtn btn" value="修正" 
                                            data-id="<?= htmlspecialchars($reportStatusItem['id'], ENT_QUOTES, 'UTF-8') ?>"
                                            data-content="<?= htmlspecialchars($reportStatusItem['writing'], ENT_QUOTES, 'UTF-8') ?>"
                                        class="btn">
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">現在未確認・却下された日報はありません。</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
        </div>
        <div>
            <h4>申請書の提出状況 </h4>
        </div>
        <button class="btn" id="reportBtn">日報</button>
        <button class="btn" id="appBtn">申請書</button>
        <a href="/index.php?action=logout">ログアウト</a>
    </div>
    <div id="reportModal" class="modal">
        <div class="modal-content">
            <h3>日報入力</h3>
            <form id="postReport" action="/index.php?action=submitReport" method="post">
                <label for="report">日報内容:</label><br>
                <textarea id="report" name="writing" rows="4" cols="50"></textarea><br>
                <input  type="submit" class="btn submit" value="送信">
            </form>
             <button class="btn" id="cancelBtn">キャンセル</button>
        </div>
    </div>
    <div id="appModal" class="modal">
        <div class="modal-content">
            <h3>申請書入力</h3>
            <div>
                <select id="typeList" >
                    <option value="">選択してください</option>
                    <option value="休暇申請">休暇申請</option>
                    <option value="出張申請">出張申請</option>
                    <option value="経費精算">経費精算</option>
                    <option value="定期申請">定期申請</option>
                </select>
            </div>
            
            <form class="appContainer" id="restContainer" action="/index.php?action=submitRest" method="post">
                <!-- 区分（全休・午前半休・午後半休） -->
                <div class="form-group">
                    <label>休暇区分 <span style="color:red;">*</span></label>
                    <label class="radio-label">
                        <input type="radio" name="leave_type" value="full" checked> 全休
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="leave_type" value="am"> AM半休
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="leave_type" value="pm"> PM半休
                    </label>
                </div>

                <!-- 対象日 -->
                <div class="form-group">
                    <label for="targetDate">取得希望日 <span style="color:red;">*</span></label>
                    <input type="date" id="targetDate" name="target_date" class="form-control" required>
                </div>

                <!-- 申請理由（datalistで定型文を選択可能 + 直接入力・変更も自由） -->
                <div class="form-group">
                    <label for="reasonInput">申請理由</label>
                    <input id="reasonInput" 
                        name="reason" 
                        list="reasonList" 
                        class="form-control" 
                        placeholder="選択または理由を入力" 
                        onfocus="this.select()">
                    
                    <datalist id="reasonList">
                        <option value="私用のため">
                        <option value="通院のため">
                        <option value="家庭都合のため">
                        <option value="リフレッシュのため">
                    </datalist>
                </div>

                <!-- 備考・連絡事項 -->
                <div class="form-group alighn-top">
                    <label for="leaveMemo">備考・緊急連絡先（任意）</label>
                    <textarea id="leaveMemo" name="memo" class="form-control" rows="3" placeholder="引き継ぎ事項や連絡先があれば記載"></textarea>
                </div>
            </form>
            <div class="appContainer" id="tripContainer">
                出張申請
            </div>
            <div class="appContainer" id="expenseContainer">
                経費精算
            </div>
            <div class="appContainer" id="regularContainer">
                定期申請
            </div>
            <input  type="submit" class="btn submit" value="送信">
            <button class="btn" id="closeAppModalBtn">閉じる</button>
        </div>
    </div>
</body>
</html>