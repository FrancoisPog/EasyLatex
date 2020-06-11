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

    $query = "SELECT pr_id, pr_name
                FROM el_project
                WHERE pr_author = '${author}'";

    $projects = pog_db_execute($db,$query);

    mysqli_close($db);

    return ($projects)?$projects:[];
}

/**
 * Print the user dashboard
 * @param $projects The user projects
 */
function pog_print_dashboard($projects){
    pog_print_header(1,'dashboard');

    echo    
            '<form  class="section form" id="newproject" action="projects_manager" method="POST">',
            '<h2>New project</h2>',
                pog_html_input('el_newproject_name','Project name'),
                pog_html_button('el_newproject','Create','submit',false),
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
                        echo "<tr><td><a href='project.php?data=${id}'>$name</a></td><td>Yesterday</td>";
                    }

    echo            '</tbody>',
                '</table>',
            '</section>';
                


    pog_print_footer();
}


// MAIN
pog_isLogged('../');

$projects = pog_fecth_projects();

pog_print_dashboard($projects);

