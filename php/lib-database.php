<?php 

/*################################################################
 *
 *              Database functions librarie          
 *
 ###############################################################*/

/** 
 *  Opening the connection to the database
 *  In case of connection error the script is stopped.
 *
 *  @return objet 	database connecter
 */
function pog_db_connecter() {
    $db = mysqli_connect(BD_SERVER, BD_USER, BD_PASS, BD_NAME);
    if ($db !== FALSE) {
        mysqli_set_charset($db, 'utf8') 
        or pog_db_error_exit('<h4>Charset loading error</h4>');
        return $db;    
    }
    if(!defined(DB_DEBUG)){
        ob_clean();
        http_response_code(500);
        pog_print_error_page('500 : Internal Server Error','<p>An error has occurred on our side, we are sorry ...</p><p> Please retry later.</p>');
    }

    // Connection error
    $msg = '<h4>Database connection error</h4>'
            .'<div style="margin: 20px auto; width: 350px;">'
            .'BD_SERVER : '. BD_SERVER
            .'<br>BD_USER : '. BD_USER
            .'<br>BD_PASS : '. BD_PASS
            .'<br>BD_NAME : '. BD_NAME
            .'<p>Erreur MySQL numéro : '.mysqli_connect_errno()
            .'<br>'.htmlentities(mysqli_connect_error(), ENT_QUOTES, 'ISO-8859-1')  
            .'</div>';
    pog_db_error_exit($msg);
}

/**
 * Script stop if database error
 *
 * Display an error message, then stop the script
 * Function called when a 'database' error occurs:
 * - during the connection phase to the MySQL server
 * - or indirectly when the sending of a request fails
 *
 * @param string $msg Error message to display
 */
function pog_db_error_exit($msg) {
    ob_end_clean();
    echo    '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8">',
            '<title>Erreur base de données</title>',
            '<style>',
                'table{border-collapse: collapse;}td{border: 1px solid black;padding: 4px 10px;}',
            '</style>',
            '</head><body>',
            $msg,
            '</body></html>';
    exit(1);
}

/**
 * Management of a query error in the database.
 *
 * Must be called when a call to mysqli_query () fails
 * Call the pog_db_errorExit () function which displays an error message then ends the script
 *
 * @param object $db Database connector
 * @param string $sql SQL request causing the error
 */
function pog_db_error($db, $sql) {
    
    if(!defined(DB_DEBUG)){
        ob_clean();
        http_response_code(500);
        pog_print_error_page('500 : Internal Server Error','<p>An error has occurred on our side, we are sorry ...</p><p> Please retry later.</p>');
        exit(0);
    }
    $errNum = mysqli_errno($db);
    $errTxt = mysqli_error($db);

    // Collecte des informations facilitant le debugage
    $msg =  '<h4>Erreur de requête</h4>'
            ."<pre><b>Erreur mysql :</b> $errNum"
            ."<br> $errTxt"
            ."<br><br><b>Requête :</b><br> $sql"
            .'<br><br><b>Pile des appels de fonction</b></pre>';

    // Récupération de la pile des appels de fonction
    $msg .= '<table>'
            .'<tr><td>Fonction</td><td>Appelée ligne</td>'
            .'<td>Fichier</td></tr>';

    $appels = debug_backtrace();
    for ($i = 0, $iMax = count($appels); $i < $iMax; $i++) {
        $msg .= '<tr style="text-align: center;"><td>'
                .$appels[$i]['function'].'</td><td>'
                .$appels[$i]['line'].'</td><td>'
                .$appels[$i]['file'].'</td></tr>';
    }

    $msg .= '</table>';

    pog_db_error_exit($msg);	// ==> ARRET DU SCRIPT
}

/**
 * Execution of database query. 
 * @param Object $db            The databasse connecter 
 * @param String $query         The query 
 * @param bool $protect_outputs If true, the outputs results will be protected (for 'select' query)
 * @param bool $insert          If true, is an insertion query
 * @param bool $multi           If there are severals query in $query
 * @return Array                The result in an array
 */
function pog_db_execute($db,$query,$protect_outputs = true,$insert = false,$multi = false){
    $array = null;
    if($multi){ // Multi query
        $res = mysqli_multi_query($db,$query);
        if(!$res){
            pog_db_error($db,$query);
        }

        if($insert){
            return $res;
        }

        $i = 0;
        do {
            if ($result = mysqli_store_result($db,0)) {
                while ($data = mysqli_fetch_assoc($result)) {
                    $array[$i][] = ($protect_outputs) ? pog_db_protect_outputs($data) : $data;
                }
                mysqli_free_result($result);
            }
            
            if (!mysqli_more_results($db)) {
                break;
            }
            $i++;
        } while (mysqli_next_result($db));

        return $array;
    }

    // Single query

    $res = mysqli_query($db,$query);

    if(!$res){
        pog_db_error($db,$query);
    }
    
    if($insert){
        return $res;
    }

    while($data = mysqli_fetch_assoc($res)){
        $array[] = ($protect_outputs) ? pog_db_protect_outputs($data) : $data;
    }

    mysqli_free_result($res);
    return $array;
}

/**
 * Protection of database outputs. (htmlentities())
 * @param mixed $content    The array or string to protect
 * @return mixed            The array or string protected
 */
function pog_db_protect_outputs($content) {
    if (is_array($content)) {
        foreach ($content as &$value) { 
            $value = pog_db_protect_outputs($value);   
        }
        unset ($value);
        return $content;
    }
    if (is_string($content)){
        $protected_content = htmlentities($content,ENT_QUOTES);
        return $protected_content;
    }
    return $content;
}

/**
 * Protection of database inputs.
 * @param Object $db        The database connecter
 * @param mixed $content    The array or string to protect
 * @return mixed            The array or string protected
 */
function pog_db_protect_inputs($db,$content) {
    if (is_array($content)) {
        foreach ($content as &$value) { 
            $value = pog_db_protect_inputs($db,$value);   
        }
        unset ($value);
        return $content;
    }
    if (is_string($content)){
        $protected_content = mysqli_real_escape_string($db,$content);
        return $protected_content;
    }
    return $content;
}
