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
document.querySelector('body nav div.collapse-btn').addEventListener('click',function(e){
    if(e.currentTarget.classList.contains('active')){
        e.currentTarget.classList.remove('active');
        e.currentTarget.parentNode.nextSibling.nextSibling.classList.remove('show');
    } else {
        e.currentTarget.classList.add('active');
        e.currentTarget.parentNode.nextSibling.nextSibling.classList.add('show');
    }
});