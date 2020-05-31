<?php

ob_start();

function pog_print_index(){
    echo '<!DOCTYPE html>',
            '<html lang="en">',
                '<head>',
                    '<meta charset="UTF-8">',
                    '<meta name="viewport" content="width=device-width, initial-scale=1.0">',
                    '<title>EasyLatex</title>',
                    '<link rel="stylesheet" href="styles/easylatex.css">',
                '</head>',
                '<body id="index">',
                    '<header>',
                        '<h1>EasyLatex</h1>',
                        '<h2>Make Latex document without code !</h2>',
                    '</header>',
                    '<main>',
                        '<form action="index.php" method="POST">',
                            '<input type="hidden" name="markdown">',
                            '<input type="hidden" name="latex">',
                            '<div class="editor">',
                                '<textarea class="editor-input" name="content" id="" placeholder="Your markdown here">',(isset($_POST['markdown']))?$_POST['markdown']:'','</textarea>',
                            '</div>',
                            '<div class="buttons">
                                <button id="btn-preview">Preview markdown</button>',
                                '<input id="btn-convert" name="btn-convert" type="submit" value="Convert in LaTex">',
                            '</div>',
                            '<div class="viewer">',
                                '<iframe class="viewer-wrapper" src="https://latexonline.cc/compile?url=https://francois.poguet.com/EasyLatex/output.tex">',
                                    
                                '</iframe>',
                            '</div>',
                        '</form>',    
                    '</main>',
                    '<footer>',
                        '<p>EasyLatex - François Poguet &copy;</p>',
                    '</footer>',
                    '<script src="js/converter.js"></script>',
                    '<script src="js/main.js"></script>',
                '</body>',
            '</html>';
}

function pog_parseToLatex(){
    $content = $_POST['latex'];

    $file = fopen("output.tex",'w+');

    $latex_begin = '\documentclass{article} \usepackage[utf8]{inputenc}\title{EasyLatex}\author{François Poguet}\date{Juin 2020}\begin{document}\maketitle\tableofcontents ';

    $latex_end = ' \end{document}';

    fwrite($file,$latex_begin);

    fwrite($file,$content);

    fwrite($file,$latex_end);

    fclose($file);
}



// MAIN
if(isset($_POST['latex'])){
    pog_parseToLatex();
}

pog_print_index();

