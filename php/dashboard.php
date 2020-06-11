<?php

ob_start();
session_start();
require_once('lib-EasyLatex.php');

function pog_fecth_project(){
    $db = pog_db_connecter();

    $query = "SELECT pr_id
                FROM el_project";

    $projects = pog_db_execute($db,$query);

    mysqli_close($db);

    return $projects;
}

function pog_print_dashboard($projects){
    pog_print_header(1,'dashboard');

    echo    
            '<form class="form" id="newproject" action="projects_manager" method="POST">',
            '<h2>New projects</h2>',
                pog_html_input('el_newproject_name','Project name'),
                pog_html_button('el_newproject','Create','submit',false),
            '</form>',
            '<h2>Current projects</h2>';

            foreach($projects as $project){
                $id = cp_encrypt_url([$project['pr_id']]);
                echo "<a href='project.php?data=${id}'>Link</a>";
            }


    pog_print_footer();
}


// MAIN
pog_isLogged('../');

$projects = pog_fecth_project();

pog_print_dashboard($projects);

