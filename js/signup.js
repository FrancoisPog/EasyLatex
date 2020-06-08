let form = document.getElementById('signup-form');

form.onsubmit = (e) => {
    console.log('ee');
    e.preventDefault();
};


let username = form.elements.el_signup_username;
let firstname = form.elements.el_signup_firstname;
let lastname = form.elements.el_signup_lastname;
let password = form.elements.el_signup_password;
let passwordRepeat = form.elements.el_signup_passwordRepeat;
let submit = form.elements.el_signup;





firstname.onblur = () => {
    setValidity(firstname,firstname.value.match(/^[^<>]{1,50}$/),'The first name must contains less than 50 characters, without HTML tags');
    formValidity();
}

lastname.onblur= () => {
    setValidity(lastname,lastname.value.match(/^[^<>]{1,50}$/),'The last name must contains less than 50 characters, without HTML tags');
    formValidity();
}

username.onblur = () => {
    setValidity(username,username.value.match(/^[0-9a-zA-Z]{6,20}$/),'The username must contains between 6 and 20 letters and digits');
    formValidity();
}

password.onblur = () => {
    setValidity(password,password.value.length > 0,'The password can\'t be empty');
    formValidity();
}

passwordRepeat.onblur = () => {
    setValidity(passwordRepeat,passwordRepeat.value == password.value,'The two passwords don`t match');
    formValidity();
}



function formValidity(){
    for(let elt of [firstname,lastname,username,password,passwordRepeat]){
        if(elt.parentNode.classList.contains('tooltip') || elt.value.length == 0){
            submit.setAttribute('disabled','');
            return;
        }
    }
    submit.removeAttribute('disabled');
    return ;
}


/**
 * 
 * @param {Element} element 
 */
function setValidity(element, isValid, tip = ''){
    if(isValid){
        remove_tooltip(element);
        element.parentNode.classList.remove('invalid');
    }else{
        element.parentNode.classList.add('invalid');
        add_tooltip(element,tip);
    }
}
    

/**
 * 
 * @param {Element} input 
 * @param {String} content 
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
 * 
 * @param {Element} input 
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