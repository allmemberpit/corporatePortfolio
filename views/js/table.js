window.addEventListener('DOMContentLoaded', () => {
    const receiptButtons = document.querySelectorAll('.view-receipt-btn');
    const modal = document.getElementById('receiptModal');
    const modalImage = document.getElementById('modalImage');
    const closeBtn = document.querySelector('.close-btn');

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

    // 3. 閉じるボタン（×）が押されたらモーダルを非表示にする
    closeBtn.addEventListener('click', () => {
        modal.style.display = 'none';
        modalImage.src = ''; // 読み込み保持を防ぐためにクリア
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
            const hasButtonA = cellA && cellA.querySelector('input[type="submit"]') !== null ? 1 : 0;
            const hasButtonB = cellB && cellB.querySelector('input[type="submit"]') !== null ? 1 : 0;

            if (hasButtonA !== hasButtonB) {
                // 基準となる比較（ボタンがある方を上にする場合）
                const comp = hasButtonB - hasButtonA; 
                
                // 【3】矢印（nextAsc）の向きに合わせて、上にするか下にするかを反転させる
                return nextAsc ? comp : -comp;
            }
            // ボタンの有無が同じ行同士なら、以下の通常のソート処理へ進む
        }

        // --- 【4】通常のソート処理（日付・数値・文字列） ---
        const textA = cellA ? cellA.textContent.trim() : "";
        const textB = cellB ? cellB.textContent.trim() : "";

        /*const timeA = parseDateToTimestamp(textA);
        const timeB = parseDateToTimestamp(textB);*/

        
        const numA = Number(textA);
        const numB = Number(textB);
        if (!isNaN(numA) && !isNaN(numB)) {
            comparison = numA - numB;
        } else {
            comparison = textA.localeCompare(textB, 'ja');
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