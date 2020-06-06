<?php

ob_start();
require_once('lib-EasyLatex.php');

/**
 * Print the project page
 */
function pog_print_project(){
    pog_print_header(1,'project');

    echo    '<article class="md-syntax">',
                        '<h2>Markdown syntax</h2>',
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
                            '<h3>layout</h3>',
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
                '<form action="index.php" method="POST">',
                    '<input type="hidden" name="markdown">',
                    '<input type="hidden" name="latex">',
                    '<div class="editor">',
                        '<textarea class="editor-input input" name="content" placeholder="Your markdown here">',(isset($_POST['markdown']))?$_POST['markdown']:'','</textarea>',
                        '<aside class="editor-tools">',
                            '<span class="tooltip"><span class="tooltip-tip">Italicize the selected text | ctrl+i</span><button id="btn-italic">italic</button></span>',
                            '<span class="tooltip"><span class="tooltip-tip">Bold the selected text | ctrl+b</span><button id="btn-bold" >bold</button></span>',
                            '<span class="tooltip"><span class="tooltip-tip">Insert a new line | ctrl+l</span><button id="btn-newline" >New line</button></span>',
                            '<span class="tooltip"><span class="tooltip-tip">Insert a new page | ctrl+p</span><button id="btn-newpage" >New page</button></span>',
                            '<span class="tooltip"><span class="tooltip-tip">Insert a new paragraph | ctrl+§</span><button id="btn-newpar" >New paragraph</button></span>',
                        '</aside>',
                    '</div>',
                    '<div class="buttons">',
                        '<button class="btn" id="btn-preview">See preview</button>',
                        '<input class="btn" id="btn-convert" name="btn-convert" type="submit" value="Convert in LaTex">',
                        '<button class="btn" id="btn-syntax">Markdown syntax</button>',
                        '<button class="btn" id="btn-help">Help</button>',
                    '</div>',
                    '<div class="viewer">',
                        '<iframe class="viewer-wrapper" src="https://latexonline.cc/compile?url=https://francois.poguet.com/EasyLatex/output.tex">',
                        '</iframe>',
                        '<div class="errors">',
                            '<h2>Markdown errors</h2>',
                            '<ul class="errors-list">',
                            '</ul>',
                        '</div>',
                    '</div>',
                '</form>',    
            '<script src="../js/converter.js"></script>',
            '<script src="../js/editor-shortcuts.js"></script>',
            '<script src="../js/main.js"></script>';
    pog_print_footer();
                    
}

/**
 * Write the latex code on a file
 */
function pog_parseToLatex(){
    $file = fopen("output.tex",'w+');

    $latex_begin = '\documentclass{report}\usepackage[utf8]{inputenc}\usepackage[T1]{fontenc}\usepackage[english]{babel}\setlength{\parindent}{0cm}\renewcommand{\thesection}{\arabic{section}}\title{EasyLatex}\author{François Poguet}\date{Juin 2020}\begin{document}\maketitle\tableofcontents\newpage ';
    $latex_end = ' \end{document}';

    $content = $_POST['latex'];
    fwrite($file,$latex_begin);
    fwrite($file,$content);
    fwrite($file,$latex_end);

    fclose($file);
}



// MAIN
if(isset($_POST['latex'])){
    pog_parseToLatex();
}

pog_print_project();

