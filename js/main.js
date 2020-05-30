let editor = document.getElementsByClassName('editor-input')[0];
let btn_preview = document.getElementById('btn-preview'); 

const regex = { '<strong><em>$2</em></strong>'  : /(\*){3,}([^\*]+)(\*){3,}/gmi ,
                        '<strong>$1</strong>'   :   /\*\*([^\*]+)\*\*/gmi,
                        '<em>$1</em>'           :   /\*([^\*]+)\*/gmi,
                        '<h4>$1</h4>'         :   /\#\#(.+)/gmi,
                        '<h3>$1</h3>'         :   /\#(.+)/gmi,
                        
                }

const regex_inverse = { '**$1**'    :   /<strong>(.*?)<\/strong>/gmi,
                        '*$1*'      :   /<em>(.*?)<\/em>/gmi,
                        '## $1'      :   /<h4>(.*?)<\/h4>/gmi,
                        '# $1'      :   /<h3>(.*?)<\/h3>/gmi
                        }

function markdown2preview(){
    let content = editor.innerHTML;
    for (const regex_type in regex) {
        content = content.replace(regex[regex_type],regex_type);
        
       
    } 
    editor.innerHTML = content;
}

function preview2markdown(){
    let content = editor.innerHTML;
    console.log(content);
    for (const regex_type in regex_inverse) {
        content = content.replace(regex_inverse[regex_type],regex_type);
        console.log(content);
    } 
    editor.innerHTML = content;
}


let preview_mode = false;

btn_preview.addEventListener('click',function(e){
    if(preview_mode){
        preview2markdown();
        editor.setAttribute('contenteditable','true');
        preview_mode = false;
    }else{
        markdown2preview();  
        editor.setAttribute('contenteditable','false');
        preview_mode = true;
    }
});




