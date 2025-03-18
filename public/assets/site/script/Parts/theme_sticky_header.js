import { Cart } from '@module/cart.js';

export default function initThemeStickyHeader() {
    const theme_event_list = document.querySelector('.listWrapper.theme');
    const header = document.querySelector('.listWrapper.theme .title-row');
    const content = document.querySelector('#content');
    const cartHandling = new Cart();

    // if there is a header and a button execute sticky header
    if (!theme_event_list || !header || !header.querySelector('a')) {
        return;
    }

    const wrapper = document.createElement('div');
    wrapper.classList.add('container');
    const pinned_header = header.cloneNode(true);
    const pinned_header_title = pinned_header.querySelector('h2');

    if (pinned_header_title.dataset.altTitle) {
        pinned_header_title.innerText = pinned_header_title.dataset.altTitle;
    }

    pinned_header.querySelectorAll('[data-ajax-handle="cart"]').forEach(function(button) {
        button.onclick = function(event) {
            event.preventDefault();
            event.stopPropagation();
            cartHandling.addToCart(button);
        };
    });

    wrapper.classList.add('pinned');
    wrapper.appendChild(pinned_header);

    window.addEventListener('scroll', function() {
        if (window.scrollY > theme_event_list.offsetTop + header.clientHeight * 2) {
            if (wrapper.parentElement !== content) {
                content.appendChild(wrapper);
            }
        } else {
            if (wrapper.parentElement === content) {
                content.removeChild(wrapper);
            }
        }
    });
}
