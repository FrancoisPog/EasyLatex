<?php

ob_start();
session_start();
require_once('php/lib-EasyLatex.php');

$_SESSION['security_token'] = md5(uniqid(microtime()));


/**
 * Print the index page
 */
function pog_print_index(){
    pog_print_header(0,'index');
    $token = $_SESSION['security_token'];
    echo '<form class="form login" id="login-form" action="php/login.php" method="POST" autocomplete="off">',
                '<h1>Login</h1>',
                pog_html_input('el_login_username','Username'),
                pog_html_input('el_login_password','Password','password'),
                pog_html_checkbox('el_login_remember','Remember me',true),
                pog_html_button('login','Login','submit'),
                '<p>You don\'t have any account ?</p>',
                pog_html_button('sign_up','Sign up'),
            '</form>',
            '<form class="form signup" id="signup-form" method="POST" action="php/sign_up.php" autocomplete="off">',
                '<h1>Sign Up</h1>',
                pog_html_input('el_signup_username','Username'),
                pog_html_input('el_signup_firstname','First name'),
                pog_html_input('el_signup_password','Password','password'),
                pog_html_input('el_signup_lastname','Last name'),
                
                pog_html_input('el_signup_passwordRepeat','Confirm password','password'),
                
                pog_html_checkbox('el_signup_remember','Remember me',true),
                pog_html_button('el_signup','Sign up','submit',true),
                "<input type='hidden' name='el_signup_token' value='${token}'>",
            '</form>',
            pog_html_script('js/index.js'),
            pog_html_script('js/signup.js');
            
            


    pog_print_footer();
}







// MAIN

pog_print_index();