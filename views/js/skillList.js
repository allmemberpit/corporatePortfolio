document.addEventListener('DOMContentLoaded', () => {
    const table = document.getElementById('skillTable');
    //グループ表示の数を数える。
    const officeNum = document.getElementById('officeNum');
    const programNum = document.getElementById('programNum');
    const officeHead = document.getElementById('office-head');
    const programmingHead = document.getElementById('programming-head');
    const officeClm = officeHead.getAttribute('colspan');
    const programClm = programmingHead.getAttribute('colspan');
    officeNum.innerText = officeClm + '/' + officeClm;
    programNum.innerText = programClm + '/' + programClm;
    // ① 各th内のソートボタン（▲▼）による並べ替え機能
    let currentSortCol = null;
    let currentSortDir = 'asc';
    const sortButton = '↕';
    table.querySelectorAll('thead th').forEach(th => {
        const sortBtn = th.querySelector('.sort-btn');
        if (!sortBtn) return;

        sortBtn.addEventListener('click', () => {
            const colIndex = parseInt(th.dataset.colIndex, 10);
            const sortType = th.dataset.sortType;
            if (currentSortCol === colIndex) {
                currentSortDir = currentSortDir === 'asc' ? 'desc' : 'asc';
            } else {
                currentSortCol = colIndex;
                // スキルレベルは高い順（降順）から、社員名は昇順から開始
                currentSortDir = (sortType === 'text') ? 'asc' : 'desc';
            }

            // すべてのソートボタン表示をリセットし、選択中の列だけ強調
            table.querySelectorAll('.sort-btn').forEach(b => {
                b.textContent = sortButton;
                b.classList.remove('ring-2', 'ring-blue-400', 'bg-blue-50', 'text-blue-700');
            });
            sortBtn.textContent = currentSortDir === 'asc' ? '▲' : '▼';
            sortBtn.classList.add('ring-2', 'ring-blue-400', 'bg-blue-50', 'text-blue-700');

            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            rows.sort((rowA, rowB) => {
                const valA = rowA.cells[colIndex].dataset.sortVal;
                const valB = rowB.cells[colIndex].dataset.sortVal;
                let comparison = 0;
                if (sortType === 'text') {
                    comparison = valA.localeCompare(valB, 'ja');
                } else {
                    comparison = parseFloat(valA) - parseFloat(valB);
                }

                return currentSortDir === 'asc' ? comparison : -comparison;
            });
            rows.forEach(row => tbody.appendChild(row));
        });
    });
    //行ごとの非表示ボタン
    const hiddenChipsBar = document.getElementById('hiddenChipsBar');
    const hiddenChipsContainer = document.getElementById('hiddenChipsContainer');
    const allReborn = document.getElementById('allReborn');
    table.querySelectorAll('.hide-col-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const colIndex = parseInt(btn.parentElement.dataset.colIndex, 10);
            const th = table.querySelector(`th[data-col-index="${colIndex}"]`);
            //thを消去
            th.style.display = 'none';
            //tdの消去
            const skillName = th.dataset.skillName;
            const td = table.querySelectorAll(`td[data-skill-name="${skillName}"]`);
            td.forEach(d=>{
                d.style.display = 'none';
            });
            let head = th.dataset.skillType == 'office'?officeHead:programmingHead;
            head.setAttribute('colspan',head.getAttribute('colspan')-1);
            if(head.getAttribute('colspan')=='0'){
                head.style.display ='none';
            }else{
                head.style.display = 'table-cell';
            }
            hiddenChipsBar.style.display = 'flex';
            const hiddenChip = document.createElement('span');
            hiddenChip.textContent = th.textContent.replace(sortButton, '').replace('×','').replace('未経験','').replace('1年未満','').replace('1年以上','').replace('2年以上','').replace('3年以上','').replace('▲','').replace('▼','');   
            hiddenChip.classList.add('hidden-chip');
            hiddenChip.classList.add('chip');
            hiddenChipsContainer.appendChild(hiddenChip);
            if(th.dataset.skillType == 'office'){
                hiddenChip.classList.add('hidden-office');
                hiddenChip.dataset.type='office';
                officeNum.innerText = head.getAttribute('colspan') + '/' + officeClm;
            }else{
                hiddenChip.classList.add('hidden-program');
                hiddenChip.dataset.type='program';
                programNum.innerText = head.getAttribute('colspan') + '/' + programClm;
            }
            
            allReborn.style.display = 'block';

            //カラムの復活ボタンを押したとき
            hiddenChip.addEventListener('click', () => {
                th.style.display = 'table-cell';
                td.forEach(d=>{
                    d.style.display = 'table-cell';
                });
                hiddenChipsContainer.removeChild(hiddenChip);
                let headId = hiddenChip.dataset.type == 'office'?'office-head':'programming-head';
                head = document.getElementById(headId);
                head.setAttribute('colspan',Number(head.getAttribute('colspan'))+1);
                head.style.display = 'table-cell';
                if(hiddenChip.dataset.type == 'office'){
                    officeNum.innerText = head.getAttribute('colspan') + '/' + officeClm;
                }else{
                    programClm.innerText = head.getAttribute('colspan') + '/' + programClm;
                }
                
                if (hiddenChipsContainer.children.length === 0) {
                    hiddenChipsBar.style.display = 'none';
                    allReborn.style.display = 'none';
                }
            });
        });
    });
     //すべて再表示ボタンを押したとき
    allReborn.addEventListener('click', () => {
        allReborn.style.display = 'none';
        table.querySelectorAll('thead th').forEach(th => {
            th.style.display = 'table-cell';
        });
        table.querySelectorAll('tbody td').forEach(td => {
            td.style.display = 'table-cell';
        });
        officeHead.setAttribute('colspan',officeClm);
        programmingHead.setAttribute('colspan',programClm);
        officeNum.innerText = officeClm + '/' + officeClm;
        programNum.innerText = programClm + '/' + programClm;
        while (hiddenChipsContainer.firstChild) {
            hiddenChipsContainer.removeChild(hiddenChipsContainer.firstChild);
        }
        if (hiddenChipsContainer.children.length === 0) {
            hiddenChipsBar.style.display = 'none';
            allReborn.style.display = 'none';
        }
    });
    //事務系ボタンを押したとき
    const officeGroup = document.getElementById('officeGroup');
    officeGroup.addEventListener('click',()=>{typeGroup(table,hiddenChipsContainer,hiddenChipsBar,allReborn,officeHead,officeClm,'office',officeNum);});
    const programGroup = document.getElementById('programGroup');
    programGroup.addEventListener('click',()=>{typeGroup(table,hiddenChipsContainer,hiddenChipsBar,allReborn,programmingHead,programClm,'program',programNum);}); 
    function typeGroup(table,hiddenChipsContainer,hiddenChipsBar,allReborn,head,clm,type,num){
        head.setAttribute('colspan',clm);
        num.innerText = clm + '/' + clm;
        table.querySelectorAll('thead th').forEach(th => {
            if(th.dataset.skillType == type){
                th.style.display = 'table-cell';
            }  
            const skillName = th.dataset.skillName;
            const td = table.querySelectorAll(`td[data-skill-name="${skillName}"]`);
            td.forEach(d=>{
                d.style.display = 'table-cell';
            });
        });
        hiddenChipsContainer.querySelectorAll(`[data-type="${type}"]`).forEach(child =>{
            if(child.dataset.type == type){
                hiddenChipsContainer.removeChild(child);
            }
        });
        if (hiddenChipsContainer.children.length === 0) {
            hiddenChipsBar.style.display = 'none';
            allReborn.style.display = 'none';
        }
    }
    //フィルターを押したとき
    const filters = table.querySelectorAll('.column-filter');
    filters.forEach(filter => {
        filter.addEventListener('change',applyFilters);
    });
    //検索欄に入力したとき
    const search = document.getElementById("search");
    if(search){search.addEventListener('input', applyFilters);}
    function applyFilters(){
        const tbody = table.querySelector('tbody');
        const rows = tbody.querySelectorAll('tr');

        // 前後の空白を除き、小文字化（英字混在にも対応）
        const nameQuery = search ? search.value.trim().toLowerCase() : '';

        rows.forEach(row => {
            let isVisible = true;

            // 1. 社員名（1列目 / index 0）の部分一致チェック
            if (nameQuery !== '') {
                const nameCell = row.cells[0];
                const nameText = nameCell ? nameCell.textContent.trim().toLowerCase() : '';
                if (!nameText.includes(nameQuery)) {
                    isVisible = false;
                }
            }

            // 2. 各スキル列フィルターのチェック（AND条件）
            if (isVisible) {
                filters.forEach(filter => {
                    const requiredLevel = filter.value;
                    
                    if (requiredLevel === '') return;

                    const colIndex = parseInt(filter.dataset.columnIndex, 10);
                    const cell = row.cells[colIndex];
                    if (cell) {
                        const cellValue = cell.dataset.sortVal;;
                        if (cellValue < requiredLevel) {
                            isVisible = false;
                        }
                    }
                });
            }

            row.style.display = isVisible ? '' : 'none';
        });
    }
    
});