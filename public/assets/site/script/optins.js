export default function initOptins() {
    let form_root = document.forms['newsletterSettingsForm'];
    if (!form_root) {
        form_root = document.forms['newAccountForm'];
    }
    if (!form_root) {
        // light login flow
        form_root = document.forms['accountOptinForm'];
    }

    if (!form_root) {
        return false;
    }

    let general_newsletters_opt = form_root.elements['newsletterOptinCheckbox'];
    let newsletters_list = form_root.elements['mailinglistsFieldSet'];
    let blocklist_opt = form_root.elements['blockAll'];
    let optin_group = form_root.elements['optinsFieldSet'];

    // fold in/out the list of newsletters on change ...
    if (general_newsletters_opt && newsletters_list) {
        general_newsletters_opt.addEventListener('change', function() {
            if (newsletters_list) {
                if (general_newsletters_opt.checked) {
                    newsletters_list.style.display = null;
                } else {
                    newsletters_list.style.display = 'none';
                }
            }
        });
    }

    if (blocklist_opt && optin_group) {
        blocklist_opt.addEventListener('change', function() {
            optin_group.classList.toggle('unavailable');
            if (blocklist_opt.checked) {
                optin_group.setAttribute('disabled', 'disabled');
            } else {
                optin_group.removeAttribute('disabled');
            }
        });
    }

    form_root.querySelectorAll('input').forEach(input => {
        input.addEventListener('change', () => {
            form_root.querySelectorAll('[data-save-and-continue-label]').forEach(button => {
                button.innerText = button.dataset.saveAndContinueLabel;
            });
        });
    });
}
