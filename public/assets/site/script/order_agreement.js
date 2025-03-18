export default function initOrderAgreement() {
    if (! document.forms.orderForm) {
        return;
    }

    let order_agreement_check = document.getElementById('corona-check');

    if (! order_agreement_check) {
        return;
    }

    let button = document.forms.orderForm.querySelector('[type="submit"]');
    button.setAttribute('disabled', true);

    order_agreement_check.onchange = function() {
        if (order_agreement_check.checked) {
            button.removeAttribute('disabled');
        } else {
            button.setAttribute('disabled', true);
        }
    };
}
