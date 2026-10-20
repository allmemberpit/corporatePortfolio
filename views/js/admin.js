window.addEventListener('DOMContentLoaded', () => {
    const changePw  = document.querySelectorAll('.changePw');
    const pwModal   = document.getElementById('pwModal');
    const cancelBtn = document.getElementById('cancelBtn');
    const sumitBtn  = document.getElementById('submitBtn');
    const oldPw     = document.getElementById('oldPw');
    const newPw     = document.getElementById('newPw');
    const confPw    = document.getElementById('confPw');
    const npCaution = document.getElementById('npCaution');
    const cpCaution = document.getElementById('cpCaution');
    const pwId      = document.getElementById('pwId');


    changePw.forEach(button => {
        button.addEventListener('click', () => {
            pwModal.style.display = 'block';
            sumitBtn.disabled = true;
            pwId.value = button.dataset.id;
        });
    });
    cancelBtn.addEventListener('click', () => {
        // キャンセルボタンがクリックされたときの処理
        closeModal();
    });

    newPw.addEventListener('change',()=>{
        //パスワードが元のパスワードと一緒の時の処理
        if(oldPw.value == newPw.value){
            npCaution.style.display = 'block';
            sumitBtn.disabled = true;
        }else{
            npCaution.style.display = 'none';
            if(confPw.value == newPw.value){
                sumitBtn.disabled = false;
            }
        }
    });
    confPw.addEventListener('change',()=>{
        //パスワードが元のパスワードと一緒の時の処理
        if(confPw.value == newPw.value){
            cpCaution.style.display = 'none';
            if(oldPw.value != newPw.value){
                sumitBtn.disabled = false;
            }
        }else{
            cpCaution.style.display = 'block';
            sumitBtn.disabled = true;
        }
    });

    // モーダルの背景（黒い部分）をクリックした時も閉じる
    window.addEventListener('click', (event) => {
        if (event.target === pwModal) {
            closeModal();
        }
    });
    //モーダルを閉じる処理
    function closeModal() {
        pwModal.style.display = 'none';
        sumitBtn.disabled = true;
        oldPw.value = '';
        newPw.value = '';
        confPw.value = '';
    }
});