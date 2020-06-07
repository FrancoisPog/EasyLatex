let btn_signup = document.getElementById('sign_up');
let btn_login = document.getElementById('login')

let form_signup = document.getElementById('signup-form');
let form_login = document.getElementById('login-form');



btn_signup.onclick = (e) => {
    form_login.style.display = 'none';
    form_signup.style.display = 'flex';
};
