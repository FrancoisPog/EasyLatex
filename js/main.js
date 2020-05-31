// Get elements
let btn_preview = document.getElementById('btn-preview'); 
let btn_convert = document.getElementById('btn-convert');
let form = document.querySelector('form');

// Debug
let DEBUG = false;
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
    form.elements[0].value = converter_to_latex();  ;
    
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