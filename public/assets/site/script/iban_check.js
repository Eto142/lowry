import IBAN from 'iban';

export default function initIBANValidation() {
    let iban_input = document.querySelector('[name="detail[iban]"], [name="cancellation_iban"]');

    if (iban_input) {
        let validation_msg = iban_input.form.querySelector('[name="detail[iban]"] ~ .validationMsg, [name="cancellation_iban"] ~ .validationMsg');

        iban_input.addEventListener('change', function() {
            if (!iban_input.getAttribute('required') && iban_input.value === '') {
                iban_input.classList.remove('checked-invalid');
                iban_input.setCustomValidity('');
            } else if (IBAN.isValid(iban_input.value)) {
                iban_input.classList.remove('checked-invalid');
                iban_input.setCustomValidity('');
            } else {
                iban_input.classList.add('checked-invalid');
                if (validation_msg) {
                    iban_input.setCustomValidity(validation_msg.innerText);
                }
            }
        });
    }
}
