<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>スキルマップ</title>
    <link rel="stylesheet" href="/views/css/input.css">
    <link rel="stylesheet" href="/views/css/header.css">
    <link rel="stylesheet" href="/views/css/skillList.css">
    <script src="/views/js/skillList.js"></script>
    <script src="/views/js/toggle.js"></script>
    <script src="/views/js/admin.js"></script>
</head>
<body>
    <header class="portal-header">
        <div class="header-container">
            <!-- ロゴやサイト名 -->
            <div class="header-logo">
                <a href="index.php?action=skills">スキルマップ</a>
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
               
                foreach ($navItems as $item): 
                        // 管理者専用ページの権限チェック
                        if (!empty($item['admin_only']) && (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')) {
                            continue;
                        }
                        if($item['label'] === 'スキルマップ') {
                            continue; // スキルマップは表示しない
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
            </ul>
        </nav>
    </header>
    <div class="container">
        <h2>スキルマップ</h2>
        <div class="groupBar">
            <span class="hiddenSpan">グループ表示：</span>
            <button type="button" class="chip" id="officeGroup">事務系（<span id="officeNum"></span>）</button>
            <button type="button" class="chip" id="programGroup">プログラム系（<span id="programNum"></span>）</button>
            <button type="button" class="chip" id="allReborn">すべて再表示</button>
        </div>
        <div id="hiddenChipsBar">
            <span class="hiddenSpan">非表示中の列（クリックで復元）:</span>
            <div id="hiddenChipsContainer" class=""></div>
        </div>
        <div class="table-container">
            <table id="skillTable" class="corporate-table">
                <thead>
                    <tr>
                        <th id="name" class="columnName" rowspan="2" data-col-index="0" data-sort-type="text">
                            名前<button class="sort-btn">↕</button><br>
                            <input type="text" id="search" class="column-search">
                        </th>
                        <th id="office-head" colspan="10" data-skill-type="office">事務系</th>
                        <th id="programming-head" colspan="11" data-skill-type="program">プログラミング系</th>
                    </tr>
                    <tr>
                        <th id="word" class="columnName officeColumn" data-col-index="1" data-skill-type="office" data-skill-name="Word">
                            Word<button class="sort-btn">↕</button>
                            <button type="button" class="hide-col-btn">×</button><br>
                            <select class="column-search column-filter" data-column-index="1">
                                <option value="1">未経験</option>
                                <option value="2">1年未満</option>
                                <option value="3">1年以上</option>
                                <option value="4">2年以上</option>
                                <option value="5">3年以上</option>
                            </select>
                        </th>
                        <th class="columnName officeColumn" data-col-index="2" data-skill-type="office" data-skill-name="Excel">
                            Excel<button class="sort-btn">↕</button>
                            <button type="button" class="hide-col-btn">×</button><br>
                            <select class="column-search column-filter" data-column-index="2">
                                <option value="1">未経験</option>
                                <option value="2">1年未満</option>
                                <option value="3">1年以上</option>
                                <option value="4">2年以上</option>
                                <option value="5">3年以上</option>
                            </select>
                        </th>
                        <th class="columnName officeColumn" data-col-index="3" data-skill-type="office" data-skill-name="PowerPoint">
                            PowerPoint<button class="sort-btn">↕</button>
                            <button type="button" class="hide-col-btn">×</button><br>
                            <select class="column-search column-filter" data-column-index="3">
                                <option value="1">未経験</option>
                                <option value="2">1年未満</option>
                                <option value="3">1年以上</option>
                                <option value="4">2年以上</option>
                                <option value="5">3年以上</option>
                            </select>
                        </th>
                        <th class="columnName officeColumn" data-col-index="4" data-skill-type="office" data-skill-name="Microsoft Outlook">
                            Microsoft Outlook<button class="sort-btn">↕</button>
                            <button type="button" class="hide-col-btn">×</button><br>
                            <select class="column-search column-filter" data-column-index="4">
                                <option value="1">未経験</option>
                                <option value="2">1年未満</option>
                                <option value="3">1年以上</option>
                                <option value="4">2年以上</option>
                                <option value="5">3年以上</option>
                            </select>
                        </th>
                        <th class="columnName officeColumn" data-col-index="5" data-skill-type="office" data-skill-name="Google スプレッドシート">
                            Google スプレッドシート<button class="sort-btn">↕</button>
                            <button type="button" class="hide-col-btn">×</button><br>
                            <select class="column-search column-filter" data-column-index="5">
                                <option value="1">未経験</option>
                                <option value="2">1年未満</option>
                                <option value="3">1年以上</option>
                                <option value="4">2年以上</option>
                                <option value="5">3年以上</option>
                            </select>
                        </th>
                        <th class="columnName officeColumn" data-col-index="6" data-skill-type="office" data-skill-name="Microsoft Teams / Slack">
                            Microsoft Teams / Slack<button class="sort-btn">↕</button>
                            <button type="button" class="hide-col-btn">×</button><br>
                            <select class="column-search column-filter" data-column-index="6">
                                <option value="1">未経験</option>
                                <option value="2">1年未満</option>
                                <option value="3">1年以上</option>
                                <option value="4">2年以上</option>
                                <option value="5">3年以上</option>
                            </select>
                        </th>
                        <th class="columnName officeColumn" data-col-index="7" data-skill-type="office" data-skill-name="Adobe Acrobat">
                            Adobe Acrobat<!--（PDF編集・閲覧ソフト）--><button class="sort-btn">↕</button>
                            <button type="button" class="hide-col-btn">×</button><br>
                            <select class="column-search column-filter" data-column-index="7">
                                <option value="1">未経験</option>
                                <option value="2">1年未満</option>
                                <option value="3">1年以上</option>
                                <option value="4">2年以上</option>
                                <option value="5">3年以上</option>
                            </select>
                        </th>
                        <th class="columnName officeColumn" data-col-index="8" data-skill-type="office" data-skill-name="クラウドストレージ">
                            クラウドストレージ<!--（OneDrive / Google Drive / Box 等）--><button class="sort-btn">↕</button>
                            <button type="button" class="hide-col-btn">×</button><br>
                            <select class="column-search column-filter" data-column-index="8">
                                <option value="1">未経験</option>
                                <option value="2">1年未満</option>
                                <option value="3">1年以上</option>
                                <option value="4">2年以上</option>
                                <option value="5">3年以上</option>
                            </select>
                        </th>
                        <th class="columnName officeColumn" data-col-index="9" data-skill-type="office" data-skill-name="会計・経理ソフト">
                            会計・経理ソフト<!--（弥生会計、freee、マネーフォワード等）--><button class="sort-btn">↕</button>
                            <button type="button" class="hide-col-btn">×</button><br>
                            <select class="column-search column-filter" data-column-index="9">
                                <option value="1">未経験</option>
                                <option value="2">1年未満</option>
                                <option value="3">1年以上</option>
                                <option value="4">2年以上</option>
                                <option value="5">3年以上</option>
                            </select>
                        </th>
                        <th class="columnName officeColumn" data-col-index="10" data-skill-type="office" data-skill-name="基幹システム・ERP">
                            基幹システム・ERP<!-- / 販売管理ソフト（各種専用システム）--><button class="sort-btn">↕</button>
                            <button type="button" class="hide-col-btn">×</button><br>
                            <select class="column-search column-filter" data-column-index="10">
                                <option value="1">未経験</option>
                                <option value="2">1年未満</option>
                                <option value="3">1年以上</option>
                                <option value="4">2年以上</option>
                                <option value="5">3年以上</option>
                            </select>
                        </th>
                        <th class="columnName programmingColumn" data-col-index="11" data-skill-type="program" data-skill-name="Python">
                            Python<button class="sort-btn">↕</button>
                            <button type="button" class="hide-col-btn">×</button><br>
                            <select class="column-search column-filter" data-column-index="11">
                                <option value="1">未経験</option>
                                <option value="2">1年未満</option>
                                <option value="3">1年以上</option>
                                <option value="4">2年以上</option>
                                <option value="5">3年以上</option>
                            </select>
                        </th>
                        <th class="columnName programmingColumn" data-col-index="12" data-skill-type="program" data-skill-name="HTML5/CSS3">
                            HTML5/CSS3<button class="sort-btn">↕</button>
                            <button type="button" class="hide-col-btn">×</button><br>
                            <select class="column-search column-filter" data-column-index="12">
                                <option value="1">未経験</option>
                                <option value="2">1年未満</option>
                                <option value="3">1年以上</option>
                                <option value="4">2年以上</option>
                                <option value="5">3年以上</option>
                            </select>
                        </th>
                        <th class="columnName programmingColumn" data-col-index="13" data-skill-type="program" data-skill-name="JavaScript">
                            JavaScript<button class="sort-btn">↕</button>
                            <button type="button" class="hide-col-btn">×</button><br>
                            <select class="column-search column-filter" data-column-index="13">
                                <option value="1">未経験</option>
                                <option value="2">1年未満</option>
                                <option value="3">1年以上</option>
                                <option value="4">2年以上</option>
                                <option value="5">3年以上</option>
                            </select>
                        </th>
                        <th class="columnName programmingColumn" data-col-index="14" data-skill-type="program" data-skill-name="TypeScript">
                            TypeScript<button class="sort-btn">↕</button>
                            <button type="button" class="hide-col-btn">×</button><br>
                            <select class="column-search column-filter" data-column-index="14">
                                <option value="1">未経験</option>
                                <option value="2">1年未満</option>
                                <option value="3">1年以上</option>
                                <option value="4">2年以上</option>
                                <option value="5">3年以上</option>
                            </select>
                        </th>
                        <th class="columnName programmingColumn" data-col-index="15" data-skill-type="program" data-skill-name="PHP">
                            PHP<button class="sort-btn">↕</button>
                            <button type="button" class="hide-col-btn">×</button><br>
                            <select class="column-search column-filter" data-column-index="15">
                                <option value="1">未経験</option>
                                <option value="2">1年未満</option>
                                <option value="3">1年以上</option>
                                <option value="4">2年以上</option>
                                <option value="5">3年以上</option>
                            </select>
                        </th>
                        <th class="columnName programmingColumn" data-col-index="16" data-skill-type="program" data-skill-name="Java">
                            Java<button class="sort-btn">↕</button>
                            <button type="button" class="hide-col-btn">×</button><br>
                            <select class="column-search column-filter" data-column-index="16">
                                <option value="1">未経験</option>
                                <option value="2">1年未満</option>
                                <option value="3">1年以上</option>
                                <option value="4">2年以上</option>
                                <option value="5">3年以上</option>
                            </select>
                        </th>
                        <th class="columnName programmingColumn" data-col-index="17" data-skill-type="program" data-skill-name="C#">
                            C#<button class="sort-btn">↕</button>
                            <button type=" button" class="hide-col-btn">×</button><br>
                            <select class="column-search column-filter" data-column-index="17">
                                <option value="1">未経験</option>
                                <option value="2">1年未満</option>
                                <option value="3">1年以上</option>
                                <option value="4">2年以上</option>
                                <option value="5">3年以上</option>
                            </select>
                        </th>
                        <th class="columnName programmingColumn" data-col-index="18" data-skill-type="program" data-skill-name="C++">
                            C++<button class="sort-btn">↕</button>
                            <button type="button" class="hide-col-btn">×</button><br>
                            <select class="column-search column-filter" data-column-index="18">
                                <option value="1">未経験</option>
                                <option value="2">1年未満</option>
                                <option value="3">1年以上</option>
                                <option value="4">2年以上</option>
                                <option value="5">3年以上</option>
                            </select>
                        </th>
                        <th class="columnName programmingColumn" data-col-index="19" data-skill-type="program" data-skill-name="SQL">
                            SQL<button class="sort-btn">↕</button>
                            <button type="button" class="hide-col-btn">×</button><br>
                            <select class="column-search column-filter" data-column-index="19">
                                <option value="1">未経験</option>
                                <option value="2">1年未満</option>
                                <option value="3">1年以上</option>
                                <option value="4">2年以上</option>
                                <option value="5">3年以上</option>
                            </select>
                        </th>
                        <th class="columnName programmingColumn" data-col-index="20" data-skill-type="program" data-skill-name="Go">
                            Go<button class="sort-btn">↕</button>
                            <button type="button" class="hide-col-btn">×</button><br>
                            <select class="column-search column-filter" data-column-index="20">
                                <option value="1">未経験</option>
                                <option value="2">1年未満</option>
                                <option value="3">1年以上</option>
                                <option value="4">2年以上</option>
                                <option value="5">3年以上</option>
                            </select>
                        </th>
                        <th class="columnName programmingColumn" data-col-index="21" data-skill-type="program" data-skill-name="Ruby">
                            Ruby<button class="sort-btn">↕</button>
                            <button type="button" class="hide-col-btn">×</button><br>
                            <select class="column-search column-filter" data-column-index="21">
                                <option value="1">未経験</option>
                                <option value="2">1年未満</option>
                                <option value="3">1年以上</option>
                                <option value="4">2年以上</option>
                                <option value="5">3年以上</option>
                            </select>
                        </th>
                </thead>
                <?php function level($level) {
                    switch ($level) {
                        case 1:
                            return '<span class="level level-1">未経験</span>';
                        case 2:
                            return '<span class="level level-2">1年未満</span>';
                        case 3:
                            return '<span class="level level-3">1年以上</span>';
                        case 4:
                            return '<span class="level level-4">2年以上</span>';
                        case 5:
                            return '<span class="level level-5">3年以上</span>';
                        default:
                            return '<span class="level level-1">未経験</span>';
                    }
                }?>
                <tbody>
                    <?php if (!empty($skills)): ?>
                        <?php foreach ($skills as $skill): ?>
                            <tr>
                                <td data-sort-val="<?= htmlspecialchars($skill['name'], ENT_QUOTES, 'UTF-8'); ?>" >
                                    <a href="index.php?action=otherProfile&id=<?= htmlspecialchars($skill['user_id'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <?php echo htmlspecialchars($skill['name'], ENT_QUOTES, 'UTF-8'); ?>
                                    </a>
                                </td>
                                <td data-sort-val="<?php echo $skill['word']; ?>" data-skill-name="Word">
                                    <?php echo level($skill['word']); ?>
                                </td>
                                <td data-sort-val="<?php echo $skill['excel']; ?>" data-skill-name="Excel">
                                    <?php echo level($skill['excel']); ?>
                                </td>
                                <td data-sort-val="<?php echo $skill['powerpoint']; ?>" data-skill-name="PowerPoint">
                                    <?php echo level($skill['powerpoint']); ?>
                                </td>
                                <td data-sort-val="<?php echo $skill['outlook']; ?>" data-skill-name="Microsoft Outlook">
                                    <?php echo level($skill['outlook']); ?>
                                </td>
                                <td data-sort-val="<?php echo $skill['spreadsheet']; ?>" data-skill-name="Google スプレッドシート">
                                    <?php echo level($skill['spreadsheet']); ?>
                                </td>
                                <td data-sort-val="<?php echo $skill['teams']; ?>" data-skill-name="Microsoft Teams / Slack">
                                    <?php echo level($skill['teams']); ?>
                                </td>
                                <td data-sort-val="<?php echo $skill['adobe']; ?>" data-skill-name="Adobe Acrobat">
                                    <?php echo level($skill['adobe']); ?>
                                </td>
                                <td data-sort-val="<?php echo $skill['cloud']; ?>" data-skill-name="クラウドストレージ">
                                    <?php echo level($skill['cloud']); ?>
                                </td>
                                <td data-sort-val="<?php echo $skill['accounting']; ?>" data-skill-name="会計・経理ソフト">
                                    <?php echo level($skill['accounting']); ?>
                                </td>
                                <td data-sort-val="<?php echo $skill['erp']; ?>" data-skill-name="基幹システム・ERP">
                                    <?php echo level($skill['erp']); ?>
                                </td>
                                <td data-sort-val="<?php echo $skill['python']; ?>" data-skill-name="Python">
                                    <?php echo level($skill['python']); ?>
                                </td>
                                <td data-sort-val="<?php echo $skill['html']; ?>" data-skill-name="HTML5/CSS3">
                                    <?php echo level($skill['html']); ?>
                                </td>
                                <td data-sort-val="<?php echo $skill['javascript']; ?>" data-skill-name="JavaScript">
                                    <?php echo level($skill['javascript']); ?>
                                </td>
                                <td data-sort-val="<?php echo $skill['typescript']; ?>" data-skill-name="TypeScript">
                                    <?php echo level($skill['typescript']); ?>
                                </td>
                                <td data-sort-val="<?php echo $skill['php']; ?>" data-skill-name="PHP">
                                    <?php echo level($skill['php']); ?>
                                </td>
                                <td data-sort-val="<?php echo $skill['java']; ?>" data-skill-name="Java">
                                    <?php echo level($skill['java']); ?>
                                </td>
                                <td data-sort-val="<?php echo $skill['csharp']; ?>" data-skill-name="C#">
                                    <?php echo level($skill['csharp']); ?>
                                </td>
                                <td data-sort-val="<?php echo $skill['cplusplus']; ?>" data-skill-name="C++">
                                    <?php echo level($skill['cplusplus']); ?>
                                </td>
                                <td data-sort-val="<?php echo $skill['sql']; ?>" data-skill-name="SQL">
                                    <?php echo level($skill['sql']); ?>
                                </td>
                                <td data-sort-val="<?php echo $skill['go']; ?>" data-skill-name="Go">
                                    <?php echo level($skill['go']); ?>
                                </td>
                                <td data-sort-val="<?php echo $skill['ruby']; ?>" data-skill-name="Ruby">
                                    <?php echo level($skill['ruby']); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="22">現在スキル情報はありません。</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
        </div>
       
       
        <a href="/index.php?action=logout">ログアウト</a>
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