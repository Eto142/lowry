function isNederland(input) {
    if (
        input.value === 'NL'
        || input.value === 'NLD'
        || input.value === 'Nederland'
        || input.selectedOptions[0].innerText.toLowerCase() === 'nederland'
    ) {
        return true;
    }

    return false;
}

function splitAddress(address) {
    // split an address into number and suffix
    // cendris doesn't use the suffix
    // everything from the first character that isn't a number goes into the suffix
    // returns just the number

    address = address.trim();

    let split_point = address.search(/\d\D/);

    if (split_point > -1) {
        return address.substring(0, split_point + 1).trim();
    } else {
        return address.trim();
    }
}

function triggers(input) {
    // ensures state updates, validations are triggered
    const change_event = new Event('change', { bubbles: true });
    const input_event = new Event('input', { bubbles: true });
    input.dispatchEvent(input_event);
    input.dispatchEvent(change_event);
}

function handleCendris(form) {
    let response_timer;
    let keyup_timer;
    let done = null;
    let initial_postcode = form.elements['detail[postcode]'].value;
    let initial_huisnummer = form.elements['detail[housenumber]'].value;

    const message_container = document.getElementById('messageCendris');
    const submit_button = form.querySelector('[type="submit"]');

    function enableCendris() {
        message_container.style.display = 'none';
        form.elements['detail[street]'].setAttribute('readonly', true);
        form.elements['detail[city]'].setAttribute('readonly', true);
        form.elements['detail[postcode]'].setAttribute('pattern', '[0-9]{4}\\s*[a-zA-Z]{2}');
    }

    function disableCendris() {
        message_container.style.display = 'none';
        form.elements['detail[street]'].removeAttribute('readonly', true);
        form.elements['detail[city]'].removeAttribute('readonly', true);
        form.elements['detail[postcode]'].removeAttribute('pattern');
    }

    function callCendris(){
        if (isNederland(form.elements['detail[country]'])) {
            enableCendris();
        }

        // we passed here before, but apparently the call isn't done yet
        if (done === false) {
            return false;
        }

        // cendris is NL only
        if (! isNederland(form.elements['detail[country]'])) {
            disableCendris();
            return false;
        }

        // we need proper postcode and number
        const regex = new RegExp(form.elements['detail[postcode]'].pattern);
        if (! regex.test(form.elements['detail[postcode]'].value) || form.elements['detail[housenumber]'].value.length < 1) {
            return false;
        }

        // and there needs to be new information
        if (form.elements['detail[postcode]'].value === initial_postcode && form.elements['detail[housenumber]'].value === initial_huisnummer) {
            return false;
        }

        submit_button.setAttribute('disabled', true);
        done = false;
        window.clearTimeout(response_timer);

        // give cendris 1 second to respond before we warn the user it might take a while
        response_timer = setTimeout(function(){
            if (!done) {
                message_container.querySelector('.placeholder').innerText = form.dataset.messageLoading;
                message_container.style.display = null;
            }
        }, 1000);

        // do cendris post
        $.ajax({
            type: 'POST',
            url: form.dataset.cendrisEndpoint,
            data: {
                'postcode': form.elements['detail[postcode]'].value,
                'huisnummer': splitAddress(form.elements['detail[housenumber]'].value),
                'land': form.elements['detail[country]'].value
            },
            dataType: 'json',
            error: function() {
                // the call is not succesfull and maybe cendris doesn't know the address
                done = true;
                disableCendris();
                form.elements['detail[street]'].value = '';
                form.elements['detail[city]'].value = '';

                triggers(form.elements['detail[street]']);
                triggers(form.elements['detail[city]']);

                message_container.querySelector('.placeholder').innerText = form.dataset.messageNotfound;
                message_container.style.display = null;
                submit_button.removeAttribute('disabled');
            },
            success: function(data) {
                done = true;
                // the call is succesfull but cendris doesn't know the address
                if (
                    ! data
                    || data.straat.toLowerCase() === 'onbekend'
                    || data.woonplaats.toLowerCase() === 'onbekend'
                    || data.straat.toLowerCase() === ''
                    || data.woonplaats.toLowerCase() === ''
                ) {
                    disableCendris();
                    form.elements['detail[street]'].value = '';
                    form.elements['detail[city]'].value = '';

                    message_container.querySelector('.placeholder').innerText = form.dataset.messageNotfound;
                    message_container.style.display = null;
                    submit_button.removeAttribute('disabled');
                } else {
                    enableCendris();
                    form.elements['detail[street]'].value = data.straat;
                    form.elements['detail[city]'].value = data.woonplaats;

                    initial_postcode = form.elements['detail[postcode]'].value;
                    initial_huisnummer = form.elements['detail[housenumber]'].value;
                    submit_button.removeAttribute('disabled');
                }

                triggers(form.elements['detail[street]']);
                triggers(form.elements['detail[city]']);
            }
        });
    }

    [
        'detail[country]',
        'detail[postcode]',
        'detail[housenumber]'
    ].forEach(function(name) {
        form.elements[name].addEventListener('change', function() {
            callCendris();
        });
        form.elements[name].addEventListener('keyup', function() {
            window.clearTimeout(keyup_timer);
            keyup_timer = window.setTimeout(function(){
                callCendris();
            }, 1000);
        });
    });

    if (isNederland(form.elements['detail[country]'])) {
        enableCendris();
    }
}

export default function initCendris() {
    document.querySelectorAll('form[data-cendris="true"]').forEach(function(form) {
        // enable cendris post code checking features if applicable
        handleCendris(form);
    });
}
