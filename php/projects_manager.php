<?php

ob_start();
session_start();
require_once('lib-EasyLatex.php');



function pog_new_project(){

    $db = pog_db_connecter();

    $_POST = pog_db_protect_inputs($db,$_POST);
    extract($_POST);

    $author = $_SESSION['username'];

    $date = pog_getDate();

    $filename = md5(uniqid(rand(),true));

    $query = "INSERT INTO el_project SET
                pr_author = '${author}',
                pr_content = '\# Title',
                pr_creat_date = '${date}',
                pr_filename = '${filename}'";
    
    pog_db_execute($db,$query,false,true);



    

    mysqli_close($db);


}




// MAIN

if(isset($_POST['el_newproject'])){
    pog_new_project();
}