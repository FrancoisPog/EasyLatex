<?php

/**
 * Create a new project and return it
 * @param array $array The array that contains project data
 * @param string $content The content at the project creation
 * @param string $title The document title, if not specified, the title is the project name
 * @return array The new project
 */
function pog_project_create($array = null, $content = '',$title = null){
    $array = ($array) ? $array : $_POST;

    // Check input
    pog_check_param($array,['el_newproject','el_newproject_name']) or pog_session_exit('.');

    if(!preg_match('/^[^<>]{1,100}$/',$array['el_newproject_name'])){
        pog_session_exit('.');
    }

    // Get user data
    $db = pog_db_connecter();
    $author = pog_db_protect_inputs($db,$_SESSION['username']);

    $query = "SELECT us_first_name, us_last_name
                FROM el_user
                WHERE us_username = '${author}'";

    $authorData = pog_db_execute($db,$query,false);

    if(!$authorData){
        pog_session_exit('.');
    }


    $author_str = pog_db_protect_inputs($db,$authorData[0]['us_first_name'].' '.$authorData[0]['us_last_name']);


    // Insert project in database
    $array = pog_db_protect_inputs($db,$array);
    extract($array);

    
    $name = $array['el_newproject_name'];
    $date = pog_getDate();

    $filename = md5(uniqid(rand(),true));
    $cover_title = ($title)?$title:$name;
    $content = pog_db_protect_inputs($db,$content);

    $query = "INSERT INTO el_project SET
                pr_author = '${author}',
                pr_content = '${content}',
                pr_creat_date = '${date}',
                pr_modif_date = '${date}',
                pr_filename = '${filename}',
                pr_name = '${name}',
                pr_cover_title = '${cover_title}',
                pr_cover_author = '${author_str}',
                pr_cover_date = 0;
                SELECT *
                FROM el_project
                WHERE pr_filename = '${filename}'
                AND pr_creat_date = '${date}'
                AND pr_author = '${author}'";
    
    $project = pog_db_execute($db,$query,false,false,true);

    mysqli_close($db);

    return $project[1][0];
}


/**
 * Write the latex code on a file
 * @param array $project The project to parse
 * @param string $prefix The path prefix to the .tex file
 * @param string $latex The latex code to parse, if not specified, the latex input in $_POST is used
 */
function pog_project_parse($project,$prefix = '..',$latex = null){
    $filename = $project['pr_filename'];
    $filpath = "${prefix}/projects/${filename}.tex";
    $file = fopen($filpath,'w+');

    $type = $project['pr_type'];
    $lang = ($project['pr_lang'] == 'fr')?'french':'english';
    $content_table = ($project['pr_table_content'] == 1)?(($type == 'report')?'\tableofcontents\newpage':'\tableofcontents'):'';

    $title = str_replace('\\','\\\\',$project['pr_cover_title']);
    $author = str_replace('\\','\\\\',$project['pr_cover_author']);
    $date = ($project['pr_cover_date'] == '0')?'':"\\date{".str_replace('\\','\\\\',$project['pr_cover_date'])."}";

    $latex_begin = "\documentclass{{$type}}\usepackage[utf8]{inputenc}\usepackage[T1]{fontenc}\usepackage[$lang]{babel}\setlength{\parindent}{0cm}\\renewcommand{\\thesection}{\arabic{section}}\\title{{$title}}\author{{$author}}$date\begin{document}\maketitle$content_table ";
    $latex_end = ' \end{document}';

    $content = ($latex)?$latex:$_POST['latex'];

    fwrite($file,$latex_begin);
    fwrite($file,$content);
    fwrite($file,$latex_end);

    fclose($file);
}

/**
 * Fetch project data in database
 * @param int $id The project id
 * @param bool $protect If true, the project data will be protected 
 * @return Array The project
 */
function pog_project_fetch($id,$protect = true){
    $db = pog_db_connecter();
    $author = $_SESSION['username'];
    $query = "SELECT *
                FROM el_project
                WHERE pr_id = ${id}
                AND pr_author = '${author}'";

    $project = pog_db_execute($db,$query,$protect);

    mysqli_close($db);

    return $project[0];
}

/**
 * Fetch all projects of the logged user
 * @return array An array of projects
 */
function pog_project_fetch_all(){
    $db = pog_db_connecter();

    $author = $_SESSION['username'];

    $query = "SELECT pr_id, pr_name, pr_modif_date
                FROM el_project
                WHERE pr_author = '${author}'
                ORDER BY pr_modif_date DESC";

    $projects = pog_db_execute($db,$query);

    mysqli_close($db);

    return ($projects)?$projects:[];
}

/**
 * Update the content of a project
 * @param int $id The project id
 * @param array $array The array that contains project data
 * @return array The project updated
 */
function pog_project_update_content($id,$array = null){
    $array = ($array) ? $array : $_POST;

    $db = pog_db_connecter();

    $id = pog_db_protect_inputs($db,$id);
    $user = $_SESSION['username'];

    $content = pog_db_protect_inputs($db,$array['markup']);
    $date = pog_getDate();

    $query = "  UPDATE el_project SET
                pr_content = '${content}',
                pr_modif_date = $date
                WHERE pr_id = '${id}';
                SELECT *
                FROM el_project
                WHERE pr_id = '${id}'
                AND pr_author = '${user}'";

    $project = pog_db_execute($db,$query,false,false,true);

    mysqli_close($db);
    
    return $project[1][0];
}

/**
 * Update the settings of a project
 * @param int $id The project id
 * @param array $array The array that contains project data
 * @return array The project updated
 */
function pog_project_update_settings($id,$array = null){
    $array = ($array) ? $array : $_POST;
    $db = pog_db_connecter();

    $array = pog_db_protect_inputs($db,$array);
    extract($array);

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

function pog_project_rename(){

}

function pog_project_delete(){

}