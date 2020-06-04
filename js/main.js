// Get elements
let btn_preview = document.getElementById('btn-preview'); 
let btn_convert = document.getElementById('btn-convert');

let btn_syntax = document.getElementById('btn-syntax');
let elt_syntax = document.getElementsByClassName('md-syntax')[0];
let btn_exit_syntax = document.getElementById('md-syntax-exit');

let btn_italic = document.getElementById('btn-italic');
let btn_bold = document.getElementById('btn-bold');
let btn_newline = document.getElementById('btn-newline');
let btn_newpage = document.getElementById('btn-newpage');

let form = document.querySelector('form');
let errors = document.getElementsByClassName('errors')[0];
let errors_list = document.getElementsByClassName('errors-list')[0];

// Debug
let DEBUG = true;

function debug($str){
    DEBUG && console.log($str);
}


let preview_mode = false;
let markdown_svg = "";

// Preview
btn_preview.addEventListener('click',function(e){
    e.preventDefault();
    if(preview_mode){
        back_to_markdown();
        btn_preview.textContent = "See preview";
    }else{
        converter_to_preview();
        btn_preview.textContent = "Back to markdown";    
    }
});


// Convert
form.addEventListener("submit",function(e){
    e.preventDefault();

    if(preview_mode){
        form.elements[0].value = markdown_svg;
    }else{
        form.elements[0].value = document.getElementsByClassName('editor-input')[0].value;
    }

    res = converter_to_latex();

    
    if(Array.isArray(res)){
        //console.log(res);
        errors.style.display = 'block';
        errors_list.innerHTML = '';

        for(let error of res){
            let errorElt = document.createElement('li');
            errorElt.innerHTML = error;
            errors_list.appendChild(errorElt);
        }
        
        return;
    }

    form.elements[1].value = res;
    form.submit();



});


/**
 * Display the markdown on editor
 */
 function back_to_markdown(){
    let preview = document.getElementsByClassName("editor-input")[0];
    let area = document.createElement("textarea");
    area.classList.add('editor-input');
    area.classList.add('input');
    preview.parentNode.classList.remove('preview');
    area.value = markdown_svg;
    area.name = "content";

    preview.parentNode.replaceChild(area,preview);
    preview_mode = false;
}

// Italic
btn_italic.addEventListener('click',(e)=>{
    e.preventDefault();
    change_selection_style('i');
});

// Bold
btn_bold.addEventListener('click',(e)=>{
    e.preventDefault();
    change_selection_style('b');
});

// New Line
btn_newline.addEventListener('click',(e)=>{
    e.preventDefault();
    change_selection_style('nl',true);
});

// New page
btn_newpage.addEventListener('click',(e)=>{
    e.preventDefault();
    change_selection_style('np',true);
});



// Selection style
function change_selection_style(tag, isSingle = false){
    let editor = document.getElementsByClassName("editor-input")[0];
    let s1 = editor.selectionStart;
    let s2 = editor.selectionEnd;
    let text = editor.value;
    if(isSingle){
        editor.value = text.substring(0,s2) + '[:' + tag + ':]' + text.substring(s2,text.lenght);
        return;
    }
    editor.value = (text.substring(0,s1) + '['+tag+':' + text.substring(s1,s2) + ':'+tag+']' + text.substring(s2,text.lenght));
    debug(s1+":"+s2);
}


// Markdown syntax - open
btn_syntax.addEventListener('click',(e)=>{
    e.preventDefault();
    elt_syntax.classList.add('open');
    form.classList.add('blurred');

});

// Markdown syntax - exit
btn_exit_syntax.addEventListener('click',(e)=>{
    e.preventDefault();
    elt_syntax.classList.remove('open');
    form.classList.remove('blurred');
});