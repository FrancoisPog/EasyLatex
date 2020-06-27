<?php

ob_start();
session_start();
require_once('_easylatex.php');

pog_print_header(1,'help','Help');

echo    '<h2>The markup editor</h2>',
            '<p>To make easier the text entry with markup tags, some buttons are on the top of the editor to write automatically these tags. You can also use keyboards shortcuts.</p>',
        '<h2>The text preview</h2>',
            '<p>You can preview your text correctly formatted before compiling it to LaTex, you must know that new lines are removed, only the [:nl:] tags allow to keep a new line. </p>',
        '<h2>The document settings</h2>',
            '<p>In the settings page, you can choose some document details, you can change the title, the author name and the date in the cover page. You can modifie the document type, the language and if the table of content is visible. You can also rename and delete the project.</p>',

pog_print_footer();