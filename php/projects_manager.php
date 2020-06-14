<?php

ob_start();
session_start();
require_once('lib-EasyLatex.php');


/**
 * Create a new project
 */
function pog_new_project(){

    // Check input
    pog_check_param($_POST,['el_newproject','el_newproject_name']) or pog_session_exit('../');

    if(!preg_match('/^[^<>]{1,100}$/',$_POST['el_newproject_name'])){
        pog_session_exit('../');
    }

    // Get user data
    $db = pog_db_connecter();
    $author = pog_db_protect_inputs($db,$_SESSION['username']);

    $query = "SELECT us_first_name, us_last_name
                FROM el_user
                WHERE us_username = '${author}'";

    $authorData = pog_db_execute($db,$query,false);

    // TODO test if res = null

    var_dump($authorData);

    $author_str = pog_db_protect_inputs($db,$authorData[0]['us_first_name'].' '.$authorData[0]['us_last_name']);


    // Insert project in database
    $_POST = pog_db_protect_inputs($db,$_POST);
    extract($_POST);

    
    $name = $_POST['el_newproject_name'];
    $date = pog_getDate();

    $filename = md5(uniqid(rand(),true));

    $query = "INSERT INTO el_project SET
                pr_author = '${author}',
                pr_content = '\# Title',
                pr_creat_date = '${date}',
                pr_filename = '${filename}',
                pr_name = '${name}',
                pr_cover_title = '${name}',
                pr_cover_author = '${author_str}',
                pr_cover_date = 0";
    
    pog_db_execute($db,$query,false,true);

    mysqli_close($db);

    header('Location: dashboard.php');
    exit(0);

}




// MAIN

if(isset($_POST['el_newproject'])){
    pog_new_project();
}