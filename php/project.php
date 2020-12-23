<?php

ob_start();
session_start();
require_once('_easylatex.php');

/**
 * Print the project page
 */
function pog_print_project($project){
    $project = pog_db_protect_outputs($project);
    $content = $project['pr_content'];
    $filename = $project['pr_filename'];
    $data = urlencode(urlencode($_GET['data']));
    
    pog_print_header(0,'project',$project['pr_name']);
    echo    '<article class="md-syntax">',
                        '<h2>Markup syntax</h2>',
                        '<span id="md-syntax-exit">&times;</span>',
                        '<section>',
                            '<h3>Heading</h3>',
                            '<table>',
                                '<tr>',
                                    '<td>1<sup>st</sup> level ordered</td>',
                                    '<td># Title</td>',
                                '</tr>',
                                '<tr>',
                                    '<td>1<sup>st</sup> level unordered</td>',
                                    '<td>#* Title</td>',
                                '</tr>',
                                '<tr>',
                                    '<td>2<sup>nd</sup> level ordered</td>',
                                    '<td>## Title</td>',
                                '</tr>',
                                '<tr>',
                                    '<td>2<sup>nd</sup> level unordered</td>',
                                    '<td>##* Title</td>',
                                '</tr>',
                                '<tr>',
                                    '<td>3<sup>rd</sup> level unordered</td>',
                                    '<td>### Title</td>',
                                '</tr>',
                                
                            '</table>',
                        '</section>',
                        '<section>',
                            '<h3>Text</h3>',
                            '<table>',
                                '<tr>',
                                    '<td>Bold</td>',
                                    '<td>[b:text:b]</td>',
                                '</tr>',
                                '<tr>',
                                    '<td>Italic</td>',
                                    '<td>[i:text:i]</td>',
                                '</tr>',
                            '</table>',
                        '</section>',
                        '<section>',
                            '<h3>Layout</h3>',
                            '<table>',
                                '<tr>',
                                    '<td>New line</td>',
                                    '<td>[:nl:]</td>',
                                '</tr>',
                                '<tr>',
                                    '<td>New page</td>',
                                    '<td>[:np:]</td>',
                                '</tr>',
                                '<tr>',
                                    '<td>Paragraph beginning</td>',
                                    '<td>[:par:]</td>',
                                '</tr>',
                            '</table>',
                        '</section>',
                        
                '</article>',
                "<form action='project/${data}/' method='POST'>",
                    '<input type="hidden" name="markup">',
                    '<div class="editor">',
                        '<textarea class="editor-input input" name="content" placeholder="Your markup here">',$project['pr_content'],'</textarea>',
                        '<aside class="editor-tools">',
                            pog_html_tooltip('Italicize the selected text | ctrl+i','<button id="btn-italic" >italic</button>'),
                            pog_html_tooltip('Bold the selected text | ctrl+b','<button id="btn-bold" >bold</button>'),
                            pog_html_tooltip('Insert a new line | ctrl+l','<button id="btn-newline" >New line</button>'),
                            pog_html_tooltip('Insert a new page | ctrl+p','<button id="btn-newpage" >New page</button>'),
                            pog_html_tooltip('Insert a new paragraph | ctrl+§','<button id="btn-newpar" >New paragraph</button>'),
                        '</aside>',
                    '</div>',
                    '<div class="buttons">',
                        pog_html_button('btn-preview','See preview'),
                        pog_html_button('btn-convert','Convert in LaTex','submit'),
                        pog_html_button('btn-open',"<a target='_blank' href='https://latexonline.cc/compile?url=https://francois.poguet.com/EasyLatex/projects/${filename}.tex' >Open file</a>",'button'),
                        pog_html_button('btn-settings',"<a href='settings/${data}/' >Settings</a>"),
                        pog_html_button('btn-syntax','Markup syntax'),
                        pog_html_button('btn-help',"<a target='_blank' href='help/'>Help</a>"),
                    '</div>',
                    '<div class="viewer">',
                        "<iframe class='viewer-wrapper' src='https://latexonline.cc/compile?url=https://francois.poguet.com/EasyLatex/projects/${filename}.tex'>",
                        '</iframe>',
                        '<div class="errors">',
                            '<h2>Markup errors</h2>',
                            '<ul class="errors-list">',
                            '</ul>',
                        '</div>',
                    '</div>',
                '</form>',    
                pog_html_script('js/converter.js'),
                pog_html_script('js/editor-shortcuts.js'),
                pog_html_script('js/project.js'),
                
                
                
                pog_print_footer();
                    
}





// MAIN

pog_isLogged('.');

pog_check_param($_GET,['data']) or pog_session_exit('.');

var_dump($_GET);
$id = pog_decrypt_url($_GET['data'],1)[0];



if(!$id){
    pog_print_project_404();
}

if(isset($_POST['latex'])){
    $project = pog_project_update_content($id);
    pog_project_parse($project);
}else{
    $project = pog_project_fetch($id,false);
}

if(!$project){
    pog_print_project_404();
}


pog_print_project($project);

