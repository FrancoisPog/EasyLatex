// Regex
const markup_regex    = /(\[(.):(.*?):(\2)\]|(\#{1,3}\*?) *(.+?) *(<br>|$))/gmi;
const preview_regex     = /<(.*?)( class="(.*?)")?>[0-9\. ]*(.*?)<\/(\1)>/gmi;

var title_count = 0;
var subtitle_count = 0;

/**
 * Parse the markup recursively to preview html code
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
function markup_to_preview_rec(match,no_used,text_type,text_content,no_used,title_type,title_content,offset,og){
    // Title case
    if(typeof(text_content) == "undefined"){
        if(title_content.match(markup_regex)){
            title_content = title_content.replace(markup_regex,markup_to_preview_rec);
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
    if(text_content.match(markup_regex)){
        text_content = text_content.replace(markup_regex,markup_to_preview_rec);
    }

    if(text_type == 'i'){
        return '<em>'+text_content+'</em>';
    }

    if(text_type == 'b'){
        return '<strong>'+text_content+'</strong>';
    }

    return match;   
    
}

/**
 * Parse a markup text to html preview
 * @param {String} text The text to parse
 */
function markup_to_preview(text){
    title_count = 0;
    subtitle_count = 0;
    single_tags_regex = {   
                            '<span class="nl"><br></span>' : /\[:nl:\]/gmi,
                            '<span class="np"><br>--<br></span>' : /\[:np:\]/gmi,
                            '<span class="par">&nbsp;&nbsp;</span>' : /^\[:par:\]/gmi,
                            'h$1\><span class="par">&nbsp;&nbsp;</span>' : /h([1-6])\> *\[:par:\]/gmi,
                            '<span class="par"><br><br>&nbsp;&nbsp;</span>' : /\[:par:\]/gmi
                        }

    content =  text.replace(/\</gmi,'&lt;').replace(/\>/gmi,'&gt;').replace(/\n/gmi,'<br>').replace(markup_regex,markup_to_preview_rec).replace(/<br>/gmi,' ');

    for(let tag in single_tags_regex){
        content = content.replace(single_tags_regex[tag],tag);
    }

    return content;
}

/**
 * Get the markup code from editor and parse it for the preview
 */
function converter_to_preview(){
    let editor = get_editor_input();
    let content = editor.value;

    markup_svg = content;
    
    content = markup_to_preview(content);
    
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
    if(tag_content.match(preview_regex)){
        tag_content = tag_content.replace(preview_regex,preview_to_latex);
    }

    switch(tag_name){
        case 'em': 
            return '\\textit{'+tag_content+'}';     
        case 'strong' : 
            return '\\textbf{'+tag_content+'}';
        case 'h1' : 
            return '\\section{'+tag_content+'}';   
        case 'h2' : 
            return '\\section*{'+tag_content+'}';
        case 'h3' : 
            return '\\subsection{'+tag_content+'}';
        case 'h4' : 
            return '\\subsection*{'+tag_content+'}';
        case 'h5' : 
            return '\\subsubsection*{'+tag_content+'}';
        case 'h6' : 
            return '\\subsubsection*{'+tag_content+'}';
        case 'span' :  {
            switch (tag_class) {
                case 'nl':
                    return "\\leavevmode \\\\";            
                case 'np':
                    return "\\newpage ";
                case 'par' :
                    return '\\paragraph{}'; 
            }
        }
        default : {
            return match;
        }
    }
}

/**
 * markup correcter
 * @param {String} text The text to correct
 */
function markup_correcter(text){
    let errors = new Array();

    text = text.replace(/\n/gmi,' ').replace(/\</gmi,'&lt;').replace(/\>/gmi,'&gt;');

    invalid_chars_errors = [...text.matchAll(/.{0,50}(\\).{0,50}/gmi)];

    for(let match of invalid_chars_errors){
        match[0] = match[0].replace(/(\\)/gmi,'<span class="invalid_char">$1</span>').replace(/<br>/gmi,'');
        errors.push('<h3>Invalid character</h3><p>... '+match[0]+' ...</p>');
    }

    double_np_errors = [...text.matchAll(/.{0,50}(\[:np:\]){2,}.{0,50}/gmi)];
   
    for(let match of double_np_errors){
        match[0] = match[0].replace(/(\[:np:\])/gmi,'<span class="invalid_char">[:np:]</span>');
        errors.push('<h3>Too many successive new pages</h3><p>... '+match[0]+' ...</p>');
    }

    empty_tags_errors = [...text.matchAll(/.{0,50}\[(i|b): *:\1\].{0,50}/gmi)];

    for(let match of empty_tags_errors){
        match[0] = match[0].replace(/\[(i|b):( *):\1\]/gmi,'<span class="invalid_char">[$1:$2:$1]</span>');
        errors.push('<h3>Empty tags</h3><p>... '+match[0]+' ...</p>');
    }

    return errors;
}

/**
 * Escape the latex specials characters
 * @param {String} text The text to escape
 */
function escape_special_chars(text){
    return text.replace(/(\$|\{|\}|\&|\%)/gmi,'\\\$1');
}

/**
 * Convert to latex from editor
 */
function converter_to_latex(){
    let editor = get_editor_input();
    let content = "";
    if(editor.tagName != "DIV"){
        content = markup_to_preview(editor.value);
        markup_svg = editor.value;
    }else{
        content = editor.innerHTML;
    }
    
    let errors = markup_correcter(markup_svg);
     
    if(errors.length){
        return errors;
    };

    content = escape_special_chars(content);

    let latex = content.replace(/<br>/gmi,'');

    return latex.replace(preview_regex,preview_to_latex).replace(/\\\&lt;/gmi,'<').replace(/\\\&gt;/gmi,'>');
}
