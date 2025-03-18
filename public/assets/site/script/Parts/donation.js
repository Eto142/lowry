import { formatter } from '@lib/formatter';
import noUiSlider from '@node/nouislider/dist/nouislider.min.mjs';

export default function initDonation(){
    const donation_input = document.getElementById('do_amount');

    if (! donation_input) {
        return;
    }

    const form = donation_input.form;
    const visual_min = Number(donation_input.min) < 10 ? 0 : Number(donation_input.min);

    if (visual_min === Number(donation_input.max)) {
        return;
    }

    const min_feedback = donation_input.form.querySelector('.msg.min');
    const max_feedback = donation_input.form.querySelector('.msg.max');

    let keyup_timer;
    let precise = false;

    const placeholder = document.createElement('div');
    placeholder.classList.add('input-slider');
    donation_input.parentNode.classList.add('slider-active');
    donation_input.parentNode.appendChild(placeholder, donation_input);

    const moneyFormat = {
        to: function(value) {
            return formatter.floatToMoney(
                value,
                donation_input.dataset.currencyLocale,
                donation_input.dataset.currency
            );
        },
        from: function(value) {
            return formatter.moneyToFloat(value);
        }
    };

    const slider = noUiSlider.create(placeholder, {
        start: [
            Number(donation_input.value)
        ],
        connect: 'lower',
        step: Number(donation_input.step),
        range: {
            'min': visual_min,
            'max': Number(donation_input.max)
        },
        tooltips: moneyFormat
    });

    function pushToSlider(value) {
        slider.set(value);
        placeholder.noUiSlider.updateOptions(
            {
                step: 1
            }
        );
    }

    function setSlider() {
        if (placeholder.noUiSlider.get() < Number(donation_input.min)) {
            placeholder.noUiSlider.set(Number(donation_input.min));
        }
    }

    function lockForm() {
        form.classList.add('is-submitted');
        form.querySelectorAll('button').forEach(function(button){
            button.setAttribute('disabled', true);
        });
    }

    Array.from(form.elements['do_methods'].elements).forEach(option => {
        option.addEventListener('change', function(){
            setSlider();
        });
    });

    window.addEventListener('load', function(){
        pushToSlider(donation_input.value);
    });
    donation_input.onchange = function() {
        pushToSlider(donation_input.value);
    };
    donation_input.onfocus = function() {
        precise = true;
    };
    donation_input.onkeyup = function() {
        precise = true;
        window.clearTimeout(keyup_timer);
        keyup_timer = window.setTimeout(function(){
            pushToSlider(donation_input.value);
        }, 1000);
    };

    placeholder.noUiSlider.on('update', function() {
        let new_step = 1;
        let value = Math.round(slider.get());
        donation_input.value = value;
        if (value > 10) {
            new_step = 5;
        }
        if (value > 100) {
            new_step = 10;
        }
        if (!precise && new_step !== placeholder.noUiSlider.options.step) {
            // if we don't check, it goes into a loop
            placeholder.noUiSlider.updateOptions(
                {
                    step: new_step
                }
            );
        }

        if (min_feedback) {
            if (Math.round(slider.get()) === Number(donation_input.min)) {
                min_feedback.style.display = null;
            } else {
                min_feedback.style.display = 'none';
            }
        }
        if (max_feedback) {
            if (Math.round(slider.get()) === Number(donation_input.max)) {
                max_feedback.style.display = null;
            } else {
                max_feedback.style.display = 'none';
            }
        }

        setSlider();
    });

    form.addEventListener('submit', event => {
        event.preventDefault();
        event.stopPropagation();

        setSlider();
        lockForm();

        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
        }).then(response => {
            if (response.ok) {
                return response.json();
            } else {
                window.location.reload();
            }
        }).then(data => {
            window.location.href = data;
        });
    });

}
