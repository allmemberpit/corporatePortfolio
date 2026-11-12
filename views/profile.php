<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>プロフィール</title>
    <link rel="stylesheet" href="/views/css/input.css">
    <link rel="stylesheet" href="/views/css/profile.css">
    <link rel="stylesheet" href="/views/css/header.css">
    <script src="/views/js/toggle.js"></script>
    <script src="/views/js/profile.js"></script>
</head>
<body>
    <header class="portal-header">
        <div class="header-container">
            <!-- ロゴやサイト名 -->
            <div class="header-logo">
                <a href="index.php?action=dashboard">プロフィール</a>
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
                <?php require_once __DIR__ . '/navigation.php';
                $navItems = getNavItems($name);
                foreach ($navItems as $item): ?>
                    <?php 
                        // 管理者専用ページの権限チェック
                        if (!empty($item['admin_only']) && (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')) {
                            continue;
                        }
                        if($item['label'] === 'プロフィール') {
                            continue; // プロフィールは表示しない
                        } 
                    ?>
                    <li><a <?php if (!empty($item['id'])): ?>
                            id="<?= htmlspecialchars($item['id'], ENT_QUOTES, 'UTF-8') ?>"
                        <?php endif; ?>
                        <?php if (!empty($item['act'])): ?>
                            href="<?= htmlspecialchars($item['act'], ENT_QUOTES, 'UTF-8') ?>"
                        <?php endif; ?>>
                        <?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?>
                    </a></li>
                <?php endforeach; ?>
                <!--<li><a href="#">ホーム</a></li>
                <li><a href="index.php?action=dashboard">戻る</a></li>
                <li><a href="index.php?action=logout" class="logout-link">ログアウト</a></li>-->
            </ul>
        </nav>
    </header>
    <div class="container">
        <h1><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>のプロフィール</h1>
        <div class="profile" id="editForm" >
            <div class="personal">
                <h2>
                    <span class="profileTitle">基本情報</span>
                    <button id="edit" type="button" >編集</button>
                    <button id="save" type="button" >保存</button>
                    <button id="cancel" type="button" >戻る</button>
                </h2>
                <div class="profile-row">
                    <span class="profileCategory">名前</span>
                    <span class="profileData"><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></span>
                    <input type="text" class="profileInput">
                </div> 
                <div class="profile-row">
                    <span class="profileCategory">生年月日</span>
                    <span class="profileData" data-date="<?= htmlspecialchars($profile['birthday'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                        <?php 
                            $birthdayStr = $profile['birthday'] ?? ''; 
                            if (!empty($birthdayStr)) {
                                // 1. 誕生日のオブジェクトを作成
                                $birthDate = new DateTime($birthdayStr);
                                $today = new DateTime(); // 本日の日付
                                // 2. 年月日のフォーマットに変換（YYYY年MM月DD日）
                                $formattedDate = $birthDate->format('Y年n月j日'); 
                                // 3. 年齢を自動計算
                                $age = $birthDate->diff($today)->y;
                                // 4. 出力
                                echo "{$formattedDate}（{$age}歳）";
                            } else {
                                echo "未登録";
                            }
                         ?>
                    </span>
                    <input type="date" class="profileInput">
                </div>
                <div class="profile-row">
                    <span class="profileCategory">住所</span>
                    <span class="profileData"><?php if (!empty($profile['address'])) { echo htmlspecialchars($profile['address'], ENT_QUOTES, 'UTF-8'); } else { echo "未登録"; } ?></span>
                    <textarea class="profileInput" cols="30"></textarea>
                </div>
                <div class="profile-row">
                    <span class="profileCategory">最寄り駅</span>
                    <span class="profileData"><?php if (!empty($profile['nearest_station'])) { echo htmlspecialchars($profile['nearest_station'], ENT_QUOTES, 'UTF-8'); } else { echo "未登録"; } ?></span>
                    <input type="text" class="profileInput">
                </div>
            </div>
            <div class="skill">
                <h2>スキル情報</h2>
                <h3 class="accordionH3" data-target="skills-1"><span class="skillsTitle">事務能力</span><span class="accordionMode">＋</span></h3> 
                <div id="skills-1" class="profileAccordion">
                    <div class="profile-row">
                        <span class="profileCategory">Word</span>
                        <span class="profileData">
                            <?php switch(htmlspecialchars($profile['word'], ENT_QUOTES, 'UTF-8')): case "1": ?> 未経験 <?php break; case "2": ?> 一年未満 <?php break; case "3": ?> 一年以上 <?php break; case "4": ?> 二年以上 <?php break; case "5": ?> 三年以上 <?php endswitch; ?>
                        </span>
                        <div class="profileInput">
                            <label><input type="radio" name="word" value="1"<?php if ($profile['word'] == '1') echo ' checked'; ?>> 未経験</label>
                            <label><input type="radio" name="word" value="2"<?php if ($profile['word'] == '2') echo ' checked'; ?>>一年未満</label>
                            <label><input type="radio" name="word" value="3"<?php if ($profile['word'] == '3') echo ' checked'; ?>>一年以上</label>
                            <label><input type="radio" name="word" value="4"<?php if ($profile['word'] == '4') echo ' checked'; ?>>二年以上</label>
                            <label><input type="radio" name="word" value="5"<?php if ($profile['word'] == '5') echo ' checked'; ?>>三年以上</label>
                        </div>
                    </div>   
                    <div class="profile-row">
                        <span class="profileCategory">Excel</span>
                        <span class="profileData">
                            <?php switch(htmlspecialchars($profile['excel'], ENT_QUOTES, 'UTF-8')): case "1": ?> 未経験 <?php break; case "2": ?> 一年未満 <?php break; case "3": ?> 一年以上 <?php break; case "4": ?> 二年以上 <?php break; case "5": ?> 三年以上 <?php endswitch; ?>
                        </span>
                        <div class="profileInput">
                            <label><input type="radio" name="excel" value="1"<?php if ($profile['excel'] == '1') echo ' checked'; ?>> 未経験</label>
                            <label><input type="radio" name="excel" value="2"<?php if ($profile['excel'] == '2') echo ' checked'; ?>>一年未満</label>
                            <label><input type="radio" name="excel" value="3"<?php if ($profile['excel'] == '3') echo ' checked'; ?>>一年以上</label>
                            <label><input type="radio" name="excel" value="4"<?php if ($profile['excel'] == '4') echo ' checked'; ?>>二年以上</label>
                            <label><input type="radio" name="excel" value="5"<?php if ($profile['excel'] == '5') echo ' checked'; ?>>三年以上</label>
                        </div>
                    </div>
                    <div class="profile-row">
                        <span class="profileCategory">Power Point</span>
                        <span class="profileData">
                            <?php switch(htmlspecialchars($profile['powerpoint'], ENT_QUOTES, 'UTF-8')): case "1": ?> 未経験 <?php break; case "2": ?> 一年未満 <?php break; case "3": ?> 一年以上 <?php break; case "4": ?> 二年以上 <?php break; case "5": ?> 三年以上 <?php endswitch; ?>
                        </span>
                        <div class="profileInput">
                            <label><input type="radio" name="powerpoint" value="1"<?php if ($profile['powerpoint'] == '1') echo ' checked'; ?>> 未経験</label>
                            <label><input type="radio" name="powerpoint" value="2"<?php if ($profile['powerpoint'] == '2') echo ' checked'; ?>>一年未満</label>
                            <label><input type="radio" name="powerpoint" value="3"<?php if ($profile['powerpoint'] == '3') echo ' checked'; ?>>一年以上</label>
                            <label><input type="radio" name="powerpoint" value="4"<?php if ($profile['powerpoint'] == '4') echo ' checked'; ?>>二年以上</label>
                            <label><input type="radio" name="powerpoint" value="5"<?php if ($profile['powerpoint'] == '5') echo ' checked'; ?>>三年以上</label>
                        </div>
                    </div>
                    <div class="profile-row">
                        <span class="profileCategory">Microsoft Outlook</span>
                        <span class="profileData">
                            <?php switch(htmlspecialchars($profile['outlook'], ENT_QUOTES, 'UTF-8')): case "1": ?> 未経験 <?php break; case "2": ?> 一年未満 <?php break; case "3": ?> 一年以上 <?php break; case "4": ?> 二年以上 <?php break; case "5": ?> 三年以上 <?php endswitch; ?>
                        </span>
                        <div class="profileInput">
                            <label><input type="radio" name="outlook" value="1"<?php if ($profile['outlook'] == '1') echo ' checked'; ?>> 未経験</label>
                            <label><input type="radio" name="outlook" value="2"<?php if ($profile['outlook'] == '2') echo ' checked'; ?>>一年未満</label>
                            <label><input type="radio" name="outlook" value="3"<?php if ($profile['outlook'] == '3') echo ' checked'; ?>>一年以上</label>
                            <label><input type="radio" name="outlook" value="4"<?php if ($profile['outlook'] == '4') echo ' checked'; ?>>二年以上</label>
                            <label><input type="radio" name="outlook" value="5"<?php if ($profile['outlook'] == '5') echo ' checked'; ?>>三年以上</label>
                        </div>
                    </div>
                    <div class="profile-row">
                        <span class="profileCategory">Google スプレッドシート</span>
                        <span class="profileData">
                            <?php switch(htmlspecialchars($profile['spreadsheet'], ENT_QUOTES, 'UTF-8')): case "1": ?> 未経験 <?php break; case "2": ?> 一年未満 <?php break; case "3": ?> 一年以上 <?php break; case "4": ?> 二年以上 <?php break; case "5": ?> 三年以上 <?php endswitch; ?>
                        </span>
                        <div class="profileInput">
                            <label><input type="radio" name="spreadsheet" value="1"<?php if ($profile['spreadsheet'] == '1') echo ' checked'; ?>> 未経験</label>
                            <label><input type="radio" name="spreadsheet" value="2"<?php if ($profile['spreadsheet'] == '2') echo ' checked'; ?>>一年未満</label>
                            <label><input type="radio" name="spreadsheet" value="3"<?php if ($profile['spreadsheet'] == '3') echo ' checked'; ?>>一年以上</label>
                            <label><input type="radio" name="spreadsheet" value="4"<?php if ($profile['spreadsheet'] == '4') echo ' checked'; ?>>二年以上</label>
                            <label><input type="radio" name="spreadsheet" value="5"<?php if ($profile['spreadsheet'] == '5') echo ' checked'; ?>>三年以上</label>
                        </div>
                    </div>
                    <div class="profile-row">
                        <span class="profileCategory">Microsoft Teams / Slack</span>
                        <span class="profileData">
                            <?php switch(htmlspecialchars($profile['teams'], ENT_QUOTES, 'UTF-8')): case "1": ?> 未経験 <?php break; case "2": ?> 一年未満 <?php break; case "3": ?> 一年以上 <?php break; case "4": ?> 二年以上 <?php break; case "5": ?> 三年以上 <?php endswitch; ?>
                        </span>
                        <div class="profileInput">
                            <label><input type="radio" name="teams" value="1"<?php if ($profile['teams'] == '1') echo ' checked'; ?>> 未経験</label>
                            <label><input type="radio" name="teams" value="2"<?php if ($profile['teams'] == '2') echo ' checked'; ?>>一年未満</label>
                            <label><input type="radio" name="teams" value="3"<?php if ($profile['teams'] == '3') echo ' checked'; ?>>一年以上</label>
                            <label><input type="radio" name="teams" value="4"<?php if ($profile['teams'] == '4') echo ' checked'; ?>>二年以上</label>
                            <label><input type="radio" name="teams" value="5"<?php if ($profile['teams'] == '5') echo ' checked'; ?>>三年以上</label>
                        </div>
                    </div>
                         
                    <div class="profile-row">
                        <span class="profileCategory">Adobe Acrobat（PDF編集・閲覧ソフト）</span>
                        <span class="profileData">
                            <?php switch(htmlspecialchars($profile['adobe'], ENT_QUOTES, 'UTF-8')): case "1": ?> 未経験 <?php break; case "2": ?> 一年未満 <?php break; case "3": ?> 一年以上 <?php break; case "4": ?> 二年以上 <?php break; case "5": ?> 三年以上 <?php endswitch; ?>
                        </span>
                        <div class="profileInput">
                            <label><input type="radio" name="adobe" value="1"<?php if ($profile['adobe'] == '1') echo ' checked'; ?>> 未経験</label>
                            <label><input type="radio" name="adobe" value="2"<?php if ($profile['adobe'] == '2') echo ' checked'; ?>>一年未満</label>
                            <label><input type="radio" name="adobe" value="3"<?php if ($profile['adobe'] == '3') echo ' checked'; ?>>一年以上</label>
                            <label><input type="radio" name="adobe" value="4"<?php if ($profile['adobe'] == '4') echo ' checked'; ?>>二年以上</label>
                            <label><input type="radio" name="adobe" value="5"<?php if ($profile['adobe'] == '5') echo ' checked'; ?>>三年以上</label>
                        </div>
                    </div>
                    <div class="profile-row">
                        <span class="profileCategory">クラウドストレージ（OneDrive / Google Drive / Box 等）</span>
                        <span class="profileData">
                            <?php switch(htmlspecialchars($profile['cloud'], ENT_QUOTES, 'UTF-8')): case "1": ?> 未経験 <?php break; case "2": ?> 一年未満 <?php break; case "3": ?> 一年以上 <?php break; case "4": ?> 二年以上 <?php break; case "5": ?> 三年以上 <?php endswitch; ?>
                        </span>
                        <div class="profileInput">
                            <label><input type="radio" name="cloud" value="1"<?php if ($profile['cloud'] == '1') echo ' checked'; ?>> 未経験</label>
                            <label><input type="radio" name="cloud" value="2"<?php if ($profile['cloud'] == '2') echo ' checked'; ?>>一年未満</label>
                            <label><input type="radio" name="cloud" value="3"<?php if ($profile['cloud'] == '3') echo ' checked'; ?>>一年以上</label>
                            <label><input type="radio" name="cloud" value="4"<?php if ($profile['cloud'] == '4') echo ' checked'; ?>>二年以上</label>
                            <label><input type="radio" name="cloud" value="5"<?php if ($profile['cloud'] == '5') echo ' checked'; ?>>三年以上</label>
                        </div>
                    </div>
                    <div class="profile-row">
                        <span class="profileCategory">会計・経理ソフト（弥生会計、freee、マネーフォワード等）</span>
                        <span class="profileData">
                            <?php switch(htmlspecialchars($profile['accounting'], ENT_QUOTES, 'UTF-8')): case "1": ?> 未経験 <?php break; case "2": ?> 一年未満 <?php break; case "3": ?> 一年以上 <?php break; case "4": ?> 二年以上 <?php break; case "5": ?> 三年以上 <?php endswitch; ?>
                        </span>
                        <div class="profileInput">
                            <label><input type="radio" name="accounting" value="1"<?php if ($profile['accounting'] == '1') echo ' checked'; ?>> 未経験</label>
                            <label><input type="radio" name="accounting" value="2"<?php if ($profile['accounting'] == '2') echo ' checked'; ?>>一年未満</label>
                            <label><input type="radio" name="accounting" value="3"<?php if ($profile['accounting'] == '3') echo ' checked'; ?>>一年以上</label>
                            <label><input type="radio" name="accounting" value="4"<?php if ($profile['accounting'] == '4') echo ' checked'; ?>>二年以上</label>
                            <label><input type="radio" name="accounting" value="5"<?php if ($profile['accounting'] == '5') echo ' checked'; ?>>三年以上</label>
                        </div>
                    </div>
                       
                    <div class="profile-row">
                        <span class="profileCategory">基幹システム・ERP / 販売管理ソフト（各種専用システム）</span>
                        <span class="profileData">
                            <?php switch(htmlspecialchars($profile['erp'], ENT_QUOTES, 'UTF-8')): case "1": ?> 未経験 <?php break; case "2": ?> 一年未満 <?php break; case "3": ?> 一年以上 <?php break; case "4": ?> 二年以上 <?php break; case "5": ?> 三年以上 <?php endswitch; ?>
                        </span>
                        <div class="profileInput">
                            <label><input type="radio" name="erp" value="1"<?php if ($profile['erp'] == '1') echo ' checked'; ?>> 未経験</label>
                            <label><input type="radio" name="erp" value="2"<?php if ($profile['erp'] == '2') echo ' checked'; ?>>一年未満</label>
                            <label><input type="radio" name="erp" value="3"<?php if ($profile['erp'] == '3') echo ' checked'; ?>>一年以上</label>
                            <label><input type="radio" name="erp" value="4"<?php if ($profile['erp'] == '4') echo ' checked'; ?>>二年以上</label>
                            <label><input type="radio" name="erp" value="5"<?php if ($profile['erp'] == '5') echo ' checked'; ?>>三年以上</label>
                        </div>
                    </div>
                       
                    <div class="profile-row">
                        <span class="profileCategory">その他</span>
                        <span class="profileData"><?php if (!empty($profile['office_other'])) { echo htmlspecialchars($profile['office_other'], ENT_QUOTES, 'UTF-8'); } else { echo "未登録"; } ?></span>
                        <textarea class="profileInput" cols="30" rows="4"></textarea>
                    </div>
                </div>
            </div>
            <div class="skill">
                <h3 class="accordionH3"  data-target="skills-2"><span class="skillsTitle">プログラミング言語</span><span class="accordionMode">＋</span></h3> 
                <div id="skills-2" class="profileAccordion">
                    <div class="profile-row">
                        <span class="profileCategory">Python</span>
                        <span class="profileData">
                            <?php switch(htmlspecialchars($profile['python'], ENT_QUOTES, 'UTF-8')): case "1": ?> 未経験 <?php break; case "2": ?> 一年未満 <?php break; case "3": ?> 一年以上 <?php break; case "4": ?> 二年以上 <?php break; case "5": ?> 三年以上 <?php endswitch; ?>
                        </span>
                        <div class="profileInput">
                            <label><input type="radio" name="python" value="1"<?php if ($profile['python'] == '1') echo ' checked'; ?>> 未経験</label>
                            <label><input type="radio" name="python" value="2"<?php if ($profile['python'] == '2') echo ' checked'; ?>>一年未満</label>
                            <label><input type="radio" name="python" value="3"<?php if ($profile['python'] == '3') echo ' checked'; ?>>一年以上</label>
                            <label><input type="radio" name="python" value="4"<?php if ($profile['python'] == '4') echo ' checked'; ?>>二年以上</label>
                            <label><input type="radio" name="python" value="5"<?php if ($profile['python'] == '5') echo ' checked'; ?>>三年以上</label>
                        </div>
                    </div>
                    <div class="profile-row">
                        <span class="profileCategory">HTML5・CSS3</span>
                        <span class="profileData">
                            <?php switch(htmlspecialchars($profile['html'], ENT_QUOTES, 'UTF-8')): case "1": ?> 未経験 <?php break; case "2": ?> 一年未満 <?php break; case "3": ?> 一年以上 <?php break; case "4": ?> 二年以上 <?php break; case "5": ?> 三年以上 <?php endswitch; ?>
                        </span>
                         <div class="profileInput">
                            <label><input type="radio" name="html" value="1"<?php if ($profile['html'] == '1') echo ' checked'; ?>> 未経験</label>
                            <label><input type="radio" name="html" value="2"<?php if ($profile['html'] == '2') echo ' checked'; ?>>一年未満</label>
                            <label><input type="radio" name="html" value="3"<?php if ($profile['html'] == '3') echo ' checked'; ?>>一年以上</label>
                            <label><input type="radio" name="html" value="4"<?php if ($profile['html'] == '4') echo ' checked'; ?>>二年以上</label>
                            <label><input type="radio" name="html" value="5"<?php if ($profile['html'] == '5') echo ' checked'; ?>>三年以上</label>
                        </div>
                    </div>
                    <div class="profile-row">
                        <span class="profileCategory">JavaScript</span>
                        <span class="profileData">
                            <?php switch(htmlspecialchars($profile['javascript'], ENT_QUOTES, 'UTF-8')): case "1": ?> 未経験 <?php break; case "2": ?> 一年未満 <?php break; case "3": ?> 一年以上 <?php break; case "4": ?> 二年以上 <?php break; case "5": ?> 三年以上 <?php endswitch; ?>
                        </span>
                         <div class="profileInput">
                            <label><input type="radio" name="javascript" value="1"<?php if ($profile['javascript'] == '1') echo ' checked'; ?>> 未経験</label>
                            <label><input type="radio" name="javascript" value="2"<?php if ($profile['javascript'] == '2') echo ' checked'; ?>>一年未満</label>
                            <label><input type="radio" name="javascript" value="3"<?php if ($profile['javascript'] == '3') echo ' checked'; ?>>一年以上</label>
                            <label><input type="radio" name="javascript" value="4"<?php if ($profile['javascript'] == '4') echo ' checked'; ?>>二年以上</label>
                            <label><input type="radio" name="javascript" value="5"<?php if ($profile['javascript'] == '5') echo ' checked'; ?>>三年以上</label>
                        </div>
                    </div>
                    <div class="profile-row">
                        <span class="profileCategory">TypeScript</span>
                        <span class="profileData">
                            <?php switch(htmlspecialchars($profile['typescript'], ENT_QUOTES, 'UTF-8')): case "1": ?> 未経験 <?php break; case "2": ?> 一年未満 <?php break; case "3": ?> 一年以上 <?php break; case "4": ?> 二年以上 <?php break; case "5": ?> 三年以上 <?php endswitch; ?>
                        </span>
                        <div class="profileInput">
                            <label><input type="radio" name="typescript" value="1"<?php if ($profile['typescript'] == '1') echo ' checked'; ?>> 未経験</label>
                            <label><input type="radio" name="typescript" value="2"<?php if ($profile['typescript'] == '2') echo ' checked'; ?>>一年未満</label>
                            <label><input type="radio" name="typescript" value="3"<?php if ($profile['typescript'] == '3') echo ' checked'; ?>>一年以上</label>
                            <label><input type="radio" name="typescript" value="4"<?php if ($profile['typescript'] == '4') echo ' checked'; ?>>二年以上</label>
                            <label><input type="radio" name="typescript" value="5"<?php if ($profile['typescript'] == '5') echo ' checked'; ?>>三年以上</label>
                        </div>
                    </div>
                    <div class="profile-row">
                        <span class="profileCategory">PHP</span>
                        <span class="profileData">
                            <?php switch(htmlspecialchars($profile['php'], ENT_QUOTES, 'UTF-8')): case "1": ?> 未経験 <?php break; case "2": ?> 一年未満 <?php break; case "3": ?> 一年以上 <?php break; case "4": ?> 二年以上 <?php break; case "5": ?> 三年以上 <?php endswitch; ?>
                        </span>
                        <div class="profileInput">
                            <label><input type="radio" name="php" value="1"<?php if ($profile['php'] == '1') echo ' checked'; ?>> 未経験</label>
                            <label><input type="radio" name="php" value="2"<?php if ($profile['php'] == '2') echo ' checked'; ?>>一年未満</label>
                            <label><input type="radio" name="php" value="3"<?php if ($profile['php'] == '3') echo ' checked'; ?>>一年以上</label>
                            <label><input type="radio" name="php" value="4"<?php if ($profile['php'] == '4') echo ' checked'; ?>>二年以上</label>
                            <label><input type="radio" name="php" value="5"<?php if ($profile['php'] == '5') echo ' checked'; ?>>三年以上</label>
                        </div>
                    </div>
                    <div class="profile-row">
                        <span class="profileCategory">Java</span>
                        <span class="profileData">
                            <?php switch(htmlspecialchars($profile['java'], ENT_QUOTES, 'UTF-8')): case "1": ?> 未経験 <?php break; case "2": ?> 一年未満 <?php break; case "3": ?> 一年以上 <?php break; case "4": ?> 二年以上 <?php break; case "5": ?> 三年以上 <?php endswitch; ?>
                        </span>
                        <div class="profileInput">
                            <label><input type="radio" name="java" value="1"<?php if ($profile['java'] == '1') echo ' checked'; ?>> 未経験</label>
                            <label><input type="radio" name="java" value="2"<?php if ($profile['java'] == '2') echo ' checked'; ?>>一年未満</label>
                            <label><input type="radio" name="java" value="3"<?php if ($profile['java'] == '3') echo ' checked'; ?>>一年以上</label>
                            <label><input type="radio" name="java" value="4"<?php if ($profile['java'] == '4') echo ' checked'; ?>>二年以上</label>
                            <label><input type="radio" name="java" value="5"<?php if ($profile['java'] == '5') echo ' checked'; ?>>三年以上</label>
                        </div>
                    </div>
                    <div class="profile-row">
                        <span class="profileCategory">C#</span>
                        <span class="profileData">
                            <?php switch(htmlspecialchars($profile['csharp'], ENT_QUOTES, 'UTF-8')): case "1": ?> 未経験 <?php break; case "2": ?> 一年未満 <?php break; case "3": ?> 一年以上 <?php break; case "4": ?> 二年以上 <?php break; case "5": ?> 三年以上 <?php endswitch; ?>
                        </span>
                        <div class="profileInput">
                            <label><input type="radio" name="csharp" value="1"<?php if ($profile['csharp'] == '1') echo ' checked'; ?>> 未経験</label>
                            <label><input type="radio" name="csharp" value="2"<?php if ($profile['csharp'] == '2') echo ' checked'; ?>>一年未満</label>
                            <label><input type="radio" name="csharp" value="3"<?php if ($profile['csharp'] == '3') echo ' checked'; ?>>一年以上</label>
                            <label><input type="radio" name="csharp" value="4"<?php if ($profile['csharp'] == '4') echo ' checked'; ?>>二年以上</label>
                            <label><input type="radio" name="csharp" value="5"<?php if ($profile['csharp'] == '5') echo ' checked'; ?>>三年以上</label>
                        </div>
                    </div>
                    <div class="profile-row">
                        <span class="profileCategory">C++</span>
                        <span class="profileData">
                            <?php switch(htmlspecialchars($profile['cplusplus'], ENT_QUOTES, 'UTF-8')): case "1": ?> 未経験 <?php break; case "2": ?> 一年未満 <?php break; case "3": ?> 一年以上 <?php break; case "4": ?> 二年以上 <?php break; case "5": ?> 三年以上 <?php endswitch; ?>
                        </span>
                        <div class="profileInput">
                            <label><input type="radio" name="cplusplus" value="1"<?php if ($profile['cplusplus'] == '1') echo ' checked'; ?>>未経験</label>
                            <label><input type="radio" name="cplusplus" value="2"<?php if ($profile['cplusplus'] == '2') echo ' checked'; ?>>一年未満</label>
                            <label><input type="radio" name="cplusplus" value="3"<?php if ($profile['cplusplus'] == '3') echo ' checked'; ?>>一年以上</label>
                            <label><input type="radio" name="cplusplus" value="4"<?php if ($profile['cplusplus'] == '4') echo ' checked'; ?>>二年以上</label>
                            <label><input type="radio" name="cplusplus" value="5"<?php if ($profile['cplusplus'] == '5') echo ' checked'; ?>>三年以上</label>
                        </div>
                    </div>
                    <div class="profile-row">
                        <span class="profileCategory">SQL</span>
                        <span class="profileData">
                            <?php switch(htmlspecialchars($profile['sql'], ENT_QUOTES, 'UTF-8')): case "1": ?> 未経験 <?php break; case "2": ?> 一年未満 <?php break; case "3": ?> 一年以上 <?php break; case "4": ?> 二年以上 <?php break; case "5": ?> 三年以上 <?php endswitch; ?>
                        </span>
                        <div class="profileInput">
                            <label><input type="radio" name="sql" value="1"<?php if ($profile['sql'] == '1') echo ' checked'; ?>> 未経験</label>
                            <label><input type="radio" name="sql" value="2"<?php if ($profile['sql'] == '2') echo ' checked'; ?>>一年未満</label>
                            <label><input type="radio" name="sql" value="3"<?php if ($profile['sql'] == '3') echo ' checked'; ?>>一年以上</label>
                            <label><input type="radio" name="sql" value="4"<?php if ($profile['sql'] == '4') echo ' checked'; ?>>二年以上</label>
                            <label><input type="radio" name="sql" value="5"<?php if ($profile['sql'] == '5') echo ' checked'; ?>>三年以上</label>
                        </div>
                    </div>
                    <div class="profile-row">
                        <span class="profileCategory">Go (Golang)</span>
                        <span class="profileData">
                            <?php switch(htmlspecialchars($profile['go'], ENT_QUOTES, 'UTF-8')): case "1": ?> 未経験 <?php break; case "2": ?> 一年未満 <?php break; case "3": ?> 一年以上 <?php break; case "4": ?> 二年以上 <?php break; case "5": ?> 三年以上 <?php endswitch; ?>
                        </span>
                        <div class="profileInput">
                            <label><input type="radio" name="go" value="1"<?php if ($profile['go'] == '1') echo ' checked'; ?>>未経験</label>
                            <label><input type="radio" name="go" value="2"<?php if ($profile['go'] == '2') echo ' checked'; ?>>一年未満</label>
                            <label><input type="radio" name="go" value="3"<?php if ($profile['go'] == '3') echo ' checked'; ?>>一年以上</label>
                            <label><input type="radio" name="go" value="4"<?php if ($profile['go'] == '4') echo ' checked'; ?>>二年以上</label>
                            <label><input type="radio" name="go" value="5"<?php if ($profile['go'] == '5') echo ' checked'; ?>>三年以上</label>
                        </div>
                    </div>
                    <div class="profile-row">
                        <span class="profileCategory">Ruby</span>
                        <span class="profileData">
                            <?php switch(htmlspecialchars($profile['ruby'], ENT_QUOTES, 'UTF-8')): case "1": ?> 未経験 <?php break; case "2": ?> 一年未満 <?php break; case "3": ?> 一年以上 <?php break; case "4": ?> 二年以上 <?php break; case "5": ?> 三年以上 <?php endswitch; ?>
                        </span>
                        <div class="profileInput">
                            <label><input type="radio" name="ruby" value="1"<?php if ($profile['ruby'] == '1') echo ' checked'; ?>>未経験</label>
                            <label><input type="radio" name="ruby" value="2"<?php if ($profile['ruby'] == '2') echo ' checked'; ?>>一年未満</label>
                            <label><input type="radio" name="ruby" value="3"<?php if ($profile['ruby'] == '3') echo ' checked'; ?>>一年以上</label>
                            <label><input type="radio" name="ruby" value="4"<?php if ($profile['ruby'] == '4') echo ' checked'; ?>>二年以上</label>
                            <label><input type="radio" name="ruby" value="5"<?php if ($profile['ruby'] == '5') echo ' checked'; ?>>三年以上</label>
                        </div>
                    </div>
                    <div class="profile-row">
                        <span class="profileCategory">その他</span>
                        <span class="profileData"><?php if (!empty($profile['programming_other'])) { echo htmlspecialchars($profile['programming_other'], ENT_QUOTES, 'UTF-8'); } else { echo "未登録"; } ?></span>
                        <textarea class="profileInput" cols="30" rows="4"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div id="profileModal" class="modal">
            <div id="profileContent" class="modal-content">
                <h3>こちらの情報でプロフィールを更新します</h3>
                <form method="post" id="modalForm" action="/index.php?action=saveProfile" method="post">
                </form>
                <button class="btn" id="cancelBtn">キャンセル</button>
            </div>
        </div>
    </div>
</body>