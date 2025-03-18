/* global grecaptcha, pep_global_config */

export default function initRecaptcha() {
    window.addEventListener('load', () => {
        if (typeof pep_global_config !== 'object') {
            return;
        }

        if (! pep_global_config['recaptcha']) {
            return;
        }

        const conf = pep_global_config['recaptcha'];
        const buttons = Array.from(document.querySelectorAll('[data-p-recaptcha]'));
        if (buttons.length < 1) {
            return;
        }

        let appended = false;
        let observer;

        const setRecaptcha3 = () => {
            grecaptcha.ready(function() {
                grecaptcha.execute(conf['key'], {action:'validate_captcha'}).then(function(token) {
                    buttons.forEach(function(button){
                        const token_input = document.createElement('input');
                        token_input.setAttribute('type', 'hidden');
                        token_input.setAttribute('name', 'g-recaptcha-response');
                        token_input.value = token;
                        button.parentNode.appendChild(token_input);

                        // add branding so we can hide the overlay label
                        // https://developers.google.com/recaptcha/docs/faq#id-like-to-hide-the-recaptcha-badge.-what-is-allowed
                        const branding = document.createElement('div');
                        branding.classList.add('grecaptcha-branding');
                        branding.innerHTML = conf['branding'];
                        button.parentNode.appendChild(branding);
                    });
                });
            });
        };

        const handleObserver = (entries) => {
            entries.forEach(entry => {
                if (entry.intersectionRatio > 0 && !appended) {
                    const script = document.createElement('script');
                    script.setAttribute('src', 'https://www.google.com/recaptcha/api.js?render=' + conf['key']);
                    script.setAttribute('async', 'true');
                    script.setAttribute('defer', 'true');
                    script.onload = setRecaptcha3;

                    document.head.appendChild(script);

                    // record that the script has been appended and we can stop observing
                    appended = true;
                    observer.disconnect();
                }
            });
        };

        if (conf['version'] === '3') {
            // https://developer.mozilla.org/en-US/docs/Web/API/Intersection_Observer_API
            observer = new IntersectionObserver(handleObserver);
            buttons.forEach(button => {
                if (button.form) {
                    observer.observe(button.form);
                }
            });

        } else {
            // prep the buttons
            buttons.forEach(button => {
                const container = button.form.querySelector('.g-recaptcha');
                if (! container) {
                    // if not present, place the container for the recaptcha before the submit button with the data-p-recaptcha attribute
                    const el = document.createElement('div');
                    el.classList.add('g-recaptcha');
                    button.parentNode.insertBefore(el, button);
                }

                button.setAttribute('disabled', 'true');
            });

            // render the recaptcha element after loading the api
            // and set up the callback to run after completing the captcha
            window.recaptchaCallback = () => {
                document.querySelectorAll('.g-recaptcha').forEach(container => {
                    grecaptcha.render(container, {
                        'sitekey' : conf['key'],
                        'theme' : 'light',
                        'callback' : () => {
                            container.closest('form').querySelector('[data-p-recaptcha]').removeAttribute('disabled');
                        }
                    });
                });
            };

            const script = document.createElement('script');
            script.setAttribute('async', 'true');
            script.setAttribute('defer', 'true');
            script.setAttribute(
                'src',
                'https://www.google.com/recaptcha/api.js?hl='
                + conf['language']
                + '&onload=recaptchaCallback&render=explicit'
            );
            document.head.appendChild(script);
        }
    });
}
