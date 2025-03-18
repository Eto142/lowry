// Various interaction and validation scripts for the user details form

/* Telephone number validation */
function initPhoneValidation() {
    let tel_inputs = document.querySelectorAll('[autocomplete="tel"]');

    if (tel_inputs.length < 1) {
        return;
    }

    const validation_msg = tel_inputs[0].form.querySelector('[autocomplete="tel"] ~ .validationMsg');

    if (!validation_msg) {
        return;
    }

    function setInvalid(input) {
        input.classList.add('checked-invalid');
        input.setCustomValidity(validation_msg.innerText);
    }

    function setValid(input) {
        input.classList.remove('checked-invalid');
        input.setCustomValidity('');
    }

    function validate(input) {
        const payload = new FormData();
        payload.append('number', input.value);

        fetch(tel_inputs[0].form.dataset.telnumberEndpoint, {
            method: 'POST',
            body: payload
        }).then((response) => {
            if (!response.ok) {
                // eslint-disable-next-line no-console
                return console.error('Something went wrong during telnumber validation');
            }
            return response.json();
        }).then((data) => {
            if (data.valid) {
                setValid(input);
            } else {
                setInvalid(input);
            }
        });
    }

    tel_inputs.forEach((input) => {
        input.addEventListener('input', function() {
            // reset to clear any old warnings while you're typing,
            // we'll try to catch any errors later
            setValid(input);
        });

        input.addEventListener('change', function() {
            // if it's empty, but not required, it's all good
            if (!input.getAttribute('required') && input.value === '') {
                setValid(input);
                return;
            }
            // if we don't pass the basic length and pattern validation, it's not good
            if (!input.reportValidity()) {
                setInvalid(input);
                return;
            }
            //  it kinda looks like a phone number, let's check if the backend agrees
            validate(input);
        });
    });
}


function trim_text(text) {
    if (typeof text !== 'string') {
        return text;
    }
    return text.
        replace(/(^\s*)|(\s*$)/gi, ''). // removes leading and trailing spaces
        replace(/[ ]{2,}/gi, ' '). // replaces multiple spaces with one space
        replace(/\n +/, '\n'); // Removes spaces after newlines

}

/* UX improvements for the account form */
function initUserDetailsForm() {
    let form = document.querySelector('.mtEditformWrapper form[name="detail"]');
    let keyup_timer;

    if (!form) {
        return;
    }

    let button = form.querySelector('[type="submit"]');
    let submit_bar = document.getElementById('submit-bar');
    function applyScrolled() {
        if (!submit_bar) {
            return;
        }
        if (button.getBoundingClientRect().top < document.documentElement.clientHeight) {
            submit_bar.classList.add('out');
        } else {
            submit_bar.classList.remove('out');
        }
    }

    if (window.location.pathname.indexOf('/my/order/profile') > -1) {
        // if you're here because required fields in order process
        // we aggressively communicate those fields
        form.querySelectorAll(':required').forEach(element => {
            if (!element.checkValidity()) {
                element.classList.add('checked-invalid');
            }
        });
    }

    if (submit_bar) {
        if (button) {
            let proxy_button = document.getElementById('submit-proxy').appendChild(button.cloneNode(true));
            proxy_button.addEventListener('click', function() {
                if (form.reportValidity()) {
                    form.submit();
                }
            });
        }
        // move the bar to the end of the document
        document.body.appendChild(submit_bar);


        window.addEventListener('scroll', function() {
            applyScrolled();
        });
        applyScrolled();
    }

    function reportField(field) {
        let list = document.querySelector('.mandatory-fields-error ul');
        let continue_btn = document.querySelector('.mtEditformWrapper .continue-button');
        if (field.id // an id is set
            && form.querySelector('[for=' + field.id + ']') // we can find a label for this field
            && ! list.querySelector('[data-field=' + field.id + ']') // it's not reported yet
        ) {
            let li = document.createElement('li');
            let label = form.querySelector('[for=' + field.id + ']').innerText.replace('*', '');
            li.setAttribute('data-field', field.id);
            li.innerText = label;
            list.appendChild(li);
        }
        if (Array.from(list.childNodes).length > 0) {
            list.parentNode.classList.remove('hidden');
            if (continue_btn) {
                continue_btn.classList.add('hidden');
            }
        }
    }
    function dismissField(field) {
        let list = document.querySelector('.mandatory-fields-error ul');
        let continue_btn = document.querySelector('.mtEditformWrapper .continue-button');
        if (field.id) {
            let li = list.querySelector('[data-field="' + field.id + '"]');
            if (li) {
                list.removeChild(li);
            }
        }
        if (Array.from(list.childNodes).length < 1) {
            list.parentNode.classList.add('hidden');
            if (continue_btn) {
                continue_btn.classList.remove('hidden');
            }
        }
    }

    Array.from(form.elements).forEach(field => {
        let label = '';

        if (field.id && form.querySelector('[for=' + field.id + ']')) {
            let li = document.createElement('li');
            label = form.querySelector('[for=' + field.id + ']').innerText.replace('*', '');
            li.setAttribute('data-field', field.id);
            li.innerText = label;
        }
        if (field.required && !field.value) {
            reportField(field);
        }
        if (field.required) {
            field.addEventListener('change', () => {
                if (field.value) {
                    dismissField(field);
                } else {
                    reportField(field);
                }
            });
        }

        if (field.getAttribute('type') === 'text') {
            field.addEventListener('focusout', () => {
                field.value = trim_text(field.value);
            });
            field.addEventListener('keyup', () => {
                window.clearTimeout(keyup_timer);
                keyup_timer = window.setTimeout(function() {
                    field.value = trim_text(field.value);
                }, 3000);
            });
            field.addEventListener('change', () => {
                window.clearTimeout(keyup_timer);
                keyup_timer = window.setTimeout(() => {
                    field.value = trim_text(field.value);
                }, 3000);
            });
        }
    });
}

export { initPhoneValidation, initUserDetailsForm };
