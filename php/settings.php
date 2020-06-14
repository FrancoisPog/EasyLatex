<?php

ob_start();
session_start();
require_once('lib-EasyLatex.php');

/**
 * Print the settings form
 */
function pog_print_settings($project){
    pog_print_header(1,'settings','Title');

    $data = urlencode($_GET['data']);

    echo    "<form class='settings' action='settings.php?data=${data}' method='POST'>",
                '<section class="settings-firstpage">',
                    '<h2>First page</h2>',
                    pog_html_input('el_settings_title','Title',$project['pr_cover_title']),
                    pog_html_input('el_settings_author','Author',$project['pr_cover_author']),
                    pog_html_checkbox('el_settings_date_auto','Use the compilation date',$project['pr_cover_date'] == '0'),
                    pog_html_input('el_settings_date','Date',($project['pr_cover_date'] == '0')?'':$project['pr_cover_date']),
                '</section>',
                '<section class="settings-advanced">',
                    '<h2>Advanced settings</h2>',
                    '<table>',
                        '<tr>',
                            '<td>Language</td>',
                            '<td>',
                                pog_html_radio('el_settings_language','en','English',true),
                                pog_html_radio('el_settings_language','fr','French',$project['pr_lang'] == 'fr'),
                            '</td>',
                        '</tr>',
                        '<tr>',
                            '<td>Document type</td>',
                            '<td>',
                                pog_html_radio('el_settings_type','ar','Article',true),
                                pog_html_radio('el_settings_type','re','Report',$project['pr_type'] == 'report'),
                            '</td>',
                        '</tr>',
                        '<tr>',
                            '<td>Contents table</td>',
                            '<td>',
                                pog_html_radio('el_settings_contents','yes','Yes',true),
                                pog_html_radio('el_settings_contents','no','No',$project['pr_table_content'] == 0),
                            '</td>',
                        '</tr>',
                    '</table>',
                '</section>',
                pog_html_button('el_settings','Done','submit'),
            '</form>',
            pog_html_script('../js/settings.js');



    pog_print_footer();
}

/**
 * fetch project data in database
 * @param int $id The project id
 * @return Array The user data
 */
function pog_fetch_project($id){
    $db = pog_db_connecter();
    $author = $_SESSION['username'];
    $query = "SELECT *
                FROM el_project
                WHERE pr_id = ${id}
                AND pr_author = '${author}'";

    $project = pog_db_execute($db,$query);

    mysqli_close($db);

    return $project[0];
}


function pog_settings_hackGuard(){

    $mandatory = ['el_settings_title','el_settings_author','el_settings_language','el_settings_type','el_settings_contents','el_settings'];
    $optional = ['el_settings_date_auto','el_settings_date'];

    pog_check_param($_POST,$mandatory,$optional) or pog_session_exit('../');

    $_POST = array_map('trim',$_POST);

    //var_dump($_POST);

    preg_match('/^[^><\\\]{4,100}$/',$_POST['el_settings_title']) or pog_session_exit('../');


    


}



// MAIN

pog_isLogged('../');

$id = pog_decrypt_url($_GET['data'],1)[0];
// TODO make error page 

if(isset($_POST['el_settings'])){
    pog_settings_hackGuard();
}


$project = pog_fetch_project($id);

pog_print_settings($project);


