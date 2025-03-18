export default function initCoursesDayFilter() {
    const input = document.getElementById('daysOfWeek');
    const form = document.getElementById('filtersForm');

    if (!input || !form) {
        return;
    }

    function isSet(button) {
        return Number(input.value) & Number(button.value);
    }

    function updateState(button) {
        if (isSet(button)) {
            button.classList.add('active');
        } else {
            button.classList.remove('active');
        }
    }

    form.querySelector('fieldset.days-of-week').querySelectorAll('button').forEach(button => {
        updateState(button);
        button.onclick = function(event){
            event.preventDefault();
            form.querySelector('.submitFilters').removeAttribute('disabled');
            if (isSet(button)) {
                input.value = Number(input.value) - Number(button.value);
            } else {
                input.value = Number(input.value) + Number(button.value);
            }
            updateState(button);
        };
    });

    const reset = document.getElementById('selected_days');
    if (reset) {
        reset.onclick = function(event) {
            event.preventDefault();
            input.value = 0;
            form.submit();
        };
    }
}
