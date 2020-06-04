// Regex
const markdown_regex    = /(\[(.):(.*?):(\2)\]|(\#{1,3}\*?) *(.+?) *(<br>|$))/gmi;
const preview_regex     = /<(.*?)( class="(.*?)")?>[0-9\. ]*(.*?)<\/(\1)>/gmi;
var title_count = 0;
var subtitle_count = 0;

var first_call_m2p = 1;

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
function markdown_to_preview_rec(match,no_used,text_type,text_content,no_used,title_type,title_content,offset,og){
    debug([match,text_type,text_content,title_type,title_content]);

    // Title case
    if(typeof(text_content) == "undefined"){
        if(title_content.match(markdown_regex)){
            title_content = title_content.replace(markdown_regex,markdown_to_preview_rec);
        }

        let title_types = ['#','#*','##','##*','###','###*'];

        let title_index = title_types.indexOf(title_type)+1;

        if(title_index <= 6){
            let title_number = "";
            if(title_index == 1){
                title_count++;
                subtitle_count = 0;
                title_number = title_count;
            }else if(title_index == 3){
                subtitle_count++;
                title_number = title_count+"."+subtitle_count;
            }   

            return '<h'+title_index+'>'+title_number+" "+title_content+'</h'+title_index+'>';
        }

        
        return match;
    }

    // Text case
    if(text_content.match(markdown_regex)){
        text_content = text_content.replace(markdown_regex,markdown_to_preview_rec);
    }

    if(text_type == 'i'){
        return '<em>'+text_content+'</em>';
    }

    if(text_type == 'b'){
        return '<strong>'+text_content+'</strong>';
    }

    return match;   
    
}

function markdown_to_preview(text){
    title_count = 0;
    subtitle_count = 0;
    single_tags_regex = {   
                            '<span class="nl">&#8617;</span><br>' : /\[:nl:\]/gmi,
                            '<br><span class="np">&#9552;</span><br>' : /\[:np:\]/gmi
                        }

    content =  text.replace(/\n/gmi,'<br>').replace(markdown_regex,markdown_to_preview_rec);

    for(let tag in single_tags_regex){
        content = content.replace(single_tags_regex[tag],tag);
    }

    return content+"<br><br>";
}

/**
 * Get the markdown code from editor and parse it for the preview
 */
function converter_to_preview(){
    let editor = document.getElementsByClassName("editor-input")[0];
    let content = editor.value;

    markdown_svg = content;
    
    content = markdown_to_preview(content);
    
    let preview = document.createElement("div");
    preview.classList.add('editor-input');
    preview.classList.add('preview');
    editor.parentNode.classList.add('preview');
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
function preview_to_latex(match,tag_name,no_used,tag_class,tag_content,no_used,no_used,offset,og){
    debug([match,tag_name,tag_content,tag_class]);
    

    if(tag_content.match(preview_regex)){
        tag_content = tag_content.replace(preview_regex,preview_to_latex);
    }


    switch(tag_name){
        case 'em': {
            return '\\textit{'+tag_content+'}';
        }

        case 'strong' : {
            return '\\textbf{'+tag_content+'}';
        }

        case 'h1' : {
            return '\\section{'+tag_content+'}';
        }

        case 'h2' : {
            return '\\section*{'+tag_content+'}';
        }

        case 'h3' : {
            return '\\subsection{'+tag_content+'}';
        }

        case 'h4' : {
            return '\\subsection*{'+tag_content+'}';
        }

        case 'h5' : {
            return '\\subsubsection{'+tag_content+'}';
        }

        case 'h6' : {
            return '\\subsubsection*{'+tag_content+'}';
        }

        case 'span' :  {
            if(tag_class == 'nl'){
                return "\\\\";
            }
            if(tag_class == 'np'){
                return "\\newpage ";
            }
        }
    }

    

    debug("error"+tag_name);

}

function preview_correcter($text){
    let errors = new Array();



    return errors;
}

/**
 * Convert to latex from editor
 */
function converter_to_latex(){
    let editor = document.getElementsByClassName("editor-input")[0];
    let content = "";

    if(editor.tagName != "DIV"){
        content = markdown_to_preview(editor.value);
    }else{
        content = editor.innerHTML;
    }

    let errors = preview_correcter(content);
    console.log(errors);
    if(!errors.length){
        return ['<h3>Title</h3><p>Content</p>'];
    };

    let latex = content.replace(/<br>/gmi,'');
    latex = latex.replace(preview_regex,preview_to_latex);


    return latex;
}