/* global dataLayer */
import Cookies from 'js-cookie';
import { DataLayerPayload } from './datalayer_payload';

export class Wishlist {
    setState(id) {
        document.querySelectorAll('[data-ajax-handler="wishlist"][data-id="' + id + '"]')
            .forEach(function(target){
                target.classList.remove('pending');
                target.classList.add('hidden');
            });
        document.querySelectorAll('.favourite .linkGotoWishList[data-id="' + id + '"]')
            .forEach(function(target){
                target.classList.remove('hidden');
            });
    }

    updateCount(url, reload_on_zero) {
        const cls = this;
        $.ajax({
            type: 'POST',
            url: url,
            data: {},
            dataType: 'json',
            success: function(data){
                document.querySelectorAll('[data-wishlist-count]').forEach(function(counter){
                    counter.setAttribute('data-wishlist-count', data.length);
                    counter.innerText = '(' + data.length + ')';
                });

                Object.keys(data).forEach(function(key){
                    cls.setState(key);
                });

                if (reload_on_zero && data.length < 1) {
                    window.location.reload();
                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                // eslint-disable-next-line no-console
                console.error(textStatus + '  ' + errorThrown);
            }
        });
    }

    flash(info) {
        let popup = document.getElementById('wishlistModal');
        if (!popup) {
            return;
        }

        let popup_buttons = popup.querySelectorAll('[aria-controls="wishlistModal"], .modal-dialog');
        popup_buttons.forEach(function(button){
            button.onclick = function(event) {
                event.preventDefault();
                event.stopPropagation();
                if (popup.getAttribute('aria-expanded') === 'false') {
                    popup.setAttribute('aria-expanded', true);
                    popup_buttons.forEach(function(b){
                        b.setAttribute('aria-expanded', true);
                    });
                } else {
                    popup.setAttribute('aria-expanded', false);
                    popup_buttons.forEach(function(b){
                        b.setAttribute('aria-expanded', false);
                    });
                }
            };
        });

        popup.querySelector('.modal-content').onclick = function(event) {
            event.stopPropagation();
        };

        popup.querySelector('.item-info').innerText = info;
        popup.setAttribute('aria-expanded', true);
    }

    addItem(dataset, user_initiated, reload) {
        const cls = this;
        $.ajax({
            type: 'POST',
            url: dataset.sendEndpoint,
            data: {
                id: dataset.id
            },
            success: function(data){
                if (data.blnReload) {
                    Cookies.set('retrywishlist', JSON.stringify(dataset));

                    if (user_initiated) {
                        window.location = data.strReloadUrl;
                    }
                    return;
                }
                Cookies.remove('retrywishlist');

                Object.keys(data).forEach(key => {
                    const value = data[key];
                    if (! value) {
                        return;
                    }

                    cls.setState(key);
                });

                if (reload) {
                    window.location.reload();
                    return;
                }

                cls.flash(dataset.title);
                cls.updateCount(dataset.retrieveEndpoint);
                cls.updateDataLayer({
                    event: 'add_to_wishlist',
                    dataset
                });
            },
            error: function(jqXHR, textStatus, errorThrown){
                // eslint-disable-next-line no-console
                console.error(textStatus + '  ' + errorThrown);
            },
            dataType: 'json'
        });
    }

    init(root_node) {
        const cls = this;
        let adders = root_node.querySelectorAll('[data-ajax-handler="wishlist"]');
        let deleters = root_node.querySelectorAll('.btnRemoveWishList');
        let wishlist_counters = root_node.querySelectorAll('[data-wishlist-count]');

        if (wishlist_counters.length > 0) {
            // set current initial state
            this.updateCount(wishlist_counters[0].dataset.retrieveEndpoint);
        }

        let retryItem = Cookies.get('retrywishlist');
        if (retryItem) {
            this.addItem(
                JSON.parse(retryItem),
                false
            );
        }

        adders.forEach((button) => {
            button.onclick = (event) => {
                event.preventDefault();
                event.stopPropagation();

                button.classList.add('pending');
                this.addItem(
                    button.dataset,
                    true,
                    button.dataset.reloadOnSuccess
                );
            };
        });

        deleters.forEach(function(button){
            button.onclick = function(event) {
                event.preventDefault();
                event.stopPropagation();

                $.ajax({
                    type: 'POST',
                    url: button.dataset.sendEndpoint,
                    data: {
                        id: button.dataset.id,
                    },
                    success: function(data){
                        Object.keys(data).forEach(key => {
                            if (! data[key]) {
                                return;
                            }

                            document.querySelector('.btnRemoveWishList[data-id="' + key + '"]').closest('.eventCard').remove();
                        });

                        cls.updateCount(button.dataset.retrieveEndpoint, true);


                        cls.updateDataLayer({
                            event: 'remove_from_wishlist',
                            item: button.dataset
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        // eslint-disable-next-line no-console
                        console.error(textStatus + '  ' + errorThrown);
                    },
                    dataType: 'json'
                });
            };
        });
    }

    updateDataLayer({event, dataset}){
        const blob = new DataLayerPayload(
            dataset.eventBoxofficeid,
            dataset.productionTitle,
            dataset.productionSubtitle,
            dataset.eventStart,
            dataset.productionType
        );

        dataLayer = dataLayer || [];

        dataLayer.push({
            event,
            eventPayload: blob.build()
        });
    }
}
