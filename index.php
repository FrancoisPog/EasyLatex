<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>EasyLatex</title>
        <link rel="stylesheet" href="styles/easylatex.css">
    </head>
    <body id="index">
        <header>
            <h1>EasyLatex</h1>
            <h2>Make Latex document without code !</h2>
        </header>

        <main>
            <div class="editor">
                <div class="editor-input" id="editable" contenteditable="true">
                    Markdown *content* here
                </div>
            </div>
            <div class="viewer">
                <div class="viewer-wrapper">
                    <embed  src="https://latexonline.cc/compile?url=https://francois.poguet.com/manual.tex" type="application/pdf">
                </div>
            </div>    
        </main>
        
        <footer>
            <p>EasyLatex - François Poguet &copy;</p>
        </footer>
        <script src="js/main.js"></script>
    </body>
</html>