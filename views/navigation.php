<?php
function getNavItems($name = '') {
    return  [
        ['act' => "#", 'label' => 'ホーム', 'admin_only' => false],
        ['act' => "index.php?action=admin", 'label' => '管理者ページ', 'admin_only' => true],
        ['act' => "index.php?action=dashboard", 'label' => 'ダッシュボード', 'admin_only' => false],
        ['act' => "index.php?action=profile&name=". $name, 'label' => 'プロフィール', 'admin_only' => false],
        ['act' => "index.php?action=skillList", 'label' => 'スキルマップ', 'admin_only' => false],
        ['act' => "index.php?action=logout", 'label' => 'ログアウト', 'admin_only' => false],
        ['id' => 'changePw', 'act' => "", 'label' => 'パスワード変更', 'admin_only' => false]
    ];
}
