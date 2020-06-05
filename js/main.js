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
let btn_newpar = document.getElementById('btn-newpar');

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
    area.onfocus = () => {editorIsFocused = true;};
    area.onblur = (e) => {editorIsFocused = false};   

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

// New paragraph
btn_newpar.addEventListener('click',(e)=>{
    e.preventDefault();
    change_selection_style('par',1);
});

// Selection style
function change_selection_style(tag, isSingleTag = false){
    let editor = document.getElementsByClassName("editor-input")[0];
    let s1 = editor.selectionStart;
    let s2 = editor.selectionEnd;
    let text = editor.value;

    if(isSingleTag){
        editor.value = text.substring(0,s2) + '[:' + tag + ':]' + text.substring(s2,text.lenght);
        console.log(tag.length);
        setSelectionRange(editor,s2+4+tag.length,s2+4+tag.length);
        return;
    }

    editor.value = (text.substring(0,s1) + '['+tag+':' + text.substring(s1,s2) + ':'+tag+']' + text.substring(s2,text.lenght));
    
    if(s1 == s2){
        setSelectionRange(editor,s2+2+tag.length,s2+2+tag.length);
    }else{
        setSelectionRange(editor,s2+4+2*tag.length,s2+4+2*tag.length);
    }
    
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



function setSelectionRange(input, selectionStart, selectionEnd) {
    if (input.setSelectionRange) {
      input.focus();
      input.setSelectionRange(selectionStart, selectionEnd);
    }else if (input.createTextRange) {
      let range = input.createTextRange();
      range.collapse(true);
      range.moveEnd('character', selectionEnd);
      range.moveStart('character', selectionStart);
      range.select();
    }
  }
  
  function setCaretToPos (input, pos) {
     setSelectionRange(input, pos, pos);
  }


  
function setKeyboardShortcuts(){
    document.onkeydown = (e) => {
        if(!editorIsFocused){
            return;
        }
        if(e.ctrlKey){
            if(e.which == 66){
                e.preventDefault();
                change_selection_style('b');
            }else if(e.which == 73){
                e.preventDefault();
                change_selection_style('i');
            }else if(e.keyCode == 13){
                e.preventDefault();
                change_selection_style('nl',true);
            }
        }
    }
}


// TODO make an editor constructor
let editor = document.getElementsByClassName('editor-input')[0];
let editorIsFocused = false;

setKeyboardShortcuts();

editor.onfocus = () => {editorIsFocused = true;};

editor.onblur = () => {editorIsFocused = false};

console.log(editor);