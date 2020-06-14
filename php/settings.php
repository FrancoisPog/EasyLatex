<?php

ob_start();
session_start();
require_once('lib-EasyLatex.php');


function pog_print_settings(){
    pog_print_header(1,'settings','Title');

    $data = urlencode($_GET['data']);

    echo    "<form class='settings' action='settings.php?data=${data}' method='POST'>",
                '<section class="settings-firstpage">',
                    '<h2>First page</h2>',
                    pog_html_input('el_settings_title','Title'),
                    pog_html_input('el_settings_author','Author'),
                    pog_html_checkbox('el_settings_date_auto','Use the compilation date',true),
                    pog_html_input('el_settings_date','Date'),
                '</section>',
                '<section class="settings-advanced">',
                    '<h2>Advanced settings</h2>',
                    '<table>',
                        '<tr>',
                            '<td>Language</td>',
                            '<td>',
                                pog_html_radio('el_settings_language','en','English'),
                                pog_html_radio('el_settings_language','fr','French'),
                            '</td>',
                        '</tr>',
                        '<tr>',
                            '<td>Document type</td>',
                            '<td>',
                                pog_html_radio('el_settings_type','ar','Article'),
                                pog_html_radio('el_settings_type','re','Report'),
                            '</td>',
                        '</tr>',
                        '<tr>',
                            '<td>Contents table</td>',
                            '<td>',
                                pog_html_radio('el_settings_contents','yes','Yes'),
                                pog_html_radio('el_settings_contents','no','No'),
                            '</td>',
                        '</tr>',
                    '</table>',
                '</section>',
                pog_html_button('el_settings','Done','submit'),
            '</form>',
            pog_html_script('../js/settings.js');



    pog_print_footer();
}





// MAIN

pog_isLogged('../');

pog_print_settings();


