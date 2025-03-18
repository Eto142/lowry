import { Cart } from '@module/cart.js';

export default function initCart() {
    const cartHandling = new Cart();

    if (! cartHandling.root_element) {
        return;
    }

    // clicking the mask closes the modal
    cartHandling.root_element.onclick = function(event) {
        event.preventDefault();
        event.stopPropagation();
        cartHandling.modalClose();
    };

    // catch clicks inside the actual cart
    document.getElementById('cart-inner').onclick = function(event) {
        event.stopPropagation();
    };

    // take over the add-to-order button handling
    cartHandling.getButtons().forEach(function(button) {
        button.onclick = function(event) {
            event.preventDefault();
            event.stopPropagation();
            if (button.getAttribute('disabled')) {
                return;
            }
            cartHandling.addToCart(button);
        };
    });

    // buttons might skip cart, but should still update datalayer beforeunload
    document.querySelectorAll('[data-beforeunload-handle="cart"]').forEach(function(button) {
        button.onclick = function() {
            cartHandling.pushToDataLayer(button.dataset);
        };
    });

    // enable the cart toggle buttons
    document.querySelectorAll('[data-toggle="cart"], [aria-controls="cart"]').forEach(function(button) {
        button.onclick = function(event) {
            event.preventDefault();
            event.stopPropagation();

            cartHandling.modalToggle(button);
        };
    });

    cartHandling.updateCart();
}
