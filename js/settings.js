let date_input = document.getElementById('el_settings_date');
let date_auto = document.getElementById('el_settings_date_auto');

date_auto.onchange = window.onload = () => {
    if(date_auto.checked){
        date_input.setAttribute('disabled','');
    }else{
        date_input.removeAttribute('disabled');
    }
}