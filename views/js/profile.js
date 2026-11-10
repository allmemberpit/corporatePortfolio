window.addEventListener('DOMContentLoaded', () => {
    // ＋ボタンを押すとスライドして開く処理。実際はh3タグ全体を押しても開く
    const accordionH3 =  document.querySelectorAll('.accordionH3');
    accordionH3.forEach(button => {
        button.addEventListener('click', () => {
            const targetClass = button.dataset.target;
            const targetElement = document.getElementById(targetClass); // または querySelector
            
            const modeSpan = button.querySelector('.accordionMode');
            if (targetElement) {
                // 開閉クラスの切り替えや、スクロール処理
                targetElement.classList.toggle('is-open');
                const isOpen = targetElement.classList.contains('is-open');
                if (modeSpan) {
                    modeSpan.textContent = isOpen ? '－':'＋';
                }
                // スムーズスクロールする場合
                targetElement.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    //編集ボタンを押したときの処理
    const edit   = document.getElementById('edit');
    const save   = document.getElementById('save');
    const cancel   = document.getElementById('cancel');
    const datas  = document.querySelectorAll('.profileData');
    const inputs = document.querySelectorAll('.profileInput');
    edit.addEventListener('click', () => {
        datas.forEach((data,i)=>{
            if (inputs[i]) {
                // 順番（インデックス）が一致する入力欄に値をセット
                data.style.display = 'none';
                if(inputs[i].type=='date'){
                    inputs[i].value = data.dataset.date;
                }else{
                    inputs[i].value = data.textContent;
                }
            }
        });
        inputs.forEach(input => {
            input.style.display = 'inline-block';
        });
        edit.style.display = 'none';
        save.style.display = 'inline-block';
        cancel.style.display = 'inline-block';
    });

    cancel.addEventListener('click', () => {
        datas.forEach((data,i)=>{
            if (inputs[i]) {
                data.style.display = 'inline-block';
                inputs[i].style.display = 'none';
            }
        });
        edit.style.display = 'inline-block';
        save.style.display = 'none';
        cancel.style.display = 'none';
    });

    const profileModal = document.getElementById('profileModal');
    const modalForm = document.getElementById('modalForm');
    const categoryTitles = document.querySelectorAll('.profileCategory');
    const names = ['userName', 'birthday', 'address', 
        'nearest_station', 'word','excel','powerpoint','outlook',
        'spreadsheet','teams','adobe','cloud','accounting','erp','office_other',
        'python','html','javascript','typescript','php','java','csharp','c++',
        'sql','go','ruby','programming_other'
    ];

    save.addEventListener('click', () => {
        // モーダル表示
        profileModal.style.display = 'block';
        let html = '';
        inputs.forEach((input,i) => {
            let value = '';
            if (input.type === 'date') {
                let birthDate = input.value ? new Date(input.value) : null;
                let today = new Date();
                const year = birthDate ? birthDate.getFullYear() : '';
                const month = birthDate ? birthDate.getMonth() + 1 : '';
                const day = birthDate ? birthDate.getDate() : '';
                let age = today.getFullYear() - year;
                if (today.getMonth() + 1 < month || (today.getMonth() + 1 === month && today.getDate() < day)) {
                    age--;
                }
                value = `<input type="hidden" class="modalData" name="${names[i]}" value="${input.value}">
                    <span class="modalData">${year}年${month}月${day}日（${age}歳）</span>`;
            }else if (input.tagName === 'INPUT' || input.tagName === 'TEXTAREA') {
                value = `<input type="hidden" class="modalData" name="${names[i]}" value="${input.value}">
                    <span class="modalData">${input.value}</span>`;
            }else if (input.tagName === 'DIV') {
                const selectedRadio = input.querySelector(`input[type="radio"]:checked`);
                let  radioValue = selectedRadio ? selectedRadio.value : '';
                switch (radioValue) {
                    case '1':
                        value = '未経験';
                        break;
                    case '2':
                        value = '1年未満';
                        break;
                    case '3':
                        value = '1年以上';
                        break;
                    case '4':
                        value = '2年以上';
                        break;
                    case '5':
                        value = '3年以上';
                        break;
                    default:
                        value = '未経験';
                }
                value = `<input type="hidden" class="modalData" name="${names[i]}" value="${radioValue}">
                    <span class="modalData" >${value}</span>`;
            }
            html += `<div class="modal-row">
                        <span class="modalCategory">${categoryTitles[i].innerText}</span>
                        ${value}
                    </div>`;
        });
        html += `<button id="submitBtn" class="btn" type="submit">更新</button>`;
        modalForm.innerHTML = html;
    });
    const cancelBtn = document.getElementById('cancelBtn');
    cancelBtn.addEventListener('click', () => {
        // キャンセルボタンがクリックされたときの処理
        profileModal.style.display = 'none';
    });
    // モーダルの背景（黒い部分）をクリックした時も閉じる
    window.addEventListener('click', () => {
        if (event.target === profileModal) {
            profileModal.style.display = 'none';
        }
    });
});