export default function initTicketListCancellation(){
    let toggle_button = document.getElementById('toggle-edit-mode');

    if (! toggle_button) {
        return;
    }

    let toggled = toggle_button.dataset.initialState !== 'toggled';

    function toggleButtonState() {
        if (toggled) {
            toggled = false;
            toggle_button.innerText = toggle_button.dataset.initialLabel;
            toggle_button.classList.remove('btn-active');
            toggle_button.classList.add('btn-default');
        } else {
            toggled = true;
            toggle_button.innerText = toggle_button.dataset.resetLabel;
            toggle_button.classList.remove('btn-default');
            toggle_button.classList.add('btn-active');
        }
    }

    toggleButtonState();

    toggle_button.onclick = function(event) {
        event.preventDefault();
        event.stopPropagation();

        document.querySelectorAll('.cancellation-options').forEach(function(options_group){
            if (toggled) {
                options_group.style.display = 'none';
            } else {
                options_group.style.display = null;
            }
        });
        document.querySelectorAll('.listItemWrapper .btn').forEach(function(ticket_button){
            if (toggled) {
                ticket_button.style.display = null;
            } else {
                ticket_button.style.display = 'none';
            }
        });

        toggleButtonState();
    };

    const cancellation_form = document.querySelector('form[name="cancellation"]');
    if (cancellation_form) {
        const iban_form = document.querySelector('.iban-form');

        cancellation_form.addEventListener('change', () => {
            const checked_option = document.querySelector('input[type=radio]:checked');
            if (checked_option && checked_option.dataset.needsIban === '1') {
                iban_form.removeAttribute('style');
            } else {
                iban_form.setAttribute('style', 'display: none;');
            }
        });
    }
}
