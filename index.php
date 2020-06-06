<?php

ob_start();
require_once('php/lib-EasyLatex.php');

/**
 * Print the index page
 */
function pog_print_index(){
    pog_print_header(0,'index');

    echo '<form class="login-form" action="index.php" autocomplete="off">
                <h1>Login</h1>
                <div class="form-control">
                    <input
                        type="text"
                        name="el_username"
                        id="el_username"
                        
                        placeholder=" "
                        autocomplete="off"
                        
                    />
                    <label for="el_username">Username</label>
                </div>
                <div class="form-control">
                    <input
                        type="password"
                        name="el_pass"
                        id="el_password"
                        placeholder=" "
                        
                        list="ice"
                        autocomplete="off"
                    />
                    
                    <label for="el_password">Password</label>
                </div>
                <button type="submit" class="btn">Login</button>
            </form>';


    pog_print_footer();
}







// MAIN

pog_print_index();