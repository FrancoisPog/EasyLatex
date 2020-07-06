<?php

ob_start();
session_start();
require_once('php/_easylatex.php');



/**
 * Print the index page
 */
function pog_print_index($mode = 'login' ,$error = ''){
    pog_print_header(0,'index','Make LaTex documents without code !');
    
    $_SESSION['security_token'] = md5(uniqid(microtime()));
    $token = $_SESSION['security_token'];

    echo '<article>',
            '<h2>Improve the quality of your document ! </h2>',
            '<p>With EasyLatex, you can make great LaTex document without know how to code, with a simple markup language</p>',
            '<img src="images/index_example.png" >',
        '</article>';


    echo '<form class="form login" ',($mode == 'login')?'':'style="display : none;"',' id="login-form" action="." method="POST" >',
                '<h1>Login</h1>',
                ($error != '' && $mode == 'login') ? pog_html_error($error):'',
                pog_html_input('el_login_username','Username','','text',true),
                pog_html_input('el_login_password','Password','','password',true),
                pog_html_checkbox('el_login_remember','Remember me',true),
                pog_html_button('el_login','Login','submit'),
                '<p>You don\'t have any account ?</p>',
                pog_html_button('sign_up','Sign up'),
                "<input type='hidden' name='el_login_token' value='${token}'>",
            '</form>',
            '<form class="form signup" ',($mode == 'signup')?'':'style="display : none;"',' id="signup-form" method="POST" action=".">',
                '<h1>Sign Up</h1>',
                ($error != '' && $mode == 'signup') ? pog_html_error($error):'',
                pog_html_input('el_signup_username','Username',(isset($_POST['el_signup_username']))?$_POST['el_signup_username']:''),
                pog_html_input('el_signup_firstname','First name',(isset($_POST['el_signup_firstname']))?$_POST['el_signup_firstname']:''),
                pog_html_input('el_signup_password','Password','','password'),
                pog_html_input('el_signup_lastname','Last name',(isset($_POST['el_signup_lastname']))?$_POST['el_signup_lastname']:''),
                pog_html_input('el_signup_passwordRepeat','Confirm password','','password'),
                pog_html_checkbox('el_signup_remember','Remember me',(isset($_POST['el_signup']))?(isset($_POST['el_signup_remember'])):true),
                pog_html_button('el_signup','Sign up','submit',true),
                '<p>Already have an account ?</p>',
                pog_html_button('el_signup_login','Login'),
                "<input type='hidden' name='el_signup_token' value='${token}'>",
            '</form>',
            pog_html_script('js/index.js'),
            pog_html_script('js/forms.js');
            
            


    pog_print_footer();
}


/**
 * Check if the signup form is correctly submited
 */
function pog_signup_hackGuard(){
    $mandatory = ['el_signup_username','el_signup_firstname','el_signup_lastname','el_signup_password','el_signup_passwordRepeat','el_signup','el_signup_token'];
    $optional = ['el_signup_remember'];

    pog_check_param($_POST,$mandatory,$optional) or pog_session_exit('.');

    

    ($_SESSION['security_token'] == $_POST['el_signup_token']) or pog_session_exit('.');

    // Data are already checked by JavaScript, so if they are not valid here -> Hacking case

    $_POST = array_map('trim',$_POST);

    // First/Last name
    foreach(['el_signup_firstname','el_signup_lastname'] as $input){
        pog_str_containsHTML($_POST[$input]) and pog_session_exit('.');
        (strlen($_POST[$input]) <= 50 && strlen($_POST[$input]) > 0 ) or pog_session_exit('.'); 
    }

    // Username
    preg_match('/^[a-zA-Z0-9]{6,20}$/',$_POST['el_signup_username']) or pog_session_exit('.');

    // Password
    (strlen($_POST['el_signup_password']) > 0) or pog_session_exit('.');
    ($_POST['el_signup_password'] == $_POST['el_signup_passwordRepeat']) or pog_session_exit('.');
}


/**
 * Insert new user in database
 */
function pog_signup_database(){
    $db = pog_db_connecter();

    $_POST = pog_db_protect_inputs($db,$_POST);

    extract($_POST);

    

    $query = "SELECT us_username
                FROM el_user
                WHERE us_username = '${el_signup_username}'";

    if(pog_db_execute($db,$query)){
        mysqli_close($db);
        return 1;
    }


    $pass = password_hash($_POST['el_signup_password'],PASSWORD_DEFAULT);

    $date = pog_getDate();

    

    $query = "INSERT INTO el_user SET
                us_username = '${el_signup_username}',
                us_first_name = '${el_signup_firstname}',
                us_last_name = '${el_signup_lastname}',
                us_password = '${pass}',
                us_signUpDate = '${date}'";

    pog_db_execute($db,$query,false,true);

    mysqli_close($db);

    return 0;
}

/**
 * Check if the login form is correctly submited
 */
function pog_login_hackGuard(){
    $mandatory = ['el_login_username','el_login_password','el_login','el_login_token'];
    $optional = ['el_login_remember'];

    
    pog_check_param($_POST,$mandatory,$optional) or pog_session_exit('.');

    
    ($_SESSION['security_token'] == $_POST['el_login_token']) or pog_session_exit('.');
}

/**
 * Connect the user
 * @return int 0 on success, else 1
 */
function pog_login_connection(){
    $db = pog_db_connecter();

    $_POST = array_map('trim',$_POST);
    $_POST = pog_db_protect_inputs($db,$_POST);
    extract($_POST);

    $query = "SELECT us_password, us_username
                FROM el_user
                WHERE us_username = '${el_login_username}'";

    $res = pog_db_execute($db,$query);

    mysqli_close($db);

    if($res == null){
        return 1;
    }

    if(!password_verify($el_login_password,$res[0]['us_password'])){
        return 1;
    }

    pog_connection($res[0]['us_username'],isset($_POST['el_login_remember']));

    

    return 0;
}



/**
 * Connect a user
 * @param string $username The user's username
 * @param string $remeber If true, a cookie will be set to remember the user
 */
function pog_connection($username,$remember){
    $_SESSION['username'] = $username;

    if($remember){
        setcookie('username',$username,time()+3600*24*365,'/');
        setcookie('key',pog_encrypt_cookie_key($username),time()+3600*24*365,'/');
    }
}




// MAIN

if(pog_isLogged()){
    header('Location: dashboard/');
    exit(0);
}

// Sign Up
if(isset($_POST['el_signup'])){

    pog_signup_hackGuard();

    if(pog_signup_database() == 1){ 

        pog_print_index('signup','This username is already used');

    }else{

        pog_connection($_POST['el_signup_username'],isset($_POST['el_signup_remember']));

        $project_content = "# Conquest beginning\n\n[:par:]\nThe early era of [i:[b:space exploration:b]:i] was driven by a \"[i:Space Race:i]\" between the Soviet Union and the United States.\n\n# Several programs\n\n## The Appolo program\n\n[:par:]\nThe Apollo program, also known as Project Apollo, was the third United States human spaceflight program carried out by the National Aeronautics and Space Administration (NASA), which succeeded in landing the first men on the Moon from 1969 to 1972. [:nl:]\nIt was the third US human spaceflight program to fly, preceded by the two-person Project Gemini conceived in 1961 to extend spaceflight capability in support of Apollo.\n\n## The Space Shuttle program\n\n[:par:]\nThe Space Shuttle program was the fourth human spaceflight program carried out by the [i:National Aeronautics and Space Administration:i] (NASA), which accomplished routine transportation for Earth-to-orbit crew and cargo from 1981 to 2011.\n\n## International Space Station\n\n[:par:]\nThe [b:International Space Station:b] (ISS) is a modular space station (habitable artificial satellite) in low Earth orbit. The ISS program is a multi-national collaborative project between five participating space agencies: NASA (United States), Roscosmos (Russia), JAXA (Japan), ESA (Europe), and CSA (Canada). The ownership and use of the space station is established by intergovernmental treaties and agreements.It evolved from the Space Station Freedom proposal.\n\n# Further away\n\n[:par:]\nThe [i:Martian:i] system, focused primarily on understanding its geology and habitability potential. Engineering interplanetary journeys is complicated and the exploration of Mars has experienced a high failure rate, especially the early attempts. Some missions have met with unexpected success, such as the twin Mars Exploration Rovers, which operated for years beyond their specification.";
        
        $project = pog_project_create(['el_newproject_name'=>'Example','el_newproject'=>'on'],$project_content,'The space race');

        pog_project_parse($project,'.','\section{Conquest beginning}\paragraph{} The early era of \textit{\textbf{space exploration}} was driven by a "\textit{Space Race}" between the Soviet Union and the United States.  \section{Several programs} \subsection{The Appolo program}\paragraph{}  The Apollo program, also known as Project Apollo, was the third United States human spaceflight program carried out by the National Aeronautics and Space Administration (NASA), which succeeded in landing the first men on the Moon from 1969 to 1972. \leavevmode \\\\ It was the third US human spaceflight program to fly, preceded by the two-person Project Gemini conceived in 1961 to extend spaceflight capability in support of Apollo.   \subsection{The Space Shuttle program}\paragraph{} The Space Shuttle program was the fourth human spaceflight program carried out by the \textit{National Aeronautics and Space Administration} (NASA), which accomplished routine transportation for Earth-to-orbit crew and cargo from 1981 to 2011.   \subsection{International Space Station}\paragraph{} The \textbf{International Space Station} (ISS) is a modular space station (habitable artificial satellite) in low Earth orbit. The ISS program is a multi-national collaborative project between five participating space agencies: NASA (United States), Roscosmos (Russia), JAXA (Japan), ESA (Europe), and CSA (Canada). The ownership and use of the space station is established by intergovernmental treaties and agreements.It evolved from the Space Station Freedom proposal.   \section{Further away}\paragraph{} The \textit{Martian} system, focused primarily on understanding its geology and habitability potential. Engineering interplanetary journeys is complicated and the exploration of Mars has experienced a high failure rate, especially the early attempts. Some missions have met with unexpected success, such as the twin Mars Exploration Rovers, which operated for years beyond their specification. ');

        header('Location: dashboard/');
    }
    exit(0);
}

// Login
if(isset($_POST['el_login'])){
    pog_login_hackGuard();
    if(pog_login_connection() == 1){
        pog_print_index('login','Invalid username or password');
    }else{
        header('Location: dashboard/');
    }
    
    exit(0);
}

pog_print_index();