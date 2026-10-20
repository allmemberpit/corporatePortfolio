window.addEventListener('DOMContentLoaded', () => {
    const reportBtn        = document.getElementById('reportBtn');
    const reportModal      = document.getElementById('reportModal');
    const postReport       = document.getElementById('postReport');
    const cancelBtn        = document.getElementById('cancelBtn');
    const appBtn           = document.getElementById('appBtn');
    const appModal         = document.getElementById('appModal');
    const reportForm       = document.getElementById('report');
    //const closeAppModalBtn = document.querySelectorAll('.closeAppModalBtn');
    const typeList         = document.getElementById('typeList');
    const restContainer    = document.getElementById('restContainer');
    const tripContainer    = document.getElementById('tripContainer');
    const expenseContainer = document.getElementById('expenseContainer');
    const regularContainer = document.getElementById('regularContainer');

    // 日報ボタンがクリックされたときの処理
    reportBtn.addEventListener('click', () => {
        reportModal.style.display = 'block';
        postReport.action = '/index.php?action=submitReport';
    });
    //申請書ボタンがクリックされたときの処理
    appBtn.addEventListener('click', () => {
        appModal.style.display = 'block';
    });
    // 日報修正ボタンがクリックされたときの処理
    document.querySelectorAll('.modifyReportBtn').forEach(button => {
        button.addEventListener('click', () => {
            reportModal.style.display = 'block';
            reportForm.value = button.dataset.content;
            postReport.action = `/index.php?action=modifyReport&id=${button.dataset.id}`;
        });
    });
    // 申請書修正ボタンがクリックされたときの処理
    document.querySelectorAll('.modifyAppBtn').forEach(button => {
        button.addEventListener('click', () => {
            appModal.style.display = 'block';
            //reportForm.value = button.dataset.content;
            const content = JSON.parse(button.dataset.content);
            allClose();
            switch(content.category){
                case 1:
                    typeList.value = '休暇申請';
                    restAppModal(content);
                    break;
                case 2:
                    typeList.value = '出張申請';
                    tripAppModal(content);
                    break;
                case 4:
                    typeList.value = '定期申請';
                    regularAppModal(content);
                    break;
                //経費のみcategoryが経費精算のcategory になったため、その他になる
                default:
                    typeList.value = '経費精算';
                    expenseAppModal(content);
            }
            //申請書項目を操作されると新規になるので、ロック
            typeList.disabled = true;
        });
    });
    
    cancelBtn.addEventListener('click', () => {
        // キャンセルボタンがクリックされたときの処理
        reportModal.style.display = 'none';
    });
    document.querySelectorAll('.closeAppModalBtn').forEach(button => {
        button.addEventListener('click', () => {
            // アプリケーション情報モーダルの閉じるボタンがクリックされたときの処理
            allClose();
            appModal.style.display = 'none';
            typeList.value = 'default';
        });
    });
    // モーダルの背景（黒い部分）をクリックした時も閉じる
    window.addEventListener('click', (event) => {
        if (event.target === reportModal) {
            reportModal.style.display = 'none';
        }
        if (event.target === appModal) {
            allClose();
            appModal.style.display = 'none';
            typeList.value = 'default';
        }
    });

    // 日報の種類を選択したときの処理
    typeList.addEventListener('change', () => {
        const selectedType = typeList.value;
        selectType(selectedType);
    });
   
    function selectType(type) {
        const today = new Date().toISOString().split('T')[0];
        allClose();
        switch (type) {
            case '休暇申請':
                restContainer.style.display = 'block';
                break;
            case '出張申請':
                // 開始日と終了日のデフォルトを本日に設定
                document.getElementById('startDate').value = today;
                document.getElementById('endDate').value = today;
                tripContainer.style.display = 'block';
                break;
            case '経費精算':
                document.getElementById('expenseDate').value = today;
                expenseContainer.style.display = 'block';
                break;
            case '定期申請':
                regularContainer.style.display = 'block';
                break;
        };
    }
    // すべての申請書コンテナを非表示にする関数
    function allClose() {
        restContainer.style.display = 'none';
        tripContainer.style.display = 'none';
        expenseContainer.style.display = 'none';
        regularContainer.style.display = 'none';
        
        restContainer.action    = `/index.php?action=submitRest`;
        tripContainer.action    = `/index.php?action=submitTrip`;
        expenseContainer.action = `/index.php?action=submitExpense`;
        regularContainer.action = `/index.php?action=submitRegular`;

        typeList.disabled = false;
    }

    //申請書修正ボタンが押されたときの処理。休暇
    function restAppModal(app){
       restContainer.style.display = 'block';
       document.querySelector(`input[name="leave_type"][value="${app.leave_type}"]`).checked = true;
       document.getElementById('targetDate').value = app.target_date;
       document.getElementById('reasonInput').value = app.reason;
       document.getElementById('leaveMemo').value = app.memo;
       restContainer.action = `/index.php?action=modifyRest&id=${app.application_id}`;
    }
    //申請書修正ボタンが押されたときの処理。休暇
    function tripAppModal(app){
        tripContainer.style.display = 'block';
        document.getElementById('startDate').value   = app.start_date;
        document.getElementById('endDate').value     = app.end_date;
        document.getElementById('destination').value = app.destination;
        document.getElementById('tripPurpose').value = app.purpose;
        document.getElementById('estimatedCost').value = app.cost;
        document.querySelector(`input[name="has_lodging"][value="${app.lodging}"]`).checked = true
        document.getElementById('tripDetail').value = app.detail;
        tripContainer.action = `/index.php?action=modifyTrip&id=${app.application_id}`;
    }

    //申請書修正ボタンが押されたときの処理。経費
    function expenseAppModal(app){
        expenseContainer.style.display = 'block';
        document.getElementById('expenseDate').value = app.expense_date;
        document.getElementById('category').value = app.category;
        document.getElementById('payee').value = app.payee;
        document.getElementById('amount').value = app.amount;
        document.getElementById('expensePurpose').value = app.purpose;
        document.getElementById('expenseMemo').value = app.detail;
        expenseContainer.action = `/index.php?action=modifyExpense&id=${app.application_id}`;
    }

    function regularAppModal(app){
        regularContainer.style.display = 'block';
        document.querySelector(`input[name="apply_type"][value="${app.apply_type}"]`).checked = true;
        document.getElementById('departure').value = app.departure;
        document.getElementById('regDestination').value = app.destination;
        document.getElementById('viaRoute').value = app.viaRoute;
        document.getElementById('period').value = app.period;
        document.getElementById('regStartDate').value = app.start_date;
        document.getElementById('passAmount').value = app.passAmount;
        regularContainer.action = `/index.php?action=modifyRegular&id=${app.application_id}`;
    }
});

    