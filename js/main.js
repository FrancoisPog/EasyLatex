let editor = document.getElementsByClassName('editor-input')[0];
let btn_preview = document.getElementById('btn-preview'); 
let btn_convert = document.getElementById('btn-convert');

let form = document.querySelector('form');

const regex_m2p = { 
                    '<em>$1</em>' : /\[i\]([^\[]*((\[[^i])?([^[]*?))*)_/gim,
                        
                                             
                }

const regex_p2m = { 
                    '[i]$1_' : /<em>(.*?)<\/em>/gim,
                        
                }


const regex_p2l = {
                        '\\section{$1}' : /<h3> *(.*?)<\/h3>/gmi,
                        '\\subsection{$1}' : /<h4>(.*?)<\/h4>/gmi,
                        '\\textit{$1}' : /<em>(.*?)<\/em>/gmi,
                        '\\textbf{$1}' : /<strong>(.*?)<\/strong>/gmi,
                        ' ' : /<br ?\/?>/gmi
                    }


function parseMarkdown(match,p1,p2,offset,og){
    console.log(match,p1,p2,offset,og);

    for (const regex_type in regex_m2p) {
        p1 = p1.replace(regex_m2p[regex_type],parseMarkdown);
    } 
}

function markdown2preview(){
    let editor = document.getElementsByClassName("editor-input")[0];
    let content = editor.value;
    
    for (const regex_type in regex_m2p) {
        content = content.replace(regex_m2p[regex_type],parseMarkdown);
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

    for (const regex_type in regex_p2m) {
        content = content.replace(regex_p2m[regex_type],regex_type);
        
    }

    let area = document.createElement("textarea");
    area.classList.add('editor-input');
    area.value = content;
    area.name = "content";

    preview.parentNode.replaceChild(area,preview);
}


function preview2latex(){
    let editor = document.getElementsByClassName("editor-input")[0];
    if(editor.tagName != "DIV"){
        console.log("hey");
        markdown2preview();
    }
    let content = document.getElementsByClassName("editor-input")[0].innerHTML;
    
    for (const regex_type in regex_p2l) {
        content = content.replace(regex_p2l[regex_type],regex_type);
        
    }

    return content;

}


let preview_mode = false;

btn_preview.addEventListener('click',function(e){
    e.preventDefault();
    if(preview_mode){
        preview2markdown();
        preview_mode = false;
    }else{
        markdown2preview();  
        preview_mode = true;
    }
});

// btn_convert.addEventListener("click",function(){
     
// });



form.addEventListener("submit",function(e){
    e.preventDefault();
    form.elements[0].value = preview2latex();  ;
    console.log(form.elements);
    preview_mode = true; 
    form.submit();
});
