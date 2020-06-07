<?php

ob_start();
require_once('php/lib-EasyLatex.php');

/**
 * Print the index page
 */
function pog_print_index(){
    pog_print_header(0,'index');

    echo '<form class="form login" id="login-form" action="index.php" method="POST" autocomplete="off">',
                '<h1>Login</h1>',
                pog_html_input('el_login_username','Username'),
                pog_html_input('el_login_password','Password','password'),
                pog_html_button('login','Login','submit'),
                '<p>You don\'t have any account ?</p>',
                pog_html_button('sign_up','Sign up'),
            '</form>',
            '<form class="form signup" id="signup-form" method="POST" action="index.php" autocomplete="off">',
                '<h1>Sign Up</h1>',
                pog_html_tooltip('The username must contains only letters and digits',pog_html_input('el_signup_username','Username')),
                pog_html_input('el_signup_firstname','First name'),
                pog_html_input('el_signup_password','Password','password'),
                pog_html_input('el_signup_lastname','Last name'),
                
                pog_html_input('el_signup_passwordRepeat','Confirm password','password'),
                
                pog_html_checkbox('remember','Remember me'),
                pog_html_button('signup','Sign up','submit'),
                '</form>',
            pog_html_script('js/index.js');
            
            


    pog_print_footer();
}







// MAIN

pog_print_index();