import { formatter } from '@lib/formatter';
import noUiSlider from '@node/nouislider/dist/nouislider.min.mjs';

function findOptionById(list, id) {
    return Object.values(list).find((option) => option.value === id);
}

function lockAndSubmit(form) {
    form.classList.add('is-submitted');
    form.querySelectorAll('button').forEach((button) => {
        button.setAttribute('disabled', true);
    });
    form.submit();
}

function validate(select) {
    const first_sibling = select.form.querySelector('select');
    let total = 0;

    select.form.querySelectorAll('select').forEach(() => {
        total += Number(select.value);
    });

    if (total < 1) {
        first_sibling.setCustomValidity(select.form.dataset.minitemsMessage);
    } else {
        first_sibling.setCustomValidity('');
    }
}


export default function initProductItem() {
    document.querySelectorAll('form.productForm').forEach(product_form => {
        /* slider for price options */
        (function() {
            let placeholder = product_form.querySelector('.range-input-placeholder');

            if (!placeholder) {
                return;
            }

            let priceOptions = product_form.querySelector('select[name="price_id"]');
            priceOptions.style.display = 'none'; // remove selector
            let currency = priceOptions.dataset.currency;
            let currencyLocale = priceOptions.dataset.currencyLocale;

            let prices = [];
            for (let price of priceOptions) {
                const duplicatedPrice = prices.find(p => p.value === parseInt(price.dataset.price));
                if (!duplicatedPrice) {
                    prices.push(
                        { 'id': price.value, 'value': parseInt(price.dataset.price) }
                    );
                }
            }

            // sort min value to max value
            const sortedPrices = prices.sort((a, b) => a.value > b.value ? 1 : -1);

            // assign the first value in select as default value
            let selectedOption = findOptionById(priceOptions, sortedPrices[0].id);
            selectedOption.selected = true;

            let priceRanges = {};

            let values = sortedPrices.map((p) => p.value);

            for (let i = 0; i < values.length; i++) {
                let procent = Math.ceil(100 / values.length * i);
                /// set first price in range as min amount
                if (i === 0) {
                    priceRanges['min'] = [values[i], values[i]];
                }
                /// create price ranges from given array - based on percentage
                if (i < values.length - 1 && i !== 0) {
                    priceRanges[procent + '%'] = [values[i], values[i + 1] - values[i]];
                }
                /// check last item and set it as max amount
                if (i === values.length - 1) {
                    priceRanges['max'] = [values[values.length - 1]];
                }

            }

            let moneyFormat = {
                to: function(value) {
                    return formatter.floatToMoney(value, currencyLocale, currency);
                },

                from: function(value) {
                    return formatter.moneyToFloat(value);
                }
            };

            let slider = noUiSlider.create(placeholder, {
                start: parseInt(selectedOption.dataset.price),
                snap: true,
                connect: false,
                tooltips: moneyFormat,
                range: priceRanges,
                pips: {
                    mode: 'range',
                    density: 999,
                    stepped: true,
                    format: {
                        from: Number,
                        to: function() {
                            return '';
                        }
                    }
                },
            });

            slider.on('change', function(newValue) {
                if (!newValue || newValue.length === 0) {
                    return;
                }
                let selectedPrice = sortedPrices.find(p => p.value === parseInt(newValue));
                selectedOption = findOptionById(priceOptions, selectedPrice.id);
                selectedOption.selected = true;
            });
        }());

        // submit after one of the button is selected
        product_form.querySelectorAll('[type="hidden"][name="price_id"] ~ button').forEach((button) => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                product_form.querySelector('[type="hidden"][name="price_id"]').value = button.value;
                lockAndSubmit(product_form);
            });
        });

        // check price id value
        product_form.querySelectorAll('select[name="price_id"]').forEach((select) => {
            if (! select.value) {
                select.setCustomValidity(select.form.dataset.priceMessage);
            }

            select.addEventListener('change', () => {
                if (! select.value) {
                    select.setCustomValidity(select.form.dataset.priceMessage);
                } else {
                    select.setCustomValidity('');
                }
            });
        });

        // validation for selection of amount (total items selected should be non-zero)
        const amountSelectors = product_form.querySelectorAll('[name^="priceIdAmount"]');
        // check if price dropdown is present
        if (amountSelectors.length > 0) {
            amountSelectors.forEach(function(select) {
                validate(select);
                select.addEventListener('change', event => {
                    validate(event.target);
                });
            });
        }
    });

}
