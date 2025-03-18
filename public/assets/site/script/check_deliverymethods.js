import Cookies from 'js-cookie';

export default function checkDeliveryMethods() {
    /*
        for the selected delivery option, check with the backend for
        relevant messages, and maybe disable the "continue" button
        e.g. when some address info is missing

        also track the user's preferred method and ensure at least one method is checked
    */

    const delivery_options = document.querySelectorAll('[data-toggle=deliveryFeedback]');
    const feedback = document.getElementById('deliveryFeedback');

    if (! delivery_options || ! feedback) {
        return;
    }

    const button = feedback.querySelector('a');
    const button_default = button.href;
    const button_shipping = button.dataset.shippingHref;
    const preferred_method = Cookies.get('order_shipment_preference');

    function cantContinue(submit_button, payment_options) {
        feedback.style.display = null;

        if (payment_options) {
            payment_options.setAttribute('disabled', 'true');
        }
        if (submit_button) {
            submit_button.setAttribute('disabled', 'true');
        }
    }

    async function updateCosts(method) {
        try {
            const costs_root = document.querySelector('[data-amount-type]').closest('table');
            costs_root.classList.add('loading');
            costs_root.querySelector('[data-amount-type="remaining"]').innerHTML = '&hellip;';

            const response = await fetch(`/order/set-delivery-method/${method}`);
            if (!response.ok) {
                throw new Error(response.status);
            }

            const data = await response.json();
            const hide_subtotal = data.subtotal.raw === data.remaining.raw;

            Object.entries(data).forEach(([key, value]) => {
                costs_root.querySelectorAll(`[data-amount-type=${key}]`).forEach((amount) => {
                    const row = amount.closest('tr');
                    if (!row) {
                        return;
                    }

                    amount.innerHTML = value.formatted;

                    if (key === 'subtotal' && hide_subtotal) {
                        row.classList.add('hidden');
                    } else if (value['raw'] > 0) {
                        row.classList.remove('hidden');
                    } else {
                        row.classList.add('hidden');
                    }
                });
            });

            costs_root.classList.remove('loading');
        } catch(err) {
            throw new Error(err.status);
        }
    }

    function validateOption(option_element) {
        const value = option_element.value;
        const payment_options = document.getElementById('paymethods');
        const submit_button = document.getElementById('selectPayment');

        Cookies.set('order_shipment_preference', option_element.value);
        updateCosts(value);

        button.href = button_default;

        if (value === 'shipping' && ! feedback.dataset.validForShipping) {
            button.href = button_shipping;
            cantContinue(submit_button, payment_options);
            return;
        }

        if (! feedback.dataset.validForEtickets) {
            cantContinue(submit_button, payment_options);
            return;
        }

        if (payment_options) {
            payment_options.removeAttribute('disabled');
        }
        if (submit_button) {
            submit_button.removeAttribute('disabled');
        }
        feedback.style.display = 'none';
    }

    if (preferred_method) {
        const option_element = document.querySelector('[data-toggle=deliveryFeedback][value="' + preferred_method + '"]');
        if (option_element) {
            option_element.checked = true;
        } else {
            // the option is missing, let's reset the preference
            Cookies.remove('order_shipment_preference');
        }
    } else if (delivery_options[0]) {
        delivery_options[0].checked = true;
    }

    delivery_options.forEach(option => {
        if (option.checked && option.value !== 'e-ticket') {
            validateOption(option);
        }

        option.addEventListener('click', function(){
            validateOption(option);
        });
    });
}
