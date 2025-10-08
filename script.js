document.getElementById("loginButton").addEventListener('click', function() {
    const form = document.getElementById('loginForm');
    if(form.style.display === 'none' || !form.style.display){
        form.style.display = 'block';
        const firstInput = form.querySelector('input');
        if(firstInput) firstInput.focus();
    }
    else{
        form.style.display = 'none';
    }
});
document.getElementById("signUpButton").addEventListener('click', function() {
    const form = document.getElementById('signUpForm');
    if(form.style.display === 'none' || !form.style.display){
        form.style.display = 'block';
        const firstInput = form.querySelector('input');
        if(firstInput) firstInput.focus();
    }
    else{
        form.style.display = 'none';
    }
});