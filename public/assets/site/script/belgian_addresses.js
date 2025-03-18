import TomSelect from '@node/tom-select/dist/esm/tom-select.js';

let city_input;
let combi_input;
let country_input;
let fancy_select;
let postal_code_input;
let table = [];

function syncAndTrigger() {
    // ensures state updates, validations are triggered
    const pc_change_event = new Event('change', { bubbles: true });
    const pc_input_event = new Event('input', { bubbles: true });
    const city_change_event = new Event('change', { bubbles: true });
    const city_input_event = new Event('input', { bubbles: true });

    postal_code_input.value = combi_input.selectedOptions[0].value;
    city_input.value = combi_input.selectedOptions[0].dataset.city;

    postal_code_input.dispatchEvent(pc_change_event);
    postal_code_input.dispatchEvent(pc_input_event);
    city_input.dispatchEvent(city_change_event);
    city_input.dispatchEvent(city_input_event);
}

function fieldsToNormal() {
    city_input.setAttribute('type', 'text');
    city_input.parentNode.querySelector('label').setAttribute('for', city_input.id);
    postal_code_input.setAttribute('type', 'text');
    postal_code_input.parentNode.classList.remove('hidden');
    if (fancy_select) {
        fancy_select.destroy();
    }
    if (combi_input) {
        combi_input.remove();
    }
}

function fieldsToBelgian(data) {
    // replace the city and postal code field with a combined select for both
    city_input.setAttribute('type', 'hidden');
    postal_code_input.setAttribute('type', 'hidden');
    postal_code_input.parentNode.classList.add('hidden');

    combi_input = document.createElement('select');
    combi_input.classList.add('form-control');
    combi_input.setAttribute('id', 'fake-combi-input');

    const empty = document.createElement('option');
    empty.setAttribute('value', '');
    empty.setAttribute('data-city', '');
    empty.innerText = city_input.form.dataset.messageNopostalcode;
    combi_input.appendChild(empty);

    Object.keys(data).forEach((key) => {
        data[key].forEach((city) => {
            const option = document.createElement('option');

            option.setAttribute('value', key);
            option.setAttribute('data-city', city);
            option.innerText = key + ' - ' + city;

            if (postal_code_input.value + city_input.value === key + city) {
                option.setAttribute('selected', 'true');
            }

            combi_input.appendChild(option);
        });
    });

    // replace the city input with the new input
    city_input.parentNode.insertBefore(combi_input, city_input);
    city_input.parentNode.querySelector('label').setAttribute('for', combi_input.id);
    // on each change sync the values back to the original, now hidden, inputs
    combi_input.addEventListener('change', function() {
        syncAndTrigger();
    });

    fancy_select = new TomSelect(combi_input);
}

function getData() {
    // if we already have the data, don't request again and simply continue
    if (table.length > 0) {
        fieldsToBelgian(table);
        return;
    }
    // otherwise block the fields while we retrieve the data
    city_input.setAttribute('readonly', 'true');
    postal_code_input.setAttribute('readonly', 'true');
    fetch(city_input.form.dataset.postalcodesEndpoint + '?country=' + country_input.value.toUpperCase())
        .then(response => response.json())
        .then(data => {
            table = data;
            fieldsToBelgian(table);
            city_input.removeAttribute('readonly');
            postal_code_input.removeAttribute('readonly');
        });
}

const initBelgianAddresses = () => {

    country_input = document.querySelector('[name="detail[country]"]');

    if (country_input) {
        postal_code_input = country_input.form.querySelector('[name="detail[postcode]"]');
        city_input = country_input.form.querySelector('[name="detail[city]"]');

        if (postal_code_input || city_input) {
            if (country_input.value.toUpperCase() === 'BE') {
                getData();
            }

            country_input.addEventListener('change', function() {
                if (country_input.value.toUpperCase() === 'BE') {
                    getData();
                } else {
                    fieldsToNormal();
                }
            });
        }
    }
};

export default initBelgianAddresses;
