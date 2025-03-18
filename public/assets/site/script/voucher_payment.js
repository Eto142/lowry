export default function initVoucherPayment() {
    const form = document.forms.voucherForm;

    if (! form) {
        return;
    }

    const input = form.querySelector('#voucher-code-field');
    const amount = form.querySelector('#voucher-amount-input');
    let voucherValid = false;

    form.onsubmit = function(event) {

        form.classList.add('is-submitted');
        form.querySelectorAll('button').forEach(function(button){
            button.setAttribute('disabled', true);
        });
        form.querySelector('#voucher-error').style.display = 'none';
        form.querySelector('#submit-spinner').style.display = null;

        if (voucherValid) {
            // if the code is validated, we can submit the form to the backend
            return;
        }

        event.preventDefault();
        event.stopPropagation();


        let form_data = {
            'strVoucherCode': input.value,
            'additionalFields': {}
        };

        form.querySelectorAll('.additional-field').forEach(function(field){
            const field_data = JSON.parse(field.dataset.additionalField);
            form_data['additionalFields'][field_data['id']] = field.value;
        });

        $.ajax({
            type: 'POST',
            url: form.dataset.endpoint,
            data: form_data,
            dataType: 'json',
            success: function(data){
                form.classList.remove('is-submitted');
                form.querySelectorAll('button').forEach(function(button){
                    button.removeAttribute('disabled');
                });
                form.querySelector('#voucher-error').style.display = null;
                form.querySelector('#submit-spinner').style.display = 'none';

                amount.value = data.suggestedAmount;
                amount.max = data.suggestedAmount;

                form.querySelector('#voucher-message').innerText = data.message;
                form.querySelector('#voucher-validation').value = data.hash;
                form.querySelector('#voucher-info').value = data.additional_info;

                if (data.blnValidVoucher === false) {
                    form.querySelector('#voucher-amount-due').style.display = 'none';
                    form.querySelector('#voucher-error').style.display = null;
                    input.removeAttribute('readonly');
                    input.focus();

                    return;
                }

                voucherValid = true;
                form.querySelector('#voucher-error').style.display = 'none';
                input.setAttribute('readonly', 'true');
                form.querySelector('#voucher-amount-due').style.display = null;

                if (data.blnIsReadOnly === true) {
                    amount.setAttribute('readonly', 'true');
                    form.querySelector('#voucher-amount-description').style.display = 'none';

                    return;
                }

                amount.focus();
            },
            error: function(jqXHR, textStatus, errorThrown){
                // eslint-disable-next-line no-console
                console.error(jqXHR);
                // eslint-disable-next-line no-console
                console.error(textStatus + '  ' + errorThrown);
            }
        });
    };
}
