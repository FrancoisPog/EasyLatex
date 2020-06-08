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
                '<p>François Poguet - ',date('Y'),' &copy;</p>',
            '</footer>',
        '</body>',
    '</html>';
}

/**
 * Generate html for text input
 * @param String $name The input's name/id
 * @param String $label The input label 
 */
function pog_html_input($name, $label,$type = 'text'){
    return "<div class='form-input-text'><input type='${type}' name='${name}' id='${name}' placeholder=' ' autocomplete='off' /><label for='${name}'>${label}</label></div>";
}


/**
 * Print a tooltip
 * @param String $label The tooltip content
 * @param String $inner_element The inner element of tooltip
 */
function pog_html_tooltip($label,$inner_element){
    return "<div class='tooltip'><span class='tooltip-tip'>${label}</span>${inner_element}</div>";
}

function pog_html_button($id,$label,$type = 'button'){
    return "<button class='btn' name='${id}' type='${type}' id='${id}'>${label}</button>";
}

function pog_html_script($path){
    return "<script src='${path}'></script>";
}

function pog_html_checkbox($id,$label,$isChecked = false){
    $isChecked = ($isChecked)?'checked':'';
    return "<input class='inp-cbx' id='${id}' name='${id}' ${isChecked} type='checkbox' style='display: none'/><label class='cbx' for='${id}'><span><svg width='12px' height='10px' viewbox='0 0 12 10'><polyline points='1.5 6 4.5 9 10.5 1'></polyline></svg></span><span>${label}</span></label>";
}

/**
 * Checking parameters validity
 * @param Array array               The array containing the parameters
 * @param Array $mandatory_keys     The array containing the mandatory keys
 * @param Array $optional_keys      The array containing the optional keys
 * @return boolean                  True if the parameters are correct, else false
 */
function pog_check_param($array, $mandatory_keys, $optional_keys = array()){
    $array = array_keys($array);
    if (count(array_diff($mandatory_keys, $array)) > 0){
        return false;
    }
    if (count(array_diff($array, array_merge($mandatory_keys,$optional_keys))) > 0){
        return false;
    }
    
    return true;
}

/**
 * Correctly end a session and redirect to the given page.
 * @param String $page  The page for the redirection
 */
function pog_session_exit($page){
    session_destroy();
    session_unset();

    // deleting session cookie
    $cookie_session_data = session_get_cookie_params();
    setcookie(session_name(), 
                '', 
                time() - 86400,
                $cookie_session_data['path'], 
                $cookie_session_data['domain'],
                $cookie_session_data['secure'],
                $cookie_session_data['httponly']
            );

    setcookie('pseudo','',time()-3600*24,'/');
    setcookie('status','',time()-3600*24,'/');
    setcookie('key','',time()-3600*24,'/');
        
    header("Location: $page");
    exit(0);

}

/**
 * Check if a string contains html tags
 * @param String $str   The string to test
 * @return boolean
 */
function pog_str_containsHTML($str){
    return ($str != str_replace(['>','<'],'',$str));
}