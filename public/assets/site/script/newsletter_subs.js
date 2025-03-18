/* global grecaptcha, dataLayer */

import { Modal } from '@module/modal';

export default function initNewsletterSubs() {

    const modal = document.getElementById('nbSubscribe');

    if (modal) {
        const modalHandling = new Modal();
        modalHandling.setClickHandlers('[aria-controls="nbSubscribe"]');
    }

    document.querySelectorAll('.newsletter-subscribe-form').forEach(form => {
        let success = true;

        function handle(recaptcha_key) {
            const payload = new FormData(form);

            if (recaptcha_key) {
                // key is not in the form
                payload.append('g-recaptcha-response', recaptcha_key);
            }

            form.querySelectorAll('.msg').forEach(message => {
                message.style.display = 'none';
            });

            fetch(form.getAttribute('action'), {
                method: 'POST',
                body: payload
            }).then(response => {
                if (!response.ok) {
                    form.querySelector('.msg.error').style.display = null;
                    success = false;
                }
                return response.json();
            }).then(data => {
                if (data.message) {
                    // something wrong, server tells us what's up
                    form.querySelectorAll('.msg').forEach(message => {
                        message.style.display = 'none';
                    });
                    form.querySelector('.msg.error.placeholder').style.display = null;
                    form.querySelector('.msg.error.placeholder .message').innerText = data.message;
                    return;
                }

                if (!success) {
                    return;
                }

                form.querySelectorAll('label, input, button, submit').forEach(el => {
                    el.style.display = 'none';
                });

                if (data.exists) {
                    // you're already subbed
                    form.querySelector('.msg.notice').style.display = null;
                    return;
                }

                form.classList.add('success');
                form.querySelector('.msg.success').style.display = null;
            });
        }

        form.onsubmit = function(e) {
            e.preventDefault();
            e.stopPropagation();

            dataLayer.push({
                'event': 'form_submit',
                'eventCategory': 'newsletter_subscribe'
            });

            const recaptcha_key = form.dataset.recaptchaKey;

            if (recaptcha_key) {
                // we're using recaptcha v3
                grecaptcha.ready(function() {
                    grecaptcha.execute(recaptcha_key, {action: 'submit'}).then(function(token) {
                        handle(token);
                    });
                });
            } else {
                handle();
            }
        };
    });
}
