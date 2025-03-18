export default function initOrderPhoneInput(){
    const button = document.getElementById('update-phone-number-button');
    if (!button) {
        return;
    }

    const input = document.getElementById('update-phone-number-value');
    const msg_invalid = input.parentNode.querySelector('.validationMsg').innerText;
    const msg_error = button.dataset.genericError;

    const updatePhoneNumber = e => {
        e.preventDefault();
        e.stopPropagation();

        const phoneNumber = input.value;
        input.form.classList.add('is-submitted');

        if (!phoneNumber) {
            return;
        }

        fetch(button.dataset.endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ phoneNumber })
        }).then(response => {
            input.parentNode.querySelector('.validationMsg').innerText = msg_invalid;

            if (!response.ok) {
                input.classList.add('checked-invalid');
                input.setCustomValidity(msg_invalid);
                input.form.classList.remove('is-submitted');

                if (response.status >= 500) {
                    input.parentNode.querySelector('.validationMsg').innerText = msg_error;
                    input.setCustomValidity(msg_error);
                }

                throw new Error('HTTP status: ' + response.status);
            }

            return response.json();
        }).then(data => {
            input.setCustomValidity('');
            input.classList.remove('checked-invalid');
            input.form.classList.remove('is-submitted');
            document.getElementById('display-phone-number-div').style.display = null;
            document.getElementById('edit-phone-number-div').style.display = 'none';
            document.getElementById('display-phone-number').innerText = data.phoneNumber;
        });
    };

    function displayEditPhoneNumber(e) {
        e.preventDefault();
        document.getElementById('display-phone-number-div').style.display = 'none';
        document.getElementById('edit-phone-number-div').style.display = null;
    }

    document.getElementById('edit-phone-number-link').addEventListener('click', displayEditPhoneNumber);

    button.addEventListener('click', updatePhoneNumber);
}
