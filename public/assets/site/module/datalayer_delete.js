/* global dataLayer */

/**
 * Remove a voorstelling from the dataLayer,
 * and send an event with all its details
 */
export function datalayerDelete(box_office_id) {
    let blob = {};

    // live update the datalayer
    // the first object in it is our modern datablob
    dataLayer[0].order_items.forEach(cart_entry => {
        const index = dataLayer[0].order_items.indexOf(cart_entry);
        if (cart_entry.item_code === box_office_id) {
            blob = dataLayer[0].order_items[index];
            dataLayer[0].order_items.splice(index, 1);
            return false;
        }
    });

    // send an event container a standard GA layout + additional payload
    dataLayer.push({
        'event': 'cart_remove',
        'eventCategory': 'product',
        'eventAction': 'remove_from_cart',
        'eventLabel': box_office_id,
        'eventPayload': blob
    });
}
