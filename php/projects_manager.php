<?php

ob_start();
session_start();
require_once('lib-EasyLatex.php');


/**
 * Create a new project
 */
function pog_new_project(){

    pog_check_param($_POST,['el_newproject','el_newproject_name']) or pog_session_exit('../');

    if(!preg_match('/^[^<>]{4,30}$/',$_POST['el_newproject_name'])){
        pog_session_exit('../');
    }

    $db = pog_db_connecter();

    $_POST = pog_db_protect_inputs($db,$_POST);
    extract($_POST);

    $author = $_SESSION['username'];
    $name = $_POST['el_newproject_name'];
    $date = pog_getDate();

    $filename = md5(uniqid(rand(),true));

    $query = "INSERT INTO el_project SET
                pr_author = '${author}',
                pr_content = '\# Title',
                pr_creat_date = '${date}',
                pr_filename = '${filename}',
                pr_name = '${name}'";
    
    pog_db_execute($db,$query,false,true);

    mysqli_close($db);

    header('Location: dashboard.php');
    exit(0);

}




// MAIN

if(isset($_POST['el_newproject'])){
    pog_new_project();
}