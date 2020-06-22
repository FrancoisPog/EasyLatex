<?php

ob_start();
session_start();
require_once('_easylatex.php');

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
 * Print the user dashboard
 * @param $projects The user projects
 */
function pog_print_dashboard($projects){
    pog_print_header(0,'dashboard','Dashboard');


    echo    
            '<form  class="section form" id="newproject" action="dashboard/" method="POST">',
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
                        echo "<tr><td><a href='project/${id}/'>$name</a><a href='settings/${id}/' target='_blank'><img src='styles/icons/settings.svg'></a></td><td>$last_changes</td>";
                    }

    echo            '</tbody>',
                '</table>',
            '</section>',
            pog_html_script('js/forms.js');
                

                    
    pog_print_footer();
}


// MAIN
pog_isLogged('.');

if(isset($_POST['el_newproject'])){
    pog_new_project();
}

$projects = pog_fecth_projects();

pog_print_dashboard($projects);

