// Get elements
let btn_preview = document.getElementById('btn-preview'); 
let btn_convert = document.getElementById('btn-convert');

let btn_syntax = document.getElementById('btn-syntax');
let elt_syntax = document.getElementsByClassName('md-syntax')[0];
let btn_exit_syntax = document.getElementById('md-syntax-exit');

let btn_italic = document.getElementById('btn-italic');
let btn_bold = document.getElementById('btn-bold');
let form = document.querySelector('form');

// Debug
let DEBUG = true;
function debug($str){
    DEBUG && console.log($str);
}


let preview_mode = false;
let markdown_svg = "";

btn_preview.addEventListener('click',function(e){
    e.preventDefault();
    if(preview_mode){
        back_to_markdown();
    }else{
        converter_to_preview();    
    }
});



form.addEventListener("submit",function(e){
    e.preventDefault();

    if(preview_mode){
        form.elements[0].value = markdown_svg;
    }else{
        form.elements[0].value = document.getElementsByClassName('editor-input')[0].value;
    }


    form.elements[1].value = converter_to_latex();
   form.submit();



});


/**
 * Display the markdown on editor
 */
 function back_to_markdown(){
    let preview = document.getElementsByClassName("editor-input")[0];
    let area = document.createElement("textarea");
    area.classList.add('editor-input');
    area.value = markdown_svg;
    area.name = "content";

    preview.parentNode.replaceChild(area,preview);
    preview_mode = false;
}


btn_italic.addEventListener('click',(e)=>{
    e.preventDefault();
    change_selection_style('i');
});

btn_bold.addEventListener('click',(e)=>{
    e.preventDefault();
    change_selection_style('b');
});



function change_selection_style(tag){

    let editor = document.getElementsByClassName("editor-input")[0];
    
    let s1 = editor.selectionStart;
    let s2 = editor.selectionEnd;

    

    let text = editor.value;

    editor.value = (text.substring(0,s1) + '['+tag+':' + text.substring(s1,s2) + ':'+tag+']' + text.substring(s2,text.lenght));

    editor.focus();

    debug(s1+":"+s2);
}



btn_syntax.addEventListener('click',(e)=>{
    e.preventDefault();
    elt_syntax.classList.add('open');
    form.classList.add('blurred');

});

btn_exit_syntax.addEventListener('click',(e)=>{
    e.preventDefault();
    elt_syntax.classList.remove('open');
    form.classList.remove('blurred');
});