document.addEventListener('DOMContentLoaded',()=>{
    let account = document.querySelector('.header_account');

    account.addEventListener('click',()=>{
        let floatingwindow = account.querySelector('.floating_window_user');
        if(floatingwindow){
            if(floatingwindow.classList.contains('close')){
                floatingwindow.classList.remove('close');
                floatingwindow.classList.add('open');
            }
            else{
                floatingwindow.classList.add('close');
                floatingwindow.classList.remove('open');
            }
        }
    })
})