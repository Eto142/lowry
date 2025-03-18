export default function basMaxTickets() {
    /*
        max tickets counts over levels and overall

        of a given pricetype you can never select more than the max
        so 6 max means e.g. max 3 in level 1 and 3 in level 2

        there is also a max per event, which is the max of all selected
        tickets together

        so we disable amounts that would push the total per price type over that
        limit, or the overall total per event over the total limit
    */

    let form = document.getElementById('ticketsForm');

    if (! form) {
        return;
    }

    let amount_selects = form.querySelectorAll('select[data-price-type]');
    let price_types = {};

    function updateMax() {
        let max_per_price = {};
        let total = 0;
        let total_max = amount_selects[0].dataset.eventMax;

        amount_selects.forEach(select => {
            if (max_per_price[select.dataset.priceType] === undefined) {
                max_per_price[select.dataset.priceType] = select.dataset.priceTypeMax;
            }

            if (select.value) {
                total += Number(select.value);
                max_per_price[select.dataset.priceType] -= Number(select.value);
            }
        });

        amount_selects.forEach(select => {
            const this_max = max_per_price[select.dataset.priceType] + Number(select.value);
            const this_total_max = total_max - total + Number(select.value);

            select.querySelectorAll('option').forEach(option => {
                if (Number(option.innerText) > this_max || Number(option.innerText) > this_total_max) {
                    option.setAttribute('disabled', true);
                } else {
                    option.removeAttribute('disabled');
                }
            });
        });
    }

    amount_selects.forEach(select => {
        price_types[select.dataset.priceType] = Number(select.dataset.priceTypeMax);

        select.addEventListener('change', function() {
            updateMax();
        });
    });
}
