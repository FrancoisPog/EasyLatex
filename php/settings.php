<?php

ob_start();
session_start();
require_once('_easylatex.php');

/**
 * Print the settings form
 */
function pog_print_settings($project){
    pog_print_header(0,'settings',$project['pr_name']);

    $data = urlencode(urlencode($_GET['data']));
   
    echo    pog_html_button('back_to_project',"<a href='project/${data}/'>Back to project</a>"),
            "<form class='settings form' id='settings-form' action='settings/${data}/' method='POST'>",
                '<section>',
                    '<h2>Project name</h2>',
                    pog_html_input('el_settings_name','Name',$project['pr_name']),
                '</section>',
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
                '<section class="settings-delete">',
                    '<h2>Delete the project</h2>',
                    pog_print_popUp('Delete','Are you sure ? ','dd','submit','Delete','el_settings_delete'),
                '</section>',
            '</form>',
            pog_html_script('js/forms.js'),
            pog_html_script('js/settings.js');



    pog_print_footer();
}



/**
 * Avoid hacking case
 */
function pog_settings_hackGuard(){

    $mandatory = ['el_settings_title','el_settings_author','el_settings_language','el_settings_type','el_settings_contents','el_settings','el_settings_name'];
    $optional = ['el_settings_date_auto','el_settings_date'];

    pog_check_param($_POST,$mandatory,$optional) or pog_session_exit('.');

    $_POST = array_map('trim',$_POST);

    if(!isset($_POST['el_settings_date_auto']) && !isset($_POST['el_settings_date'])){
        pog_session_exit('../');
    }

    preg_match('/^[^<>]{1,30}$/',$_POST['el_settings_name']) or pog_session_exit('.');

    foreach(['el_settings_title','el_settings_author','el_settings_date'] as $key){
        if($key == 'el_settings_date' && isset($_POST['el_settings_date_auto'])){
            continue;
        }
        preg_match('/^[^><]{0,100}$/',$_POST[$key]) or pog_session_exit('.');
    }

    $tests = [
        'el_settings_language' => '/^(en|fr)$/',
        'el_settings_type' => '/^(article|report)$/',
        'el_settings_contents' => '/^[10]$/'
    ];

    foreach($tests as $key => $regex){
        preg_match($regex,$_POST[$key]) or pog_session_exit('.');
    }

}





// MAIN

pog_isLogged('.');

$id = pog_decrypt_url($_GET['data'],1)[0];

if(!$id){
    pog_print_project_404();
}

if(isset($_POST['el_settings'])){
    pog_settings_hackGuard($id);
    pog_project_update_settings($id);
}


$project = pog_project_fetch($id);

if(!$project){
    pog_print_project_404();
}

pog_print_settings($project);


