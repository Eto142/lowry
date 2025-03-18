/*
Some interaction and checks to make the email-change form easier to use:
- select the contents to invite you to replace
- only enable the submit if it looks like an email address
- and if you actually changed your email address
*/
export default function initFormCheckEmail(){
    let form = document.forms.emailForm;

    if (! form) {
        return;
    }

    const current_value = form.dataset.current;
    form.addEventListener('submit', (event) => {
        if (form.elements['email'].value === current_value) {
            event.preventDefault();
            event.stopPropagation();
            window.location.reload();
        }
    });
}
