let editor = document.getElementsByClassName('editor-input')[0];
let btn_preview = document.getElementById('btn-preview'); 

const regex = { 
        '<strong><em>$2</em></strong>'  : /(\*){3,}([^\*]+)(\*){3,}/gmi ,
                        '<strong>$1</strong>'   :   /\*\*([^\*]+)\*\*/gmi,
                        '<em>$1</em>'           :   /\*([^\*]+)\*/gmi,
                        '<h4>$1</h4>'         :   /\#\#(.+)/gmi,
                        '<h3>$1</h3>'         :   /\#(.+)/gmi,
                        
                }

const regex_inverse = {
                        '**$1**'    :   /<strong>(.*?)<\/strong>/gmi,
                        '*$1*'      :   /<em>(.*?)<\/em>/gmi,
                        '##$1'      :   /<h4>(.*?)<\/h4>/gmi,
                        '#$1'      :   /<h3>(.*?)<\/h3>/gmi
                        }

function markdown2preview(){
    let editor = document.getElementsByClassName("editor-input")[0];
    let content = editor.value;
    console.log(content);
    for (const regex_type in regex) {
        content = content.replace(regex[regex_type],regex_type);
    } 

    let preview = document.createElement("div");
    preview.classList.add('editor-input');
    preview.innerHTML = content;

    editor.parentNode.replaceChild(preview,editor);

    editor.innerHTML = content;
}

function preview2markdown(){
    let preview = document.getElementsByClassName("editor-input")[0];
    let content = preview.innerHTML;
    console.log(content);

    for (const regex_type in regex_inverse) {
        content = content.replace(regex_inverse[regex_type],regex_type);
        
    } 
    console.log(content);

    let area = document.createElement("textarea");
    area.classList.add('editor-input');
    area.value = content;

    preview.parentNode.replaceChild(area,preview);
}


let preview_mode = false;

btn_preview.addEventListener('click',function(e){
    if(preview_mode){
        preview2markdown();
        preview_mode = false;
    }else{
        markdown2preview();  
        preview_mode = true;
    }
});




