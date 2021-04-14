document.querySelectorAll('div.md-input input,div.md-input textarea').forEach(function (v){
    v.addEventListener('keyup', function (e) {
        if(e.currentTarget.value){
            e.currentTarget.classList.add('active');
        }else{
            e.currentTarget.classList.remove('active');
        }
    });
    if(v.value){
        v.classList.add('active');
    }else{
        v.classList.remove('active');
    }
});