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
 * @param string $page_name The name of the page
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
                    //'<script src="https://kit.fontawesome.com/a076d05399.js"></script>',
                '</head>',
                "<body id='${page_name}'>",
                    '<header>',
                        '<h1>EasyLatex</h1>',
                        '<h2>Make LaTex document without code !</h2>',
                        pog_html_nav(),
                    '</header>',
                    '<main>';
                    
    pog_print_noscript();
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


function pog_print_error($content){
    echo '<section class="error_section">',
            '<h2>Something wrong...</h2>',
            "<p>${content}</p>",
        '</section>';
}


/**
 * Generate the html code for the navigation menu
 */
function pog_html_nav(){
    if(!pog_isLogged()){
        return '';
    }

    $username = $_SESSION['username'];

    return "<nav><a id='dashboard_link' href='dashboard.php'>${username}</a><a href='exit.php'><img alt='exit-icon' title='Exit' src='../styles/icons/exit.svg'></a></nav>";
}

/**
 * Print the noscript element
 */
function pog_print_noscript(){
    echo '<noscript><div class="noscript" ><h1>JavaScript is disabled</h1><p>This website need JavaScript to work, please activate it to continue.</p></div></noscript>';
}


/**
 * Generate html for text input
 * @param String $name The input's name/id
 * @param String $label The input label 
 * @param String $value The input default value
 * @param String $type The input type (text/password)
 */
function pog_html_input($name, $label,$value = '',$type = 'text'){
    $value = ($value!='')?"value='${value}'":'';
    return "<div class='form-input-text'><input type='${type}' name='${name}' ${value} id='${name}' placeholder=' ' autocomplete='off' /><label for='${name}'>${label}</label></div>";
}


/**
 * Generate html for a tooltip
 * @param String $label The tooltip content
 * @param String $inner_element The inner element of tooltip
 */
function pog_html_tooltip($label,$inner_element){
    return "<div class='tooltip'><span class='tooltip-tip'>${label}</span>${inner_element}</div>";
}

/**
 * Generate html for button
 * @param string $id The button id
 * @param string $label The button label
 * @param string $type The button type
 * @param boolean $disabled True for a disabled button
 */
function pog_html_button($id,$label,$type = 'button',$disabled = false){
    $disabled = ($disabled)?'disabled':'';
    return "<button class='btn' ${disabled} name='${id}' type='${type}' id='${id}'>${label}</button>";
}

/**
 * Generate html for script
 * @param string $path The script path
 */
function pog_html_script($path){
    return "<script src='${path}'></script>";
}

/**
 * Generate html for checkbox
 * @param string $id The checkbox id
 * @param string $label The checkbox label
 * @param string $isChecked True for a checked checkbox
 */
function pog_html_checkbox($id,$label,$isChecked = false){
    $isChecked = ($isChecked)?'checked':'';
    return "<input class='inp-cbx' id='${id}' name='${id}' ${isChecked} type='checkbox' style='display: none'/><label class='cbx' for='${id}'><span><svg width='12px' height='10px' viewbox='0 0 12 10'><polyline points='1.5 6 4.5 9 10.5 1'></polyline></svg></span><span>${label}</span></label>";
}

/**
 * Generate html for an error
 * @param string $content The error message
 */
function pog_html_error($content){
    return "<div class='error'><p>${content}</p></div>";
}

/**
 * Test if the user is logged
 * @param string $page_to_redirect An optional page to redirect the user if he's not logged
 */
function pog_isLogged($page_to_redirect = null){
    if(isset($_SESSION['username'])){
        return true;
    }

    if(!$page_to_redirect){
        return false;
    }

    header("Location: ${page_to_redirect}");
    exit(0);
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


/**
 * Get the current date in 'YYYYMMDDHHmm' format
 */
function pog_getDate(){
    date_default_timezone_set('Europe/Paris');
    return date('YmdHi');
}



/**
 * Crypt and sign url.
 * @param Array $data       All data to crypt in an array
 * @return String|false     The encrypted and signed url is success, false if failure
 */
function pog_encrypt_url($data){
    if(!defined('ENCRYPTION_KEY')){
        throw new Exception('[pog_encrypt_url] : The constant \'ENCRYPTION_KEY\' must be defined');
    }
    $data = implode('§',$data);

    $method = 'aes-128-gcm';
    $initVectorLen = openssl_cipher_iv_length($method);
    $initVector = openssl_random_pseudo_bytes($initVectorLen);
    $data = openssl_encrypt($data,$method,base64_decode(ENCRYPTION_KEY),OPENSSL_RAW_DATA,$initVector,$tag);
    if($data == false){
        return false;
    }
    $url = $initVector.$tag.$data;
    $url = base64_encode($url);
    return urlencode($url);

}

/**
 * Decrypts and authenticates the url. 
 * @param String $url   The url to decrypt
 * @param int $field    The number of field expected
 * @return Array|false  Decrypted and authenticated data if success, false if failure
 */
function pog_decrypt_url($url,$field){
    if(!defined('ENCRYPTION_KEY')){
        throw new Exception('[pog_decrypt_url] : The constant \'ENCRYPTION_KEY\' must be defined');
    }
    if($url == ""){
        return false;
    }
    $method = 'aes-128-gcm';
    $url = base64_decode($url);
    $initVectorLen = openssl_cipher_iv_length($method);
    $initVector = substr($url,0,$initVectorLen);
    $tagLen = 16;
    $tag = substr($url,$initVectorLen,$tagLen);
    $data = substr($url,$tagLen+$initVectorLen);
    
    $data = openssl_decrypt($data,$method,base64_decode(ENCRYPTION_KEY),OPENSSL_RAW_DATA,$initVector,$tag);
    
    if(!$data){
        return false;
    }
    $data = explode('§',$data);
    return (count($data) == $field)?$data:false ;

}