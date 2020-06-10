<?php

ob_start();
session_start();
require_once('php/lib-EasyLatex.php');



/**
 * Print the index page
 */
function pog_print_index($mode = 'login' ,$error = ''){
    pog_print_header(0,'index');
    
    $_SESSION['security_token'] = md5(uniqid(microtime()));
    $token = $_SESSION['security_token'];


    echo '<form class="form login" ',($mode == 'login')?'':'style="display : none;"',' id="login-form" action="index.php" method="POST" autocomplete="off">',
                '<h1>Login</h1>',
                ($error != '' && $mode == 'login') ? pog_html_error($error):'',
                pog_html_input('el_login_username','Username'),
                pog_html_input('el_login_password','Password','','password'),
                pog_html_checkbox('el_login_remember','Remember me',true),
                pog_html_button('el_login','Login','submit'),
                '<p>You don\'t have any account ?</p>',
                pog_html_button('sign_up','Sign up'),
                "<input type='hidden' name='el_login_token' value='${token}'>",
            '</form>',
            '<form class="form signup" ',($mode == 'signup')?'':'style="display : none;"',' id="signup-form" method="POST" action="index.php" autocomplete="off">',
                '<h1>Sign Up</h1>',
                ($error != '' && $mode == 'signup') ? pog_html_error($error):'',
                pog_html_input('el_signup_username','Username',(isset($_POST['el_signup_username']))?$_POST['el_signup_username']:''),
                pog_html_input('el_signup_firstname','First name',(isset($_POST['el_signup_firstname']))?$_POST['el_signup_firstname']:''),
                pog_html_input('el_signup_password','Password','','password'),
                pog_html_input('el_signup_lastname','Last name',(isset($_POST['el_signup_lastname']))?$_POST['el_signup_lastname']:''),
                pog_html_input('el_signup_passwordRepeat','Confirm password','','password'),
                pog_html_checkbox('el_signup_remember','Remember me',(isset($_POST['el_signup']))?(isset($_POST['el_signup_remember'])):true),
                pog_html_button('el_signup','Sign up','submit',true),
                "<input type='hidden' name='el_signup_token' value='${token}'>",
            '</form>',
            pog_html_script('js/index.js'),
            pog_html_script('js/signup.js');
            
            


    pog_print_footer();
}


/**
 * Check if the signup form is correctly submited
 */
function pog_signup_hackGuard(){
    $mandatory = ['el_signup_username','el_signup_firstname','el_signup_lastname','el_signup_password','el_signup_passwordRepeat','el_signup','el_signup_token'];
    $optional = ['el_signup_remember'];

    pog_check_param($_POST,$mandatory,$optional) or pog_session_exit('.');

    

    ($_SESSION['security_token'] == $_POST['el_signup_token']) or pog_session_exit('.');

    // Data are already checked by JavaScript, so if they are not valid here -> Hacking case

    $_POST = array_map('trim',$_POST);

    // First/Last name
    foreach(['el_signup_firstname','el_signup_lastname'] as $input){
        pog_str_containsHTML($_POST[$input]) and pog_session_exit('.');
        (strlen($_POST[$input]) <= 50 && strlen($_POST[$input]) > 0 ) or pog_session_exit('.'); 
    }

    // Username
    preg_match('/^[a-zA-Z0-9]{6,20}$/',$_POST['el_signup_username']) or pog_session_exit('.');

    // Password
    (strlen($_POST['el_signup_password']) > 0) or pog_session_exit('.');
    ($_POST['el_signup_password'] == $_POST['el_signup_passwordRepeat']) or pog_session_exit('.');
}


/**
 * Insert new user in database
 */
function pog_signup_database(){
    $db = pog_db_connecter();

    $_POST = pog_db_protect_inputs($db,$_POST);

    extract($_POST);

    $query = "SELECT us_username
                FROM el_user
                WHERE us_username = '${el_signup_username}'";

    if(pog_db_execute($db,$query)){
        mysqli_close($db);
        return 1;
    }


    $pass = password_hash($_POST['el_signup_password'],PASSWORD_DEFAULT);

    $date = pog_getDate();

    $query = "INSERT INTO el_user SET
                us_username = '${el_signup_username}',
                us_first_name = '${el_signup_firstname}',
                us_last_name = '${el_signup_lastname}',
                us_password = '${pass}',
                us_signUpDate = '${date}'";

    pog_db_execute($db,$query,false,true);

    mysqli_close($db);

    return 0;
}

/**
 * Check if the login form is correctly submited
 */
function pog_login_hackGuard(){
    $mandatory = ['el_login_username','el_login_password','el_login','el_login_token'];
    $optional = ['el_login_remember'];

    
    pog_check_param($_POST,$mandatory,$optional) or pog_session_exit('.');

    
    ($_SESSION['security_token'] == $_POST['el_login_token']) or pog_session_exit('.');
}

/**
 * Connect the user
 * @return int 0 on success, else 1
 */
function pog_login_connection(){
    $db = pog_db_connecter();

    $_POST = pog_db_protect_inputs($db,$_POST);
    extract($_POST);

    $query = "SELECT us_password, us_username
                FROM el_user
                WHERE us_username = '${el_login_username}'";

    $res = pog_db_execute($db,$query);

    mysqli_close($db);

    if($res == null){
        return 1;
    }

    if(!password_verify($el_login_password,$res[0]['us_password'])){
        return 1;
    }

    $_SESSION['username'] = $res[0]['us_username'];

    return 0;
}

// MAIN

if(pog_isLogged()){
    header('Location: php/dashboard.php');
    exit(0);
}

if(isset($_POST['el_signup'])){
    pog_signup_hackGuard();
    if(pog_signup_database() == 1){ 
        pog_print_index('signup','This username is already used');
    }else{
        header('Location: php/dashboard.php');
    }
    exit(0);
}

if(isset($_POST['el_login'])){
    pog_login_hackGuard();
    if(pog_login_connection() == 1){
        pog_print_index('login','Invalid username or password');
    }else{
        header('Location: php/dashboard.php');
    }
    
    exit(0);
}

pog_print_index('login');