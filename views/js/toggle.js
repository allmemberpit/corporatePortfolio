window.addEventListener('DOMContentLoaded', () => {
const menuToggle = document.getElementById('menuToggle');
    const dropdownMenu = document.getElementById('dropdownMenu');
    menuToggle.addEventListener('click', () => {
        // .is-open クラスをトグル（あれば削除、なければ追加）する
        dropdownMenu.classList.toggle('is-open');
    });

    // （おまけ）画面の別の場所をクリックしたらメニューを閉じるようにする場合
    document.addEventListener('click', (e) => {
        if (!menuToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
            dropdownMenu.classList.remove('is-open');
        }
    });
});