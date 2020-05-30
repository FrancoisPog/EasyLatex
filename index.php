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
                # Game mode <br>
                <br>
                ## File management<br>
                - **Save** : Save the current map<br>
                - **Open** : Open a map<br>
                <br>
                ## Game management<br>
                To manually place the hunters on the board, just click on the cells, you can click again on a hunter to remove it.<br>


                </div>
            </div>
            <div class="buttons">
                <button id="btn-preview">Preview markdown</button>
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