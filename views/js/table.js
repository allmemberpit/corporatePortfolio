window.addEventListener('DOMContentLoaded', () => {
    const receiptButtons = document.querySelectorAll('.view-receipt-btn');
    const modal = document.getElementById('receiptModal');
    const modalImage = document.getElementById('modalImage');

    // 2. 各ボタンにクリックイベントを設定
    receiptButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            // ボタンの data-receipt 属性からパスを取得
            const receiptPath = e.currentTarget.dataset.receipt;

            if (!receiptPath) {
                alert("書類ファイルが登録されていません。");
                return;
            }

            // モーダルの img タグの src にパスをセット
            modalImage.src = receiptPath;

            // モーダルを表示する
            modal.style.display = 'flex';
        });
    });

  
    // 4. モーダルの背景部分をクリックしても閉じられるようにする
    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
            modalImage.src = '';
        }
    });
});

function sortTable(thElem, columnIndex) {
    const table = thElem.closest("table");
    const tbody = table.querySelector("tbody");
    const rows = Array.from(tbody.querySelectorAll("tr"));

    let currentAsc = table.dataset.sortCol == columnIndex && table.dataset.sortAsc === "true";
    let nextAsc = !currentAsc;

    table.dataset.sortCol = columnIndex;
    table.dataset.sortAsc = nextAsc;

    // 【1】クリックされた列にボタン（input[type="submit"]）が含まれているか判定する
    const isButtonColumn = rows.some(row => {
        const cell = row.querySelectorAll("td")[columnIndex];
        return cell && cell.querySelector('input[type="submit"]') !== null;
    });

    rows.sort((rowA, rowB) => {
        const cellA = rowA.querySelectorAll("td")[columnIndex];
        const cellB = rowB.querySelectorAll("td")[columnIndex];

        let comparison = 0;

        // 【2】ボタンのある列がクリックされた場合のみ、ボタンの有無をソート基準にする
        if (isButtonColumn) {
            const priorityA = getButtonPriority(cellA);
            const priorityB = getButtonPriority(cellB);

            if (priorityA !== priorityB) {
                // 基準となる比較（ボタンがある方を上にする場合）
                const comp = priorityB - priorityA; 
                // 【3】矢印（nextAsc）の向きに合わせて、上にするか下にするかを反転させる
                return nextAsc ? comp : -comp;
            }
            // ボタンの有無が同じ行同士なら、以下の通常のソート処理へ進む
        }

        // --- 【4】通常のソート処理（日付・数値・文字列） ---
      
        // data-value属性があればそれを数値として使う。なければ通常のテキストを使う
        const valA = cellA.dataset.value !== undefined ? cellA.dataset.value : cellA.textContent.trim();
        const valB = cellB.dataset.value !== undefined ? cellB.dataset.value : cellB.textContent.trim();

        const numA = Number(valA);
        const numB = Number(valB);
        if (!isNaN(numA) && !isNaN(numB)) {
            comparison = numA - numB;
        } else {
            comparison = valA.localeCompare(valB, 'ja');
        }
        

        return nextAsc ? comparison : -comparison;
    });

    tbody.innerHTML = "";
    rows.forEach(row => tbody.appendChild(row));

    updateSortIcons(table, thElem, nextAsc);
}

// 矢印マークの更新処理を行うサブ関数
function updateSortIcons(table, activeTh, isAsc) {
    // テーブル内の全 th からアイコン要素を取得し、一旦「↕」に初期化
    const allThs = table.querySelectorAll("thead th");
    allThs.forEach(th => {
        const icon = th.querySelector(".sort-icon");
        if (icon) {
            icon.textContent = "↕";
        }
    });

    // クリックされた th のアイコンだけ「▲」または「▼」に書き換える
    const activeIcon = activeTh.querySelector(".sort-icon");
    if (activeIcon) {
        activeIcon.textContent = isAsc ? "▲" : "▼";
    }
}

// 【ヘルパー関数】セルのボタンの種類から優先度（重み）を返す
function getButtonPriority(cell) {
    if (!cell) return 0;
    const submitBtn = cell.querySelector('input[type="submit"]');
    if (!submitBtn) return 0;

    const value = submitBtn.value.trim();
    if (value === '修正') return 2; // 「修正」が最も優先度を高くする
    if (value === '確認') return 1; // 「確認」はその次
    return 1; // その他のsubmitボタンがある場合
}