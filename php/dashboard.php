<?php

ob_start();
session_start();
require_once('lib-EasyLatex.php');

/**
 * Fetch the user project
 */
function pog_fecth_projects(){
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
 * Create a new project
 */
function pog_new_project(){

    // Check input
    pog_check_param($_POST,['el_newproject','el_newproject_name']) or pog_session_exit('../');

    if(!preg_match('/^[^<>]{1,100}$/',$_POST['el_newproject_name'])){
        pog_session_exit('../');
    }

    // Get user data
    $db = pog_db_connecter();
    $author = pog_db_protect_inputs($db,$_SESSION['username']);

    $query = "SELECT us_first_name, us_last_name
                FROM el_user
                WHERE us_username = '${author}'";

    $authorData = pog_db_execute($db,$query,false);

    // TODO test if res = null


    $author_str = pog_db_protect_inputs($db,$authorData[0]['us_first_name'].' '.$authorData[0]['us_last_name']);


    // Insert project in database
    $_POST = pog_db_protect_inputs($db,$_POST);
    extract($_POST);

    
    $name = $_POST['el_newproject_name'];
    $date = pog_getDate();

    $filename = md5(uniqid(rand(),true));

    $query = "INSERT INTO el_project SET
                pr_author = '${author}',
                pr_content = '',
                pr_creat_date = '${date}',
                pr_modif_date = '${date}',
                pr_filename = '${filename}',
                pr_name = '${name}',
                pr_cover_title = '${name}',
                pr_cover_author = '${author_str}',
                pr_cover_date = 0";
    
    pog_db_execute($db,$query,false,true);

    mysqli_close($db);

}

/**
 * Print the user dashboard
 * @param $projects The user projects
 */
function pog_print_dashboard($projects){
    pog_print_header(1,'dashboard','Dashboard');


    echo    
            '<form  class="section form" id="newproject" action="dashboard.php" method="POST">',
                '<h2>New project</h2>',
                pog_html_input('el_newproject_name','Project name'),
                pog_html_button('el_newproject','Create','submit',true),
            '</form>',
            '<section class="section">',
                '<h2>Current projects</h2>',
                '<table>',
                    '<thead>',
                        '<tr>',
                            '<th>Name</th>',
                            '<th>Last changes</th>',
                        '</tr>',
                    '</thead>',
                    '<tbody>';

                    foreach($projects as $project){
                        $id = pog_encrypt_url([$project['pr_id']]);
                        $name = $project['pr_name'];
                        $last_changes = pog_getTimeFrom($project['pr_modif_date']);
                        echo "<tr><td><a href='project.php?data=${id}'>$name</a></td><td>$last_changes</td>";
                    }

    echo            '</tbody>',
                '</table>',
            '</section>',
            pog_html_script('../js/forms.js');
                

                    
    pog_print_footer();
}


// MAIN
pog_isLogged('../');

if(isset($_POST['el_newproject'])){
    pog_new_project();
}

$projects = pog_fecth_projects();

pog_print_dashboard($projects);

