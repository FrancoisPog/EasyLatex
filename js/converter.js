// Regex
const markdown_regex    = /(\[(.):(.*?):(\2)\]|(\#{1,3}) *([^\#]+?) *\#+)/gmi;
const preview_regex     = /<(.*?)>(.*?)<\/(\1)>/gmi;

/**
 * Parse the markdown to preview html code
 * @param {String} match The string matched
 * @param {String} no_used Useless
 * @param {String} text_type The text type (undefined if title)
 * @param {String} text_content The text content (undefined if title)
 * @param {String} no_used Useless
 * @param {String} title_type The title type (undefined if text)
 * @param {String} title_content The title content (undefined if text)
 * @param {String} offset The offset in the original string
 * @param {String} og The original string
 */
function markdown_to_preview(match,no_used,text_type,text_content,no_used,title_type,title_content,offset,og){
    debug(match,"\t- ",no_used,"\t- ",text_type,"\t- ",text_content,"\t- ",no_used,"\t- ",title_type,"\t- ",title_content,"\t- ",offset,"\t- ",og);

    if(typeof(text_content) == "undefined"){
        if(title_content.match(markdown_regex)){
            title_content = title_content.replace(markdown_regex,markdown_to_preview);
        }

        if(title_type == "#"){
            return '<h3>'+title_content+'</h3>';
        }
        if(title_type == "##"){
            return '<h4>'+title_content+'</h4>';
        }
        if(title_type == "###"){
            return '<h5>'+title_content+'</h5>';
        }



        debug("error title");
    }

    if(text_content.match(markdown_regex)){
        text_content = text_content.replace(markdown_regex,markdown_to_preview);
    }

    if(text_type == 'i'){
        return '<em>'+text_content+'</em>';
    }

    if(text_type == 'b'){
        return '<strong>'+text_content+'</strong>';
    }

    debug("error text");
   
    
}

/**
 * Get the markdown code from editor and parse it for the preview
 */
function converter_to_preview(){
    let editor = document.getElementsByClassName("editor-input")[0];
    let content = editor.value;

    markdown_svg = content;
    
    if(content.match(/\[(.):([^\[\]])*(\[\1)/gmi)){
        alert("Syntax error");
        return;
    }

    if(content.match(/\#{4,}/gmi)){
        alert("Syntax error");
        return;
    }
    
    content = content.replace(/\n/gmi,'<br>');
    debug(content);
    content = content.replace(markdown_regex,markdown_to_preview);

    debug(content);
    

    let preview = document.createElement("div");
    preview.classList.add('editor-input');
    preview.innerHTML = content;

    editor.parentNode.replaceChild(preview,editor);

    editor.innerHTML = content;
    preview_mode = true;
}



/**
 * Parse the preview to latex
 * @param {String} match The string matched 
 * @param {String} tag_name The tage name
 * @param {String} tag_content The tag content
 * @param {String} no_used useless
 * @param {String} offset The offset in the original
 * @param {String} og The original string
 */
function preview_to_latex(match,tag_name,tag_content,no_used,offset,og){
    //debug(match,"\t- ",tag_name,"\t- ",tag_content,"\t- ",no_used,"\t- ",offset,"\t- ",og);

    if(tag_content.match(preview_regex)){
        tag_content = tag_content.replace(preview_regex,preview_to_latex);
    }

    if(tag_name == 'em'){
        return '\\textit{'+tag_content+'}';
    }

    if(tag_name == 'strong'){
        return '\\textbf{'+tag_content+'}';
    }

    if(tag_name == 'h3'){
        return '\\section{'+tag_content+'}';
    }

    if(tag_name == 'h4'){
        return '\\subsection{'+tag_content+'}';
    }

    debug("error"+tag_name);

}

/**
 * Convert to latex from editor
 */
function converter_to_latex(){
    let editor = document.getElementsByClassName("editor-input")[0];
    let content = "";

    if(editor.tagName != "DIV"){
        content = editor.value.replace(/\n/gmi,'<br>');
        content = content.replace(markdown_regex,markdown_to_preview);
    }else{
        content = editor.innerHTML;
    }

    debug(content);

    let latex = content.replace(/<br>/gmi,'');
    latex = latex.replace(preview_regex,preview_to_latex);

    debug(latex);

    return latex;
}