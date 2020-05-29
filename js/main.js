let editor = document.getElementsByClassName('editor-input')[0];

const it_regex = /\*(.+)\*/gmi ;

const regex = { '<strong>$1</strong><span>\u00A0</span>' :   /\*\*(.+)\*\* */,
                '<em>$1</em><span>\u00A0</span>'  :   /\*(.+)\* */gmi,
                '<h3>$1</h3></' : /\#(.+)<\//
                }

function updateMarkdown(){
    let content = editor.innerHTML;
    for (const regex_type in regex) {
        content = content.replace(regex[regex_type],regex_type);
    } 

    console.log(editor.children);

    console.log(content);
    editor.innerHTML = content;

    strong(editor);
}

editor.addEventListener('blur',function(e){
    console.log(editor.innerHTML);
    updateMarkdown();  
});


updateMarkdown();



function strong(element){
    if(element.tagName == "STRONG"){
        element.addEventListener("click",function(){
            let newElement = document.createTextNode("**"+element.innerHTML+"**");
            element.parentNode.removeChild(element.nextSibling);
            element.parentNode.replaceChild(newElement,element);

        });
    }else if(element.tagName == "EM"){
        element.addEventListener("click",function(){
            let newElement = document.createTextNode("*"+element.innerHTML+"*");
            element.parentNode.removeChild(element.nextSibling);
            element.parentNode.replaceChild(newElement,element);

        });
    }else{
        for(let child of element.children){
            strong(child);
        }
    }
}