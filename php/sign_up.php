<?php

ob_start();
session_start();
require_once('lib-EasyLatex.php');


function pog_signup_hackGuard(){
    $mandatory = ['el_signup_username','el_signup_firstname','el_signup_lastname','el_signup_password','el_signup_passwordRepeat','el_signup','el_signup_token'];
    $optional = ['el_signup_remember'];

    pog_check_param($_POST,$mandatory,$optional) or pog_session_exit('../');

    ($_SESSION['security_token'] == $_POST['el_signup_token']) or pog_session_exit('../');

    pog_signup_checkInputErrors();
}

function pog_signup_checkInputErrors(){
    $_POST = array_map('trim',$_POST);

    // First/Last name
    foreach(['el_signup_firstname','el_signup_lastname'] as $input){
        pog_str_containsHTML($_POST[$input]) and pog_session_exit('../');
        (strlen($_POST[$input]) <= 50 && strlen($_POST[$input]) > 0 ) or pog_session_exit('../'); 
    }

    // Username
    preg_match('/^[a-zA-Z0-9]{6,20}$/',$_POST['el_signup_username']) or pog_session_exit('../');

    // Password
    (strlen($_POST['el_signup_password']) > 0) or pog_session_exit('../');
    ($_POST['el_signup_password'] == $_POST['el_signup_passwordRepeat']) or pog_session_exit('../');    
}

function pog_signup_database(){
    $db = pog_db_connecter();

    $_POST = pog_db_protect_inputs($db,$_POST);

    extract($_POST);

    $pass = password_hash($_POST['el_signup_password'],PASSWORD_DEFAULT);

    $query = "INSERT INTO el_user SET
                us_username = '${el_signup_username}',
                us_first_name = '${el_signup_firstname}',
                us_last_name = '${el_signup_lastname}',
                us_password = '${pass}'";

    pog_db_execute($db,$query,false,true);

    mysqli_close($db);
}



pog_signup_hackGuard();

pog_signup_database();

