/* global moment, dataLayer */

import Cookies from 'js-cookie';
import { formatter } from '@lib/formatter';
import { FocusTrap } from './focus_trap';
import { animating } from './util';
import { datalayerDelete } from './datalayer_delete';
import { DataLayerPayload } from './datalayer_payload';

let removed_buffer = [];
let handler;

export class Cart {
    constructor() {
        this.root_element = document.getElementById('cart');
        this.toggle_button_selector = '[data-toggle="cart"], [aria-controls="cart"]';
        this.open_button;
        this.FocusTrap = this.root_element ? new FocusTrap(this.root_element.querySelector('[tabindex="0"]')) : null;
    }

    getButtons() {
        return document.querySelectorAll('[data-ajax-handle="cart"]');
    }

    modalOpen() {
        this.root_element.setAttribute('aria-expanded', true);
        document.querySelectorAll(this.toggle_button_selector).forEach(function(button) {
            button.setAttribute('aria-expanded', true);
        });
        document.body.classList.add('modal-open');

        this.FocusTrap.setTrap();

        this.root_element.addEventListener('keydown', handler = (event) => {
            if (event.code === 'Escape') {
                this.modalClose();
            }
        });

        this.pushDataLayerCustomEvent({event: 'cart_opened'});
    }

    modalClose() {
        removed_buffer = []; // clear the list of removed entries
        this.root_element.setAttribute('aria-expanded', false);

        animating(this.root_element);

        document.querySelectorAll(this.toggle_button_selector).forEach(function(button) {
            button.setAttribute('aria-expanded', false);
        });
        document.body.classList.remove('modal-open');

        this.FocusTrap.releaseTrap();

        window.removeEventListener('keydown', handler);

        if (this.open_button) {
            this.open_button.focus();
        }

        this.pushDataLayerCustomEvent({event: 'cart_closed'});
    }

    /**
     * Open or close the popup.
     */
    modalToggle(button) {
        if (this.root_element.getAttribute('aria-expanded') === 'true') {
            this.modalClose();
        } else {
            this.open_button = button;
            this.updateCart();
            this.modalOpen();
        }
    }

    /**
     * Update item counters on cart badge elements.
     */
    setCounters(amount) {
        const el = document.getElementById('cartLabel');
        if (el) {
            el.dataset.count = amount;
        }
        document.querySelectorAll('#cartLabel .badge, #cart .badge').forEach(badge => {
            badge.innerText = amount;
            if (amount > 0) {
                badge.style.display = null;
            } else {
                badge.style.display = 'none';
            }
        });
        if (amount < 1 && document.getElementById('orderTimer')) {
            document.getElementById('orderTimer').style.display = 'none';
        }
    }

    /**
     * Flip the text on buttons for the entries in the cart.
     */
    flipButtons(skus) {
        const cls = this;
        cls.getButtons().forEach(button => {
            const label = button.querySelector('.label');

            if (button.dataset.themeId) {
                return;
            }

            if (skus.indexOf('event' + button.dataset.eventBoxofficeid) > -1) {
                button.classList.add('event-in-cart');
                if (label && button.dataset.buttonLabelDefault) {
                    label.innerHTML = cls.root_element.dataset.buttonLabelAdded;
                }
                button.onclick = function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    window.location = cls.root_element.dataset.checkoutUrl;
                };
            } else {
                button.classList.remove('event-in-cart');
                if (label && button.dataset.buttonLabelDefault) {
                    label.innerHTML = button.dataset.buttonLabelDefault;
                }
                button.onclick = function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    cls.addToCart(button);
                };
            }
        });
    }

    pushToDataLayer(source_data) {
        const blob = new DataLayerPayload(
            source_data.eventBoxofficeid,
            source_data.productionTitle,
            source_data.productionSubtitle,
            source_data.eventStart,
            source_data.productionType,
            source_data.productionCode
        );

        if (dataLayer === undefined) {
            return;
        }

        // live update the datalayer
        // the first object in it is our modern datablob
        dataLayer[0].order_items.push(blob.build());

        // send an event, that might be useful for tracking in Google Analytics
        // use the 'event' variable for Tag Manager: https://developers.google.com/tag-manager/devguide#events
        // payload format for Analytics: https://developers.google.com/analytics/devguides/collection/analyticsjs/events#event_fields
        // additional non-standard eventPayload contains the entire event data blob
        dataLayer.push({
            'event': 'cart_add',
            'eventCategory': 'product',
            'eventAction': 'add_to_cart',
            'eventLabel': source_data.eventBoxofficeid,
            'eventPayload': blob.build()
        });

        // legacy
        dataLayer.push({
            'event': 'GAevent',
            'eventCategory': 'Bestellen',
            'eventAction': 'In winkelmandje',
            'eventLabel': source_data.eventId
        });
    }

    removeFromCart(target_item) {
        const cls = this;
        const payload = new FormData();
        const cart_item_slot = cls.root_element.querySelector('.itemList');
        const cart_next_btn = document.getElementById('cart-next-button');
        const entry = cart_item_slot.querySelector('[data-sku-id="' + target_item.sku_id + '"]');
        const kassacode = entry.dataset.skuCode;

        datalayerDelete(kassacode);

        // update the DOM
        entry.remove();
        removed_buffer.push(target_item.sku_id);

        // flag the cart as "busy" until the ajax call comes through
        cart_next_btn.classList.add('btn-disabled');
        cls.root_element.classList.add('busy');

        if (undefined !== target_item.voorstelling_id) {
            payload.append('id', target_item.voorstelling_id);
            payload.append('type', 'event');
        } else if (undefined !== target_item.subvoorstelling_id) {
            payload.append('id', target_item.subvoorstelling_id);
            payload.append('type', 'extra');
        } else if (undefined !== target_item.productId) {
            payload.append('id', target_item.productId);
            payload.append('type', 'product');
        } else {
            return;
        }

        fetch(cls.root_element.dataset.removeEndpoint, {
            method: 'POST',
            body: payload
        }).then((response) => {
            if (!response.ok) {
                cls.handleError(response);
            }
        }).then(() => {
            cls.updateCart();
        });
    }

    drawCartItem(item) {
        const cls = this;
        const cart_item_slot = cls.root_element.querySelector('.itemList');

        let ghost_entry = cart_item_slot.querySelector('[data-sku-id="' + item.sku_id + '"]');
        if (ghost_entry) {
            // a basic entry already exists, so update the image (if we have it) and link and get out of here
            const link = ghost_entry.querySelector('a');
            if (item.canonical_url && link) {
                link.setAttribute('href', item.canonical_url);
            }

            if (! item.image) {
                return;
            }
            ghost_entry.querySelector('.thumb').style.backgroundImage = 'url(' + item.image + ')';
            ghost_entry.classList.remove('new');
            return;
        }

        let start_time;
        if (item.aanvang) {
            start_time = moment(item.aanvang);
        } else if (item.start) {
            start_time = moment(item.start);
        }

        const item_root = document.createElement('li');
        item_root.classList.add('cartItemCard');
        item_root.setAttribute('data-sku-id', item.sku_id);
        item_root.setAttribute('data-sku-code', item.kvksysteem_code);

        let main;
        let main_inside = '';

        if (item.production_id) {
            main = document.createElement('a');
            if (item.canonical_url) {
                main.setAttribute('href', item.canonical_url);
            }
        } else {
            main = document.createElement('div');
        }

        if (item.image) {
            main_inside = '<div class="thumb" style="background-image: url(' + item.image + ')"></div>';
        } else if (item.production_id) {
            item_root.classList.add('new');
            main_inside = '<div class="thumb"></div>';
        }

        if (item.titel) {
            main_inside += '<h2>' + item.titel + '</h2>';
        } else if (item.name) {
            main_inside += '<h2>' + item.name + '</h2>';
        }
        main_inside += '<div class="meta">';
        if (item.artiest) {
            main_inside += item.artiest;
            main_inside += '</br>';
        }
        if (start_time) {
            main_inside += start_time.format(formatter.php2moment(cls.root_element.dataset.dateformat));
            main_inside += ' ';
            main_inside += start_time.format(formatter.php2moment(cls.root_element.dataset.timeformat));
        }
        main_inside += '</div>';

        main.classList.add('desc');
        main.innerHTML = main_inside;

        const remover = document.createElement('a');
        const icon = cls.root_element.querySelector('template#cart-icon-trash').cloneNode(true);
        const screenreader = document.createElement('span');
        screenreader.classList.add('screenreader');
        screenreader.innerText = cls.root_element.dataset.removalLabel;
        remover.appendChild(icon.content);
        remover.appendChild(screenreader);
        remover.classList.add('remove');
        remover.setAttribute('href', '#');
        remover.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation();
            cls.removeFromCart(item);
        };

        item_root.appendChild(main);
        item_root.appendChild(remover);

        if (cart_item_slot.children.length === 0) {
            cart_item_slot.appendChild(item_root);
        } else {
            cart_item_slot.insertBefore(item_root, cart_item_slot.children[0]);
        }
    }

    addToCart(button) {
        const source = button.dataset;
        const cls = this;
        const payload = new FormData();
        let cart_next_btn = document.getElementById('cart-next-button');

        cls.open_button = button;

        // update the timestamp in the abandoned cart trigger cookie
        Cookies.set(
            'cart_banner_triggered',
            moment().toISOString(), // a parseable timestamp string so we can read back later and compare
            { expires: 14 } // remember for 2 weeks
        );

        // hide the empty cart message
        document.getElementById('cart-empty-desc').style.display = 'none';

        // flag the cart as "busy" until the ajax call comes through
        cart_next_btn.classList.add('btn-disabled');
        cls.root_element.classList.add('busy');

        // draw it right away, check and update later with ajax
        if (source.eventId) {
            const item = {
                'start': source.eventStart,
                'sku_id': 'event' + source.eventBoxofficeid,
                'voorstelling_id': source.eventId,
                'kvksysteem_code': source.eventBoxofficeid,
                'production_code': source.productionCode,
                'production_id': source.productionId,
                'titel': source.productionTitle,
                'artiest': source.productionSubtitle,
            };
            cls.drawCartItem(item);
            cls.pushToDataLayer(source);

            payload.append('id', source.eventId);
            payload.append('type', 'event');
        } else {
            payload.append('id', source.themeId);
            payload.append('type', 'theme');
        }

        cls.modalOpen();

        fetch(cls.root_element.dataset.addEndpoint, {
            method: 'POST',
            body: payload
        }).then((response) => {
            if (!response.ok) {
                cls.handleError(response);
            }
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.indexOf('application/json') !== -1) {
                return response.json();
            }
            return false;
        }).then((data) => {
            if (data === false) {
                return;
            }
            if (data.success === false && data.message) {
                cls.drawMessage(data.message);
            }
            cls.updateCart();
        });
    }

    updateCart() {
        const cls = this;
        const payload = new FormData();
        payload.append('thumb', cls.root_element.dataset.thumb);

        let cart_combo_slot = cls.root_element.querySelector('.comboList');
        let cart_next_btn = document.getElementById('cart-next-button');

        cls.root_element.classList.add('busy');

        fetch(cls.root_element.dataset.retrieveEndpoint, {
            method: 'POST',
            body: payload
        }).then((response) => {
            if (!response.ok) {
                cls.handleError(response);
            }

            const contentType = response.headers.get('content-type');
            if (contentType && contentType.indexOf('application/json') !== -1) {
                return response.json();
            }
            return false;
        }).then((data) => {
            if (data === false) {
                return;
            }

            let combos = data.eligibleThemes ?? [];
            let combo_list_valid = [];
            let combo_list_invalid = [];
            let invalid_combo_limit = 0;
            let entries = cls.root_element.querySelector('.itemList').querySelectorAll('[data-sku-id]');
            let skus = [];
            let items = data.cartItems ?? [];
            let sorted_items = items.sort(function(a, b) {
                if (a.added < b.added) {
                    return -1;
                } else if (a.added > b.added) {
                    return 1;
                }
                return 0;
            });

            items.forEach(item => {
                if (item.voorstelling_id) {
                    item.sku_id = 'event' + item.kvksysteem_code;
                } else if (item.subvoorstelling_id) {
                    item.sku_id = 'extra' + item.kvksysteem_code;
                } else if (item.productId) {
                    item.sku_id = 'product' + item.kvksysteem_code;
                }
            });

            function drawCombo(item) {
                let entry = document.createElement('li');
                const title = document.createElement('h3');
                title.innerText = item.combo_price_name;

                if (item.blnMinRequiredEventsInBasket) {
                    const icon = cls.root_element.querySelector('template#cart-icon-success').cloneNode(true);
                    entry.classList.add('upsellMsg', 'msg', 'notice');
                    entry.appendChild(icon.content);
                    entry.appendChild(title);
                    if (item.conditions_met_text) {
                        const msg = document.createElement('div');
                        msg.innerHTML = item.conditions_met_text;
                        entry.appendChild(msg);
                    }

                } else if (item.blnMinRequiredEventsInBasket === false) {
                    const icon = cls.root_element.querySelector('template#cart-icon-bundle').cloneNode(true);
                    entry.classList.add('upsellDescription', 'msg');
                    entry.appendChild(icon.content);
                    entry.appendChild(title);
                    if (item.conditions_not_met_text) {
                        const msg = document.createElement('div');
                        msg.innerHTML = item.conditions_not_met_text;
                        entry.appendChild(msg);
                    }

                    if (item.hyperlink_naam && item.hyperlink) {
                        const link = document.createElement('p');
                        link.innerHTML = '<a href="' + item.hyperlink + '">' + item.hyperlink_naam + '</a>';
                        entry.appendChild(link);
                    }
                }

                cart_combo_slot.appendChild(entry);
            }

            // split out the combo messages into lists of valid and invalid combos
            Object.keys(combos).forEach(k => {
                const combo = combos[k];
                if (combo.blnMinRequiredEventsInBasket) {
                    combo_list_valid.push(combo);
                } else {
                    combo_list_invalid.push(combo);
                }
            });
            if (combo_list_valid.length > 0) {
                invalid_combo_limit = Number(cls.root_element.dataset.limitInvalidMessagesIfAnyValid);
            } else {
                invalid_combo_limit = Number(cls.root_element.dataset.limitInvalidMessages);
            }

            // empty the list
            cart_combo_slot.innerHTML = '';

            // and then fill it back up
            combo_list_valid.forEach(combo => {
                drawCombo(combo);
            });
            combo_list_invalid.forEach((combo, i) => {
                if (invalid_combo_limit === 0 || i < invalid_combo_limit) {
                    drawCombo(combo);
                }
            });

            // add the items, drawCartItem will prevent duplications
            sorted_items.forEach(item => {
                skus.push(item.sku_id);
                if (removed_buffer.indexOf(item.sku_id) === -1) {
                    cls.drawCartItem(item);
                }
            });

            // remove entries no longer in the cart
            entries.forEach(entry => {
                if (skus.indexOf(entry.dataset.skuId) === -1) {
                    entry.remove();
                }
            });

            // if the cart is empty, we have a little message to display
            if (skus.length > 0) {
                document.getElementById('cart-empty-desc').style.display = 'none';
                cart_next_btn.classList.remove('btn-disabled');
            } else {
                document.getElementById('cart-empty-desc').style.display = null;
                cart_next_btn.classList.add('btn-disabled');
            }

            cls.setCounters(skus.length);

            cls.flipButtons(skus);

            cls.root_element.classList.remove('busy');
        });
    }

    handleError(response) {
        if (response.status === 503 && response.headers.get('status-title')) {
            // typically this means maintenance is "on" and orders are impossible right now
            this.drawMessage(response.headers.get('status-title'));
            this.root_element.querySelector('.itemList').innerHTML = '';
        } else if (response.status >= 500) {
            this.drawMessage(response.status + ': ' + response.statusText);
        }
    }

    drawMessage(text) {
        const entry = document.createElement('li');
        const icon = this.root_element.querySelector('template#cart-icon-error').cloneNode(true);
        const msg = document.createTextNode(text);
        entry.appendChild(icon.content);
        entry.appendChild(msg);

        entry.classList.add('msg', 'error');
        this.root_element.querySelector('.messageList').innerHTML = '';
        this.root_element.querySelector('.messageList').appendChild(entry);
        this.root_element.classList.remove('busy');
    }

    pushDataLayerCustomEvent(event) {
        dataLayer = dataLayer || [];

        dataLayer.push(event);
    }
}
