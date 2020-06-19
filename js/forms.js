// --- SIGN UP ---
let signup_form = document.getElementById('signup-form');
if(signup_form != null){
    let signup_username = signup_form.elements.el_signup_username;
    let signup_firstname = signup_form.elements.el_signup_firstname;
    let signup_lastname = signup_form.elements.el_signup_lastname;
    let signup_password = signup_form.elements.el_signup_password;
    let signup_passwordRepeat = signup_form.elements.el_signup_passwordRepeat;
    let signup_submit = signup_form.elements.el_signup;
    let signup_elements = [signup_firstname,signup_lastname,signup_username,signup_password,signup_passwordRepeat];

    signup_firstname.oninput = () => {
        setValidity(signup_firstname,signup_firstname.value.match(/^[^<>]{1,50}$/),'The first name must contains less than 50 characters, without HTML tags');
        formValidity(signup_elements,signup_submit);
    }

    signup_lastname.oninput = () => {
        setValidity(signup_lastname,signup_lastname.value.match(/^[^<>]{1,50}$/),'The last name must contains less than 50 characters, without HTML tags');
        formValidity(signup_elements,signup_submit);
    }

    signup_username.oninput = () => {
        setValidity(signup_username,signup_username.value.match(/^[0-9a-zA-Z]{6,20}$/),'The username must contains between 6 and 20 letters and digits');
        formValidity(signup_elements,signup_submit);
    }

    signup_password.oninput = () => {
        
        setValidity(signup_passwordRepeat,signup_passwordRepeat.value == signup_password.value,'The two passwords don\'t match',false);
        setValidity(signup_password,signup_password.value.length,'The password can\'t be empty');
        formValidity(signup_elements,signup_submit);
    }

    signup_passwordRepeat.oninput = () => {
        setValidity(signup_passwordRepeat,signup_passwordRepeat.value == signup_password.value,'The two passwords don\'t match');
        formValidity(signup_elements,signup_submit);
    }
}

// --- NEW PROJECT --- 

let new_project_input = document.getElementsByName('el_newproject_name');

if(new_project_input.length){
    new_project_input = new_project_input[0];

    new_project_input.oninput = () => {
        setValidity(new_project_input,new_project_input.value.match(/^[^<>]{1,30}$/),'The project name must contains between 1 and 30 characters without HTML tags');
        let btn_submit = document.getElementsByName('el_newproject')[0];
        
        if(new_project_input.parentNode.classList.contains('tooltip')){
            btn_submit.setAttribute('disabled','');
        }else{
            btn_submit.removeAttribute('disabled');
        }
    }
}


// --- SETTINGS ---

let settings_form = document.getElementById('settings-form');
if(settings_form != null){

    let settings_title = document.getElementsByName('el_settings_title')[0];
    let settings_author = document.getElementsByName('el_settings_author')[0];
    let settings_date = document.getElementsByName('el_settings_date')[0];
    let settings_date_auto = document.getElementsByName('el_settings_date_auto')[0];
    let settings_submit = document.getElementsByName('el_settings')[0];

    for(let element of [settings_title,settings_author,settings_date]){
        element.oninput = () => {
            setValidity(element,element.value.match(/^[^<>\\]{0,100}$/),'This field must contain less than 100 characters and mustn\'t contain html tags or "\\"');
            formValidity([settings_date,settings_title,settings_author],settings_submit,true);
        }
    }

    
    settings_date_auto.oninput = () => {
        if(settings_date_auto.checked){
            setValidity(settings_date,true,'',false);
            formValidity([settings_date,settings_title,settings_author],settings_submit,true);
            
        }else{
            setValidity(settings_date,settings_date.value.match(/^[^<>\\]{0,100}$/),'The date mustn\'t contains html tags or "\\"');
            formValidity([settings_date,settings_title,settings_author],settings_submit,true);
        }
    }


    
}


// --- FUNCTIONS ---

/**
 * Update the submit button state
 */
function formValidity(elements,submit,canBeEmpty = false){
    for(let elt of elements){
        if(elt.parentNode.classList.contains('tooltip') || (elt.value.length == 0 && !canBeEmpty )){
            submit.setAttribute('disabled','');
            return;
        }
    }
    submit.removeAttribute('disabled');
    return ;
}




/**
 * Set the validity of an input
 * @param {Element} input   A form input  
 */
function setValidity(input, isValid, tip = '', focus = true){
    if(isValid){
        remove_tooltip(input);
        input.parentNode.classList.remove('invalid');
    }else{
        input.parentNode.classList.add('invalid');
        add_tooltip(input,tip);
    }
    focus && input.focus();
}
    

/**
 * Add a tooltip to an input
 * @param {Element} input A form input
 * @param {String} content The tooltip content
 */
function add_tooltip(input, content){
    if(input.parentNode.classList.contains('tooltip')){
        return;
    }
    let input_wrapper = input.parentNode;

    let tooltip = document.createElement('div');
    tooltip.classList.add('tooltip');
    tooltip.appendChild(input_wrapper.children[0]);
    tooltip.appendChild(input_wrapper.children[0]);

    let tip = document.createElement('span');
    tip.classList.add('tooltip-tip');
    tip.textContent = content;

    tooltip.insertAdjacentElement('afterbegin',tip);

    input_wrapper.appendChild(tooltip);

}

/**
 * Remove a tooltip from an input
 * @param {Element} input A form input
 */
function remove_tooltip(input){
    let tooltip = input.parentNode;
    if(!tooltip.classList.contains('tooltip')){
        return;
    }
    let input_wrapper = tooltip.parentNode;
    tooltip.removeChild(tooltip.firstChild);

    input_wrapper.appendChild(tooltip.children[0]);
    input_wrapper.appendChild(tooltip.children[0]);

    input_wrapper.removeChild(input_wrapper.firstChild);

}