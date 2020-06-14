// --- SIGN UP ---
let form = document.getElementById('signup-form');
if(form != null){
    var username = form.elements.el_signup_username;
    var firstname = form.elements.el_signup_firstname;
    var lastname = form.elements.el_signup_lastname;
    var password = form.elements.el_signup_password;
    var passwordRepeat = form.elements.el_signup_passwordRepeat;
    var submit = form.elements.el_signup;

    firstname.oninput = () => {
        setValidity(firstname,firstname.value.match(/^[^<>]{1,50}$/),'The first name must contains less than 50 characters, without HTML tags');
        signupFormValidity();
    }

    lastname.oninput = () => {
        setValidity(lastname,lastname.value.match(/^[^<>]{1,50}$/),'The last name must contains less than 50 characters, without HTML tags');
        signupFormValidity();
    }

    username.oninput = () => {
        setValidity(username,username.value.match(/^[0-9a-zA-Z]{6,20}$/),'The username must contains between 6 and 20 letters and digits');
        signupFormValidity();
    }

    password.oninput = () => {
        
        setValidity(passwordRepeat,passwordRepeat.value == password.value,'The two passwords don\'t match',false);
        setValidity(password,password.value.length,'The password can\'t be empty');
        signupFormValidity();
    }

    passwordRepeat.oninput = () => {
        setValidity(passwordRepeat,passwordRepeat.value == password.value,'The two passwords don\'t match');
        signupFormValidity();
    }
}

// --- NEW PROJECT --- 

let new_project_input = document.getElementsByName('el_newproject_name');

if(new_project_input.length){
    new_project_input = new_project_input[0];

    new_project_input.oninput = () => {
        setValidity(new_project_input,new_project_input.value.match(/^[^<>]{1,30}$/),'The project name must contains between 4 and 30 characters without HTML tags');
        let btn_submit = document.getElementsByName('el_newproject')[0];
        
        if(new_project_input.parentNode.classList.contains('tooltip')){
            btn_submit.setAttribute('disabled','');
        }else{
            btn_submit.removeAttribute('disabled');
        }
    }
}


// --- SETTINGS ---




// --- FUNCTIONS ---

/**
 * Update the submit button state
 */
function signupFormValidity(){
    for(let elt of [firstname,lastname,username,password,passwordRepeat]){
        if(elt.parentNode.classList.contains('tooltip') || elt.value.length == 0){
            submit.setAttribute('disabled','');
            return;
        }
    }
    submit.removeAttribute('disabled');
    return ;
}

function settingsFormValidity(){

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