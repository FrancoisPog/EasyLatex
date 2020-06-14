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
                                pog_html_radio('el_settings_type','article','Article',true),
                                pog_html_radio('el_settings_type','report','Report',$project['pr_type'] == 'report'),
                            '</td>',
                        '</tr>',
                        '<tr>',
                            '<td>Contents table</td>',
                            '<td>',
                                pog_html_radio('el_settings_contents','1','Yes',true),
                                pog_html_radio('el_settings_contents','0','No',$project['pr_table_content'] == 0),
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
 * Fetch project data in database
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

/**
 * Avoid hacking case
 */
function pog_settings_hackGuard(){

    $mandatory = ['el_settings_title','el_settings_author','el_settings_language','el_settings_type','el_settings_contents','el_settings'];
    $optional = ['el_settings_date_auto','el_settings_date'];

    pog_check_param($_POST,$mandatory,$optional) or pog_session_exit('../');

    $_POST = array_map('trim',$_POST);

    if(!isset($_POST['el_settings_date_auto']) && !isset($_POST['el_settings_date'])){
        //var_dump($_POST);
        pog_session_exit('../');
    }

    foreach(['el_settings_title','el_settings_author','el_settings_date'] as $key){
        if($key == 'el_settings_date' && isset($_POST['el_settings_date_auto'])){
            continue;
        }
        preg_match('/^[^><\\\]{0,100}$/',$_POST[$key]) or var_dump($_POST);//pog_session_exit('.a./');
    }

    $tests = [
        'el_settings_language' => '/^(en|fr)$/',
        'el_settings_type' => '/^(article|report)$/',
        'el_settings_contents' => '/^[10]$/'
    ];

    foreach($tests as $key => $regex){
        preg_match($regex,$_POST[$key]) or var_dump($key);// pog_session_exit('.b./');
    }

}

/**
 * Update the project data in database
 * @param int $id The project id
 */
function pog_updateProject($id){
    $db = pog_db_connecter();

    $_POST = pog_db_protect_inputs($db,$_POST);
    extract($_POST);

    $date = (isset($el_settings_date_auto)) ? '0' : $el_settings_date;

    $query = "UPDATE el_project SET
                pr_cover_title = '${el_settings_title}',
                pr_cover_author = '${el_settings_author}',
                pr_cover_date = '${date}',
                pr_lang = '${el_settings_language}',
                pr_table_content = ${el_settings_contents},
                pr_type = '${el_settings_type}'
                WHERE pr_id = ${id} ";

    pog_db_execute($db,$query,false,true);

    mysqli_close($db);
}



// MAIN

pog_isLogged('../');

$id = pog_decrypt_url($_GET['data'],1)[0];
// TODO make error page 

if(isset($_POST['el_settings'])){
    pog_settings_hackGuard($id);
    pog_updateProject($id);
}


$project = pog_fetch_project($id);

pog_print_settings($project);


