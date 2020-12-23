// -- ON LOAD ---

let editorIsFocused = false;
window.onload = () => {
  set_editor_input(get_editor_input());
};

// -- SWITCH BETWEEN markup & PREVIEW ---

let preview_mode = false;
let markup_svg = "";
let btn_preview = document.getElementById("btn-preview");
btn_preview.onclick = (e) => {
  e.preventDefault();
  if (preview_mode) {
    back_to_markup();
    btn_preview.textContent = "See preview";
  } else {
    converter_to_preview();
    btn_preview.textContent = "Back to markup";
  }
};

// -- CONVERT TO LATEX
// '<input type="hidden" name="latex">',
let btn_convert = document.getElementById("btn-convert");
let form = document.querySelector("form");
let errors = document.getElementsByClassName("errors")[0];
let errors_list = document.getElementsByClassName("errors-list")[0];

form.onsubmit = (e) => {
  e.preventDefault();
  form.elements[0].value = preview_mode ? markup_svg : get_editor_input().value;

  res = converter_to_latex();
  console.log(res);

  if (Array.isArray(res)) {
    errors.style.display = "block";
    errors_list.innerHTML = "";
    for (let error of res) {
      let errorElt = document.createElement("li");
      errorElt.innerHTML = error;
      errors_list.appendChild(errorElt);
    }
    return;
  }

  let latex_input = document.createElement("input");
  latex_input.type = "hidden";
  latex_input.name = "latex";
  latex_input.value = res;

  form.appendChild(latex_input);

  form.submit();
};

// --- DISPLAY markup SYNTAX ---

let btn_syntax = document.getElementById("btn-syntax");
let elt_syntax = document.getElementsByClassName("md-syntax")[0];
let btn_exit_syntax = document.getElementById("md-syntax-exit");

btn_syntax.onclick = (e) => {
  e.preventDefault();
  elt_syntax.classList.add("open");
  form.classList.add("blurred");
};

btn_exit_syntax.onclick = (e) => {
  e.preventDefault();
  elt_syntax.classList.remove("open");
  form.classList.remove("blurred");
};

// --- REMOVE VIEWER FOR MOBILE ---

let viewer = document.getElementsByClassName("viewer-wrapper")[0];
let filename = viewer.getAttribute("src");
console.log(filename);

let viewer_parent = viewer.parentNode;

window.onresize = () => {
  let width = window.innerWidth;
  if (width < 900) {
    console.log("a");
    if (!viewer_parent.contains(viewer)) {
      return;
    }
    viewer_parent.removeChild(viewer);
  } else {
    console.log("b");
    if (viewer_parent.contains(viewer)) {
      return;
    }
    viewer_parent.appendChild(viewer);
  }
};

// --- FUNCTIONS ---

/**
 * Back to the markup on editor
 */
function back_to_markup() {
  let preview = get_editor_input();
  let editor = set_editor_input(null);

  preview.parentNode.classList.remove("preview");
  preview.parentNode.replaceChild(editor, preview);
  preview_mode = false;
}

/**
 * Getter for the current editor element
 */
function get_editor_input() {
  return document.getElementsByClassName("editor-input")[0];
}

/**
 * Set the editor input element
 * @param {Element} editor
 */
function set_editor_input(editor = null) {
  if (editor == null) {
    editor = document.createElement("textarea");
    editor.classList.add("editor-input");
    editor.classList.add("input");
    editor.value = markup_svg;
    editor.name = "content";
  }

  editor.onfocus = () => {
    editorIsFocused = true;
  };
  editor.onblur = () => {
    editorIsFocused = false;
  };

  return editor;
}
