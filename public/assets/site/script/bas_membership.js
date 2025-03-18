export default function initBasMembership() {
    /*
        For all tickets for Cineville / PodiumPas a code needs to be provided.
        This inserts the required fields into the form
    */
    let form = document.getElementById('ticketsForm');
    if (! form) {
        return;
    }

    let membership_prices = {};
    let fieldset_key = 'membership-codes';
    let input_key = function(price_id, counter) {
        return 'membership-' + price_id + '[' + counter + ']';
    };

    function getProperName(type) {
        switch(type) {
        case 'cineville':
            return 'Cineville';
        case 'podiumpas':
            return 'Podiumpas';
        default:
            return '';
        }
    }

    function makeMembershipInputs(select) {
        if (document.getElementById(fieldset_key)) {
            document.getElementById(fieldset_key).remove();
        }

        if (Object.keys(membership_prices).length < 1) {
            return;
        }

        let fieldset = document.createElement('fieldset');
        fieldset.setAttribute('id', fieldset_key);

        let line = document.createElement('hr');

        let title = document.createElement('h4');
        title.innerText = form.dataset.membershipTitle.replace('[!membership!]', getProperName(select.dataset.membership));

        let message = document.createElement('p');
        message.innerText = form.dataset.membershipMessage.replace('[!membership!]', getProperName(select.dataset.membership));

        fieldset.appendChild(line);
        fieldset.appendChild(title);
        fieldset.appendChild(message);

        function preventReuse() {
            let membership_used = [];
            let inputs = document.querySelectorAll('#' + fieldset_key + ' input');

            if (inputs.length < 2) {
                return true;
            }

            inputs.forEach(function(input){
                input.setCustomValidity('');
                if (!input.value) {
                    // user is not ready yet
                    return true;
                }
                if (membership_used.indexOf(input.value) > -1) {
                    input.setCustomValidity(form.dataset.membershipMessage);
                    input.reportValidity();
                    return false;
                } else {
                    membership_used.push(input.value);
                }
            });
        }

        Object.keys(membership_prices).forEach(price => {
            const count = membership_prices[price];
            if (count < 1) {
                return;
            }

            let i = 0;
            while (i < count) {
                i++;

                let key = input_key(price, i);

                let group = document.createElement('div');
                group.classList.add('form-group');

                let label = document.createElement('label');
                label.setAttribute('for', key);
                label.innerText = form.dataset.membershipLabel.replace('[!membership!]', getProperName(select.dataset.membership));

                let input = document.createElement('input');
                input.setAttribute('name', key);
                input.setAttribute('id', key);
                input.setAttribute('type', 'text');
                // this would match only cineville, we need to account for podiumpas too
                if (select.dataset.membership === 'cineville') {
                    input.setAttribute('pattern', '^CP\\$[0-9]+');
                    input.value = 'CP$';
                }
                input.setAttribute('required', 'true');

                input.addEventListener('keyup', function(){
                    preventReuse();
                });

                group.appendChild(label);
                group.appendChild(input);
                fieldset.appendChild(group);
            }
        });

        form.insertBefore(fieldset, form.querySelector('.footer'));
    }

    form.querySelectorAll('select[data-membership]').forEach(function(select){
        select.addEventListener('change', function(){
            if (Number(select.value) > 0) {
                membership_prices[select.name] = Number(select.value);
            } else {
                delete membership_prices[select.name];
            }
            makeMembershipInputs(select);
        });

    });
}
