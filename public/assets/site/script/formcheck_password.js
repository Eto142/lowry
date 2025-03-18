import { Password } from '@module/password';

export default function initFormCheckPassword(){
    let form = document.forms.passwordForm;
    let new_pw;
    let new_pw_check;
    let submit_button;

    if (form) {
        new_pw = form.elements['nieuwwachtwoord'];
        new_pw_check = form.elements['nieuwWachtwoordVerificatie'];
        submit_button = form.elements['passwordFormSubmit'];
        if (!submit_button) {
            submit_button = form.querySelector('[type="submit"]');
        }

    } else {
        form = document.forms.password_form;
        if (form) {
            new_pw = form.elements['password_form_password_first'];
            new_pw_check = form.elements['password_form_password_second'];
            submit_button = form.elements['password_form_save'];
        }
    }

    if (! form) {
        return;
    }

    // only modify submit_button disabled state if it's not handled by recaptcha already
    if (submit_button.getAttribute('data-p-recaptcha') === null) {
        submit_button.setAttribute('disabled', 'true');
    }

    new Password(
        Number(form.dataset.minStrength),
        new_pw,
        new_pw_check,
        form.dataset.warningLength,
        form.dataset.warningMismatch,
        form.dataset.warningStrength
    );

    const validate = function() {
        if (new_pw.checkValidity() && new_pw_check.checkValidity() && submit_button.getAttribute('data-p-recaptcha') === null) {
            submit_button.removeAttribute('disabled');
            if (submit_button.nextElementSibling.href.indexOf('skipPassword') > -1) {
                submit_button.nextElementSibling.classList.remove('btn-active');
                submit_button.nextElementSibling.classList.add('btn-default');
            }
        }
    };

    new_pw.addEventListener('keyup', validate);
    new_pw.addEventListener('change', validate);

    new_pw_check.addEventListener('keyup', validate);
    new_pw_check.addEventListener('change', validate);
}
