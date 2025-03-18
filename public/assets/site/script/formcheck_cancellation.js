import IBAN from 'iban';

/*
Some interaction and checks to make the cancellation form easier to use:
- disable IBAN field if not applicable
- validate IBAN
- ensure at least one option is selected
*/
export default function initFormCheckCancellation() {
    let form = document.forms.cancellationForm;

    if (! form) {
        return;
    }

    let remarks_label = form.querySelector('[for="cf_remarks"]').innerText;
    let iban_input = form.elements['cf_iban'];
    let iban_label;
    let refund_option = form.querySelector('#cf_refund');
    let other_option = form.querySelector('#cf_other');
    let submit_button = form.querySelector('#cf_submit');
    let time_out;

    let validate = function() {
        window.clearTimeout(time_out);
        if (refund_option && refund_option.checked && ! IBAN.isValid(iban_input.value)) {
            submit_button.setAttribute('disabled', 'true');
            time_out = window.setTimeout(function(){
                iban_input.classList.add('invalid');
            }, 1000);

        } else if (other_option && other_option.checked && form.elements['cf_remarks'].value.length < 1) {
            form.elements['cf_remarks'].classList.add('invalid');
            submit_button.setAttribute('disabled', 'true');
            if (iban_input) {
                iban_input.classList.remove('invalid');
            }

        } else {
            submit_button.removeAttribute('disabled');
            form.elements['cf_remarks'].classList.remove('invalid');
            if (iban_input) {
                iban_input.classList.remove('invalid');
            }
        }
    };

    function toggleFields(value) {
        if (value === 'refund') {
            form.querySelector('[for="cf_iban"]').innerText = iban_label + ' *';
            iban_input.removeAttribute('readonly');
        } else {
            form.querySelector('[for="cf_iban"]').innerText = iban_label;
            iban_input.setAttribute('readonly', true);
        }

        if (value === 'other') {
            form.elements['cf_remarks'].setAttribute('required', true);
            form.querySelector('[for="cf_remarks"]').innerText = remarks_label + ' *';
        } else {
            form.elements['cf_remarks'].removeAttribute('required');
            form.querySelector('[for="cf_remarks"]').innerText = remarks_label;
        }
    }

    Array.from(form.elements).forEach(element => {
        if (element.getAttribute('name') === 'cf_option') {
            element.addEventListener('change', function(){
                validate();
                toggleFields(element.value);
            });
        }
    });

    if (iban_input) {
        iban_label = form.querySelector('[for="cf_iban"]').innerText;
        iban_input.onkeyup = validate;
        iban_input.onchange = validate;
    }

    form.elements['cf_remarks'].onkeyup = validate;
    form.elements['cf_remarks'].onchange = validate;

    // the form may be pre-filled so handle the initial state
    if (refund_option && refund_option.checked) {
        toggleFields('refund');
    } else if (other_option && other_option.checked) {
        toggleFields('other');
    } else {
        toggleFields();
    }
}
