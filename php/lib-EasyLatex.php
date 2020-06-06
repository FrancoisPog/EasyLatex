<?php

/*################################################################
 *
 *              EasyLatex functions librarie          
 *
 ###############################################################*/


require_once('private_data.php');
require_once('lib-database.php');


/**
 * Print the page header
 * @param int $deepness The page deepness from root
 */
function pog_print_header($deepness,$page_name){
    $path='';
    for($i = 0 ; $i < $deepness ; $i++){
        $path.='../';
    }
    echo '<!DOCTYPE html>',
            '<html lang="en">',
                '<head>',
                    '<meta charset="UTF-8">',
                    '<meta name="viewport" content="width=device-width, initial-scale=1.0">',
                    '<title>EasyLatex</title>',
                    "<link rel='stylesheet' href='${path}styles/easylatex.css'>",
                    '<link href="https://fonts.googleapis.com/css2?family=Chelsea+Market&display=swap" rel="stylesheet"> ',
                '</head>',
                "<body id='${page_name}'>",
                    '<header>',
                        '<h1>EasyLatex</h1>',
                        '<h2>Make LaTex document without code !</h2>',
                    '</header>',
                    '<main>';
}

/**
 * Print the page footer
 */
function pog_print_footer(){
    echo    '</main>',
            '<footer>',
                '<p>EasyLatex - François Poguet &copy;</p>',
            '</footer>',
        '</body>',
    '</html>';
}