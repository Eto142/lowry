/*
Ensures that for a "required" fieldset of checkboxes, at least one checkbox is checked
... if a validation message is provided
*/
export default function initFormCheckCheckboxes() {
    document.querySelectorAll('form fieldset.checkbox.required').forEach(function(fieldset) {
        let checkboxes = fieldset.querySelectorAll('input[type="checkbox"]');

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function(){
                fieldset.classList.remove('checked-invalid');
                checkboxes[0].setCustomValidity('');
            });
        });

        fieldset.form.addEventListener('submit', function(event){
            let any_checked = false;
            let message = fieldset.querySelector('.validationMsg');
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    any_checked = true;
                }
            });

            if (any_checked) {
                fieldset.classList.remove('checked-invalid');
                checkboxes[0].setCustomValidity('');
            } else if (message && message.innerText) {
                fieldset.classList.add('checked-invalid');
                checkboxes[0].setCustomValidity(message.innerText);
                checkboxes[0].reportValidity();
                event.preventDefault();
            } else {
                fieldset.classList.remove('checked-invalid');
                checkboxes[0].setCustomValidity('');
            }
        });
    });
}
