/* global moment */

function fill(moment_value, virtual, real) {
    if (moment_value.isValid()) {
        real.value = moment_value.format('YYYY-MM-DD');
        virtual.value = moment_value.format('L');
        return;
    }
    real.value = null;
    virtual.value = null;
}

function isBirthdayField(field) {
    return field.getAttribute('autocomplete') === 'bday';
}

function isValidBirthdate(birthdate) {
    if (!moment(birthdate, 'YYYY-MM-DD', true).isValid()) {
        return false;
    }

    const minBirthdate = moment().subtract(150, 'years').format('YYYY-MM-DD');
    const maxBirthdate = moment().format('YYYY-MM-DD');
    // Check if the birthdate is in the future or more than 150 years in the past
    return moment(birthdate).isSameOrBefore(maxBirthdate) && moment(birthdate).isSameOrAfter(minBirthdate);
}

function isValid(input) {
    const { value } = input;
    const isRequiredField = input.hasAttribute('required');

    if (isBirthdayField(input)) {
        return moment(value).isValid() && isValidBirthdate(value);
    } else if (isRequiredField) {
        return moment(value).isValid();
    } else {
        return true;
    }
}

export default function initFormDatePicker() {
    document.querySelectorAll('input[type="date"]').forEach(function(input) {
        let start_value = input.value;
        let default_value = moment();
        let max_value = null;

        let readable_format = moment.localeData(document.documentElement.getAttribute('lang')).longDateFormat('L');

        switch (document.documentElement.getAttribute('lang')) {
        case 'nl':
        case 'de':
            readable_format = readable_format.replace('YYYY', 'JJJJ');
            break;
        case 'fr':
            readable_format = readable_format.replace('DD', 'JJ');
            readable_format = readable_format.replace('YYYY', 'AA');
            break;
        }

        if (isBirthdayField(input)) {
            default_value = moment().subtract(18, 'years');
            max_value = parseInt(moment().format('YYYY'), 10);
            if (
                // invalid dates are already handled elsewhere
                moment(start_value).isValid() && (
                    // birthdates can't be in the future
                    moment(start_value).isAfter(moment()) ||
                    // or before 1901
                    moment(start_value).isBefore(moment('1902-01-01'))
                )
            ) {
                start_value = null;
                input.value = null;
            }
        }

        const datepicker_input = input.cloneNode();
        input.setAttribute('type', 'hidden');
        input.removeAttribute('id'); // prevent duplicate id
        datepicker_input.removeAttribute('name'); // prevent duplicate name
        datepicker_input.setAttribute('type', 'text');
        datepicker_input.setAttribute('placeholder', readable_format);
        input.parentNode.insertBefore(datepicker_input, input);
        datepicker_input.value = moment(start_value).isValid() ? moment(start_value).format('L') : '';

        function getValidationMessage() {
            const validation_msg = datepicker_input.form.querySelector('[name="detail[birthdate]"] ~ .validationMsg');
            if (validation_msg) {
                return validation_msg.innerText;
            }
            return '';
        }

        $(datepicker_input).daterangepicker({
            autoUpdateInput: false,
            startDate: moment(start_value).isValid() ? moment(start_value) : default_value,
            singleDatePicker: true,
            showDropdowns: true,
            minYear: 1901,
            maxYear: max_value,
            locale: {
                format: 'L',
                applyLabel: input.dataset.applyLabel,
                cancelLabel: input.dataset.cancelLabel
            }
        }, function(start) {
            fill(start, datepicker_input, input);
        });
        
        $(datepicker_input).on('hide.daterangepicker', function() {
            if (isValid(input)) {
                datepicker_input.classList.remove('checked-invalid');
                datepicker_input.setCustomValidity('');
                fill(moment(datepicker_input.value, 'L'), datepicker_input, input);
            } else {
                datepicker_input.classList.add('checked-invalid');
                datepicker_input.setCustomValidity(getValidationMessage());
            }
        });

        $(datepicker_input).on('apply.daterangepicker', function(event, picker) {
            if (isValid(input)) {
                datepicker_input.setCustomValidity('');
                fill(picker.startDate, datepicker_input, input);
            } else {
                datepicker_input.setCustomValidity(getValidationMessage());
            }
        });
    });
}
