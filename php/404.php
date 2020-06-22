<?php
ob_start();
session_start();

require_once('_easylatex.php');
pog_print_error_page('404 : Page not found &#128269;','<p>The page you\'re looking for doesn\'t exist.</p>'.(pog_html_button('error_back','<a href='.((pog_isLogged())?'"dashboard/">Back to dashboard':'".">Login'.'</a>'))));
