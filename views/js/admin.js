 function confirmReset(userName, defaultPassword) {
    // window.confirmのダイアログに、誰のパスワードを何にリセットするかを表示する
    const message = `${userName} さん のパスワードを、初期値「${defaultPassword}」にリセットしますか？`;
    
    // OKなら true（送信実行）、キャンセルなら false（送信中止）を返す
    return window.confirm(message);
}
window.addEventListener('DOMContentLoaded', () => {
    const pwModal   = document.getElementById('pwModal');
    const sumitBtn  = document.getElementById('submitBtn');
    const changePw = document.getElementById('changePw');
    const newPw     = document.getElementById('newPw');
    const confPw    = document.getElementById('confPw');

    changePw.addEventListener('click', () => {
        pwModal.style.display = 'block';
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