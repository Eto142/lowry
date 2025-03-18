export default function initLoginPassword() {
    let button = document.getElementById('login_save');
    if (!button) {
        return;
    }

    let pw_input = button.form.elements['login_password'];

    function validate() {
        let autocompleted = false;
        document.querySelectorAll('input:-webkit-autofill').forEach(function(node){
            if (node === pw_input) {
                autocompleted = true;
            }
        });

        if (autocompleted || pw_input.value) {
            button.removeAttribute('disabled');

            document.forms.magic_link_form.querySelector('button').classList.add('btn-default');
        } else {
            button.setAttribute('disabled', true);

            document.forms.magic_link_form.querySelector('button').classList.remove('btn-default');
        }
    }

    validate();

    pw_input.addEventListener('keyup', function(){
        validate();
    });
    pw_input.addEventListener('input', function(){
        validate();
    });
    window.setInterval(function(){
        validate();
    }, 1000);
}
