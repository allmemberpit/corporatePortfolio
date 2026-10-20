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
                            <td colspan="4">現在未確認・却下された日報はありません。</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
        </div>
        <div>
            <h4>申請書の提出状況 </h4>
            <table class="userTable">
                <thead>
                    <tr>
                        <th>日付</th>
                        <th>種別</th>
                        <th>状態</th>
                        <th>確認</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($applicationStatus)): ?>
                        <?php foreach ($applicationStatus as $index => $applicationStatusItem): ?>
                            <tr>
                                <td><?= htmlspecialchars($applicationStatusItem['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <?php if ($applicationStatusItem['category'] == 1): ?>
                                        休暇申請
                                    <?php elseif ($applicationStatusItem['category'] == 2): ?>
                                        出張申請
                                    <?php elseif ($applicationStatusItem['category'] == 3): ?>
                                        経費申請
                                    <?php elseif ($applicationStatusItem['category'] == 4): ?>
                                        定期申請
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($applicationStatusItem['status'] == 0): ?>
                                        未処理
                                    <?php elseif ($applicationStatusItem['status'] == 1): ?>
                                        承認済み
                                    <?php elseif ($applicationStatusItem['status'] == 2): ?>
                                        却下
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($applicationStatusItem['status'] == 0): ?>

                                    <?php elseif($applicationStatusItem['status'] ==1): ?>
                                        <form action="index.php?action=staffCheckApplication&id=<?= $applicationStatusItem['id'] ?>" method="POST">
                                            <input type="submit" value="確認" class="btn">
                                        </form>
                                    <?php elseif ($applicationStatusItem['status'] == 2): ?>
                                        <input type="submit" class="modifyAppBtn btn" value="修正" 
                                            data-id="<?= htmlspecialchars($applicationStatusItem['id'], ENT_QUOTES, 'UTF-8') ?>"
                                            data-content="
                                                <?php if ($applicationStatusItem['category'] == 1): 
                                                        $apps = $rejectedRests;
                                                    elseif ($applicationStatusItem['category'] == 2): 
                                                        $apps = $rejectedTrips;
                                                    elseif ($applicationStatusItem['category'] == 3):
                                                        $apps = $rejectedExpens;
                                                    elseif ($applicationStatusItem['category'] == 4): 
                                                        $apps = $rejectedRegulars;
                                                    endif;     
                                                    
                                                    foreach($apps as $index => $app):                                        
                                                        if ($app['application_id'] == $applicationStatusItem['id']) {
                                                    ?>
                                                        <?=htmlspecialchars(json_encode($app, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP), ENT_QUOTES, 'UTF-8')?>
                                                    <?php  break;
                                                        }
                                                    endforeach;?>">
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">現在未確認・却下された申請はありません。</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
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
                    <option value="default">選択してください</option>
                    <option value="休暇申請">休暇申請</option>
                    <option value="出張申請">出張申請</option>
                    <option value="経費精算">経費精算</option>
                    <option value="定期申請">定期申請</option>
                </select>
            </div>
            
            <form class="appContainer" id="restContainer" action="/index.php?action=submitRest" method="post">
                <!-- 区分（全休・午前半休・午後半休） -->
                <div class="form-group">
                    <div><label>休暇区分 <span style="color:red;">*</span></label></div>
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
                    <div><label for="targetDate">取得希望日 <span style="color:red;">*</span></label></div>
                    <input type="date" id="targetDate" name="target_date" class="form-control" required>
                </div>

                <!-- 申請理由（datalistで定型文を選択可能 + 直接入力・変更も自由） -->
                <div class="form-group">
                    <div><label for="reasonInput">申請理由</label></div>
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
                <div class="form-group">
                    <div><label for="leaveMemo">備考・緊急連絡先（任意）</label></div>
                    <textarea id="leaveMemo" name="memo" class="form-control" rows="3" placeholder="引き継ぎ事項や連絡先があれば記載"></textarea>
                </div>
                <input  type="submit" class="btn submit" value="送信">
            </form>
            <form class="appContainer" id="tripContainer" action="/index.php?action=submitTrip" method="post">
                <!-- 出張期間（開始日〜終了日） -->
                <div class="form-group">
                    <label>出張期間 <span style="color:red;">*</span></label>
                    <div class="form-body-input">
                        <div class="date-range">
                            <input type="date" id="startDate" name="start_date" class="form-control" required>
                            <span>〜</span>
                            <input type="date" id="endDate" name="end_date" class="form-control" required>
                        </div>
                    </div>
                </div>

                <!-- 訪問先・目的地 -->
                <div class="form-group">
                    <label for="destination">訪問先・目的地 <span style="color:red;">*</span></label>
                    <div class="form-body-input">
                        <input type="text" id="destination" name="destination" class="form-control" placeholder="例: 大阪支店 / 株式会社〇〇" required>
                    </div>
                </div>

                <!-- 出張目的（datalistで入力補完 ＋ 自由入力） -->
                <div class="form-group">
                    <label for="tripPurpose">出張目的 <span style="color:red;">*</span></label>
                    <div class="form-body-input">
                        <input id="tripPurpose" 
                            name="purpose" 
                            list="purposeList" 
                            class="form-control" 
                            placeholder="選択または直接入力" 
                            onfocus="this.select()" 
                            required>
                        
                        <datalist id="purposeList">
                            <option value="商談・打ち合わせ">
                            <option value="現地調査・視察">
                            <option value="研修・セミナー参加">
                            <option value="製品導入・サポート対応">
                        </datalist>
                    </div>
                </div>

                <!-- 交通手段 & 概算費用 -->
                <div class="form-group">
                    <label for="estimatedCost">概算交通費（円）</label>
                    <div class="form-body-input">
                        <input type="number" id="estimatedCost" name="estimated_cost" class="form-control" placeholder="例: 25000" min="0" step="100">
                    </div>
                </div>

                <!-- 宿泊の有無 -->
                <div class="form-group">
                    <label>宿泊有無</label>
                    <div class="form-body-input">
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="has_lodging" value="0" checked> 日帰り
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="has_lodging" value="1"> 宿泊あり
                            </label>
                        </div>
                    </div>
                </div>

                <!-- 業務内容・日程詳細（複数行のテキストエリア） -->
                <div class="form-group">
                    <label for="tripDetail">訪問内容・日程案</label>
                    <div class="form-body-input">
                        <textarea id="tripDetail" name="detail" class="form-control" rows="4" placeholder="1日目: 〇〇訪問&#10;2日目: セミナー参加など"></textarea>
                    </div>
                </div>
                <input  type="submit" class="btn submit" value="送信">
            </form>
            <form class="appContainer" id="expenseContainer" action="/index.php?action=submitExpense" method="post" enctype="multipart/form-data">
                <!-- 利用日 -->
                <div class="form-group">
                    <label for="expenseDate">利用年月日 <span style="color:red;">*</span></label>
                    <div class="form-body-input">
                        <input type="date" id="expenseDate" name="expense_date" class="form-control" required>
                    </div>
                </div>

                <!-- 勘定科目（カテゴリ） -->
                <div class="form-group">
                    <label for="category">勘定科目 <span style="color:red;">*</span></label>
                    <div class="form-body-input">
                        <select id="category" name="category" class="form-control" required>
                            <option value="">選択してください</option>
                            <option value="交通費">旅費交通費</option>
                            <option value="会議費">会議費</option>
                            <option value="交際費">接待交際費</option>
                            <option value="消耗品費">消耗品費</option>
                            <option value="通信費">通信費</option>
                            <option value="その他">その他</option>
                        </select>
                    </div>
                </div>

                <!-- 支払先・店舗名 -->
                <div class="form-group">
                    <label for="payee">支払先（店舗・企業） <span style="color:red;">*</span></label>
                    <div class="form-body-input">
                        <input type="text" id="payee" name="payee" class="form-control" placeholder="例: 〇〇タクシー / タワー文具" required>
                    </div>
                </div>

                <!-- 金額（税込） -->
                <div class="form-group">
                    <label for="amount">金額（税込） <span style="color:red;">*</span></label>
                    <div class="form-body-input">
                        <input type="number" id="amount" name="amount" class="form-control" placeholder="例: 3500" min="1" step="1" required>
                    </div>
                </div>

                <!-- 利用内容・理由（datalistで入力補助 ＋ 自由入力） -->
                <div class="form-group">
                    <label for="expensePurpose">使用目的・内容 <span style="color:red;">*</span></label>
                    <div class="form-body-input">
                        <input id="expensePurpose" 
                            name="purpose" 
                            list="expensePurposeList" 
                            class="form-control" 
                            placeholder="選択または直接入力" 
                            onfocus="this.select()" 
                            required>
                        
                        <datalist id="expensePurposeList">
                            <option value="クライアント訪問時の移動">
                            <option value="打ち合わせ用のお茶代・カフェ代">
                            <option value="オフィス用備品（文房具等）の購入">
                            <option value="参考書籍の購入">
                            <option value="社外取引先との会食">
                        </datalist>
                    </div>
                </div>

                <!-- 領収書・レシート画像添付 -->
                <div class="form-group">
                    <label for="receiptImage">領収書添付</label>
                    <div class="form-body-input">
                        <input type="file" id="receiptImage" name="receipt" class="form-control" accept="image/*,.pdf" required>
                        <small style="color:#666; font-size:0.8rem; display:block; margin-top:4px;">※画像（JPG/PNG）またはPDF</small>
                    </div>
                </div>

                <!-- 備考（テキストエリア：自動的に上揃えになります） -->
                <div class="form-group">
                    <label for="expenseMemo">備考</label>
                    <div class="form-body-input">
                        <textarea id="expenseMemo" name="memo" class="form-control" rows="3" placeholder="補足事項があれば入力"></textarea>
                    </div>
                </div>
                <input  type="submit" class="btn submit" value="送信">
            </form>
            <form class="appContainer" id="regularContainer"  action="/index.php?action=submitRegular" method="post" enctype="multipart/form-data">
                <!-- 申請区分 -->
                <div class="form-group">
                    <label>申請区分 <span style="color:red;">*</span></label>
                    <div class="form-body-input">
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="apply_type" value="new" checked> 新規・経路変更
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="apply_type" value="renew"> 継続購入
                            </label>
                        </div>
                    </div>
                </div>

                <!-- 乗車区間（発 ～ 着） -->
                <div class="form-group">
                    <label>乗車区間 <span style="color:red;">*</span></label>
                    <div class="form-body-input">
                        <div class="route-range">
                            <input type="text" id="departure" name="departure" class="form-control" placeholder="発駅（例: 新宿）" required>
                            <span>～</span>
                            <input type="text" id="regDestination" name="destination" class="form-control" placeholder="着駅（例: 立川）" required>
                        </div>
                    </div>
                </div>

                <!-- 利用路線・経由地 -->
                <div class="form-group">
                    <label for="viaRoute">利用路線・経由</label>
                    <div class="form-body-input">
                        <input type="text" id="viaRoute" name="via_route" class="form-control" placeholder="例: JR中央線（特別快速）">
                    </div>
                </div>

                <!-- 通用期間（1ヶ月/3ヶ月/6ヶ月） -->
                <div class="form-group">
                    <label for="period">通用期間 <span style="color:red;">*</span></label>
                    <div class="form-body-input">
                        <select id="period" name="period" class="form-control" required>
                            <option value="1">1ヶ月</option>
                            <option value="3">3ヶ月</option>
                            <option value="6" selected>6ヶ月（推奨）</option>
                        </select>
                    </div>
                </div>

                <!-- 有効期間（開始日） -->
                <div class="form-group">
                    <label for="startDate">利用開始希望日 <span style="color:red;">*</span></label>
                    <div class="form-body-input">
                        <input type="date" id="regStartDate" name="start_date" class="form-control" required>
                    </div>
                </div>

                <!-- 申請金額（定期代） -->
                <div class="form-group">
                    <label for="passAmount">定期代金額（税込） <span style="color:red;">*</span></label>
                    <div class="form-body-input">
                        <input type="number" id="passAmount" name="amount" class="form-control" placeholder="例: 45000" min="1" step="1" required>
                    </div>
                </div>

                <!-- 証明画像・購入後の領収書添付 -->
                <div class="form-group">
                    <label for="passReceipt">定期券写し・領収書 <span style="color:red;">*</span></label>
                    <div class="form-body-input">
                        <input type="file" id="passReceipt" name="receipt" class="form-control" accept="image/*,.pdf" required>
                        <small style="color:#666; font-size:0.8rem; display:block; margin-top:4px;">※購入後の定期券コピーまたは領収書（画像/PDF）</small>
                    </div>
                </div>
                <input  type="submit" class="btn submit" value="送信">  
            </form>
           <button class="btn closeAppModalBtn">キャンセル</button>
        </div>
    </div>
</body>
</html>