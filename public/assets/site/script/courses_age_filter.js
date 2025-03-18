import noUiSlider from '@node/nouislider/dist/nouislider.min.mjs';

export default function initCoursesAgeFilter() {
    const placeholder = document.getElementById('range-input-placeholder');
    const form = document.getElementById('filtersForm');

    if (!placeholder || !form) {
        return;
    }

    const minInput = document.getElementById('minAge');
    const maxInput = document.getElementById('maxAge');
    const currentMin = minInput.value ? Number(minInput.value) : 0;
    const currentMax = maxInput.value ? Number(maxInput.value) : 99;

    const slider = noUiSlider.create(placeholder, {
        start: [
            minInput.value ? minInput.value : 0,
            maxInput.value ? maxInput.value : 99
        ],
        connect: true,
        step: 1,
        range: {
            'min': 0,
            'max': 99
        }
    });

    minInput.onchange = function() {
        slider.set([minInput.value, null]);
    };
    maxInput.onchange = function() {
        slider.set([null, maxInput.value]);
    };

    slider.on('update', function() {
        const newMin = Math.round(slider.get()[0]);
        const newMax = Math.round(slider.get()[1]);

        if (currentMin !== newMin || currentMax !== newMax) {
            form.querySelector('.submitFilters').removeAttribute('disabled');
        }

        minInput.value = newMin === 0 ? null : newMin;
        maxInput.value = newMax === 99 ? null : newMax;
    });

    const reset = document.getElementById('selected_age_range');
    if (reset) {
        reset.onclick = function(event) {
            event.preventDefault();
            minInput.value = null;
            maxInput.value = null;
            form.submit();
        };
    }
}
