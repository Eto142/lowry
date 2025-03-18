export default function initPricePromotion(){
    let form = document.getElementById('ticketsForm');
    if (! form) {
        return;
    }

    let form_submit = form.querySelector('button[type="submit"]');
    let event_id = form.dataset.eventId;
    let promotionData = form.dataset.promotion;

    if (! promotionData) {
        return;
    }

    let promotionObj = JSON.parse(promotionData);
    let promotion_triggers = promotionObj.triggerPromotion;
    let promotables = promotionObj.eligibleForPromotion;

    if (! promotables) {
        return;
    }

    function lockInput(key) {
        let promotable_selector = 'v' + event_id + 'p' + key;
        let input = document.getElementById(promotable_selector);
        let label_group = form.querySelector('[data-price-input=' + promotable_selector + ']');

        if (input) {
            input.setAttribute('disabled', true);
            input.value = 0;
        }

        if (label_group) {
            label_group.classList.add('disabled');
        }
    }

    function checkPromotions() {
        Object.keys(promotion_triggers).forEach(i => {
            // update the promotionObj with the current number of tickets
            const trigger = promotion_triggers[i];
            const input = document.getElementById('v' + event_id + 'p' + trigger.key);
            if (input) {
                trigger.tickets = input.value;
            }
        });

        form_submit.setAttribute('disabled', true);

        $.ajax({
            type: 'POST',
            url: form.dataset.promotionEndpoint,
            data: {
                promotionData: JSON.stringify(promotionObj),
                hash: form.dataset.promotionSecret,
                event_id: event_id
            },
            dataType: 'json',
            success: function(data) {
                // reload when back end says so
                if (data.blnReload) {
                    if (data.strReloadUrl) {
                        window.location = data.strReloadUrl;
                    } else {
                        window.location.reload();
                    }
                    return;
                }

                if (data.blnResult) {
                    const unlockedPromotionPrices = data.unlockPromoPrice;

                    Object.keys(promotables).forEach(i => {
                        const promotable = promotables[i];
                        const promotable_selector = 'v' + event_id + 'p' + promotable.key;
                        const input = document.getElementById(promotable_selector);
                        const label_group = form.querySelector('[data-price-input=' + promotable_selector + ']');

                        if (unlockedPromotionPrices.indexOf(promotable.key) === -1) {
                            lockInput(promotable.key);
                        } else {
                            if (input) {
                                input.removeAttribute('disabled');
                            }
                            if (label_group) {
                                label_group.classList.remove('disabled');
                            }
                        }
                    });

                } else {
                    // eslint-disable-next-line no-console
                    console.error('POST failed, resetting price inputs');
                    Object.keys(promotables).forEach(i => {
                        lockInput(promotables[i].key);
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // eslint-disable-next-line no-console
                console.error(textStatus + ' ' + errorThrown);
                Object.keys(promotables).forEach(i => {
                    lockInput(promotables[i].key);
                });
            }
        }).done(function(){
            form_submit.removeAttribute('disabled', true);
        });
    }

    Object.keys(promotion_triggers).forEach(i => {
        const trigger = promotion_triggers[i];
        const input = document.getElementById('v' + event_id + 'p' + trigger.key);

        if (input) {
            input.addEventListener('change', function(){
                checkPromotions();
            });
        }
    });
}
