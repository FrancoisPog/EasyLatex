let editor = document.getElementsByClassName('editor-input')[0];
let btn_preview = document.getElementById('btn-preview'); 
let btn_convert = document.getElementById('btn-convert');

let form = document.querySelector('form');

const markdown_regex    = /\[(.):(.*?):(\1)\]/gmi;
const preview_regex     = /<(.*?)>(.*?)<\/(\1)>/gmi;

function parseMarkdown(match,p1,p2,p3,offset,og){
    console.log(match,"\t- ",p1,"\t- ",p2,"\t- ",p3,"\t- ",offset,"\t- ",og);

    if(p2.match(markdown_regex)){
        p2 = p2.replace(markdown_regex,parseMarkdown);
    }

    if(p1 == 'i'){
        return '<em>'+p2+'</em>';
    }

    if(p1 == 'b'){
        return '<strong>'+p2+'</strong>';
    }

    console.log("error");
   
    
}

function markdown2preview(){
    let editor = document.getElementsByClassName("editor-input")[0];
    let content = editor.value;
    
    if(content.match(/\[(.):([^\[\]])*(\[\1)/gmi)){
        alert("Syntax error");
        return;
    }

    content = content.replace(/\n/gmi,'<br>');
    
    content = content.replace(markdown_regex,parseMarkdown);

    console.log(content);
    

    let preview = document.createElement("div");
    preview.classList.add('editor-input');
    preview.innerHTML = content;

    editor.parentNode.replaceChild(preview,editor);

    editor.innerHTML = content;
    preview_mode = true;
}

function parsePreview(match,p1,p2,p3,offset,og){
    console.log(match,"\t- ",p1,"\t- ",p2,"\t- ",p3,"\t- ",offset,"\t- ",og);

    if(p2.match(preview_regex)){
        p2 = p2.replace(preview_regex,parsePreview);
    }

    if(p1 == 'em'){
        return '[i:'+p2+':i]';
    }

    if(p1 == 'strong'){
        return '[b:'+p2+':b]';
    }

    console.log("error"+p1);
   
    
}


function preview2markdown(){
    let preview = document.getElementsByClassName("editor-input")[0];
    let content = preview.innerHTML;

    

    content = content.replace(preview_regex,parsePreview);

    content = content.replace(/<br>/gmi,'\n');

    console.log(content);

    let area = document.createElement("textarea");
    area.classList.add('editor-input');
    area.value = content;
    area.name = "content";

    preview.parentNode.replaceChild(area,preview);
    preview_mode = false;
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
        
    }else{
        markdown2preview();  
        
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
