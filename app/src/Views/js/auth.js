document.getElementById('username').addEventListener('input', function (e) {
    if (document.getElementById('username').value.length > 0 && document.getElementById('password').value.length > 0 && document.getElementById('password2').value.length > 0) {
        if(document.getElementById('password').value == document.getElementById('password2').value){
            document.getElementById('submitSignUp').disabled = false;
        }
    }    
});

document.getElementById('password').addEventListener('input', function (e) {
    if (document.getElementById('username').value.length > 0 && document.getElementById('password').value.length > 0 && document.getElementById('password2').value.length > 0) {
        if(document.getElementById('password').value == document.getElementById('password2').value){
            document.getElementById('submitSignUp').disabled = false;
        }
    }    
});

document.getElementById('password2').addEventListener('input', function (e) {
    if (document.getElementById('username').value.length > 0 && document.getElementById('password').value.length > 0 && document.getElementById('password2').value.length > 0) {
        if(document.getElementById('password').value == document.getElementById('password2').value){
            document.getElementById('submitSignUp').disabled = false;
            document.getElementById('password').classList.remove('error');
            document.getElementById('password2').classList.remove('error');
        }else{
            document.getElementById('password').classList.add('error');
            document.getElementById('password2').classList.add('error');
        }
    }    
});




