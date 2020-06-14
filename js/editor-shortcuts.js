// -- SET EDITOR BUTTONS & KEYBOARD SHORTCUTS

let btn_italic = document.getElementById('btn-italic');
let btn_bold = document.getElementById('btn-bold');
let btn_newline = document.getElementById('btn-newline');
let btn_newpage = document.getElementById('btn-newpage');
let btn_newpar = document.getElementById('btn-newpar');

// Italic
btn_italic.onclick = (e) => {
    e.preventDefault();
    change_selection_style('i');
};

// Bold
btn_bold.onclick = (e)=>{
    e.preventDefault();
    change_selection_style('b');
};

// New Line
btn_newline.onclick = (e)=>{
    e.preventDefault();
    change_selection_style('nl',true);
};

// New page
btn_newpage.onclick = (e)=>{
    e.preventDefault();
    change_selection_style('np',true);
};

// New paragraph
btn_newpar.onclick = (e)=>{
    e.preventDefault();
    change_selection_style('par',1);
};

// Keyboard listener
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
        }else if(e.which == 76){
            e.preventDefault();
            change_selection_style('nl',true);
        }else if(e.keyCode == 161){
            e.preventDefault();
            change_selection_style('par',true);
        }else if(e.which == 80){
            e.preventDefault();
            change_selection_style('np',true);
        }
    }
}


// --- FUNCTIONS --- 


/**
 * Add a font style tag to the text selection
 * @param {String} tag  The markup tag to add 
 * @param {boolean} isSingleTag True is is a single tag
 */
function change_selection_style(tag, isSingleTag = false){
    let editor = get_editor_input();
    let s1 = editor.selectionStart;
    let s2 = editor.selectionEnd;
    let text = editor.value;

    if(isSingleTag){
        editor.value = text.substring(0,s2) + '[:' + tag + ':]' + text.substring(s2,text.lenght);
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

/**
 * Set a selection range in a editable element
 * @param {Element} input The element used
 * @param {number} selectionStart The start selection offset
 * @param {number} selectionEnd The end selection offset
 */
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
  
/**
 * Position the caret on a specific position
 * @param {element} input The element used
 * @param {number} pos  The caret position wanted 
 */
function setCaretToPos (input, pos) {
    setSelectionRange(input, pos, pos);
}

