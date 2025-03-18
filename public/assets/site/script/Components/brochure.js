/* global dataLayer */

import { Cart } from '@module/cart.js';
import { embed, pauseAll } from '@module/embed';
import { Wishlist } from '@module/wishlist.js';
import { FocusTrap } from '@module/focus_trap';
import { DataLayerPayload } from '@module/datalayer_payload';

function handleSwipes(element, leftHandler, rightHandler, downHandler) {
    let start_x = 0;
    let start_y = 0;
    let id;

    function reset() {
        id = null;
        start_x = 0;
        start_y = 0;
        element.style.transform = null;
    }

    element.addEventListener('touchstart', function(event) {
        if (! id) {
            id = event.changedTouches[0].identifier;
            start_x = event.changedTouches[0].clientX;
            start_y = event.changedTouches[0].clientY;
        } else {
            // additional touch detected, stop trying to handle it
            reset();
        }
    });

    element.addEventListener('touchmove', function(event) {
        Array.from(event.changedTouches).forEach(function(touchpoint) {
            let delta_x = 0;
            let delta_y = 0;

            // only handle touch ends for the touch point that started the swipe
            if (touchpoint.identifier === id) {
                delta_x = touchpoint.clientX - start_x;
                delta_y = touchpoint.clientY - start_y;

                if (Math.abs(delta_x) > Math.abs(delta_y)) {
                    element.style.transform = 'translateX(' + delta_x + 'px)';
                }
            }
        });
    });

    element.addEventListener('touchend', function(event) {
        Array.from(event.changedTouches).forEach(function(touchpoint) {
            let delta_x = 0;
            let delta_y = 0;


            // only handle touch ends for the touch point that started the swipe
            if (touchpoint.identifier === id) {
                delta_x = touchpoint.clientX - start_x;
                delta_y = touchpoint.clientY - start_y;

                if (Math.abs(delta_x) > Math.abs(delta_y)) {
                    // mostly horizontal movement

                    if (Math.abs(delta_x) < element.clientWidth / 3) {
                        // swipe was too short
                        reset();
                        return false;
                    }

                    if (delta_x > 0) {
                        rightHandler();
                    } else {
                        leftHandler();
                    }
                } else {
                    // mostly vertical movement

                    if (Math.abs(delta_y) < element.clientHeight / 3) {
                        // swipe was too short
                        reset();
                        return false;
                    }

                    if (delta_y > 0) {
                        downHandler();
                    }
                }

                // reset when done
                reset();
            }
        });
    });
}

function destructModal(target) {
    target.remove();
    document.body.classList.remove('modal-open');
}

function handleReveal(button) {
    // reveal hidden events
    if (button) {
        button.onclick = (event) => {
            event.preventDefault();
            event.stopPropagation();
            button.closest('.events').querySelectorAll('li.extra').forEach((item) => {
                item.classList.remove('extra');
            });
            button.remove();
        };
    }
}

function resetPopovers() {
    document.querySelectorAll('.brochureCard').forEach(function(card) {
        card.classList.remove('has-popover');
    });
    document.querySelectorAll('[aria-controls^="brochure-popover"]').forEach(function(link) {
        link.setAttribute('aria-expanded', false);
    });
    document.querySelectorAll('[id^="brochure-popover"]').forEach(function(popover) {
        popover.classList.remove('cart', 'heart');
        popover.setAttribute('aria-expanded', false);
    });
}

function dataLayerEvent(card) {
    if (!dataLayer || !card.dataset.datalayerItems) {
        return;
    }

    const items = JSON.parse(card.dataset.datalayerItems);
    const list = [];

    if (items) {
        items.forEach(item => {
            const blob = new DataLayerPayload(
                item.eventBoxofficeid,
                item.productionTitle,
                item.productionSubtitle,
                item.eventStart,
                item.productionType
            );
            list.push(blob.build());
        });

        dataLayer.push({
            'event': 'view_popup_item',
            'eventAction': 'view',
            'eventCategory': 'product',
            'eventPayload': {
                'item_name': list[0].item_name,
                'item_type': list[0].item_type,
                'items': list
            }
        });
    }
}

function constructModal(card, direction) {
    const cartHandling = new Cart();
    const wishlistHandling = new Wishlist();

    document.body.classList.add('modal-open');

    let modal_root = document.createElement('div');
    modal_root.classList.add('brochure-modal-root');
    modal_root.onclick = function() {
        destructModal(modal_root);
    };

    let modal_inner = document.createElement('ul');
    let content = card.cloneNode(true);
    modal_inner.classList.add('modal-inner');
    modal_inner.setAttribute('tabindex', '0');
    modal_inner.appendChild(content);

    if (direction === 'left') {
        modal_inner.classList.add('to-left');
    }
    if (direction === 'right') {
        modal_inner.classList.add('to-right');
    }

    content.onclick = function(e) {
        e.stopPropagation();
    };

    modal_root.appendChild(modal_inner);
    document.body.appendChild(modal_root);

    const trap = new FocusTrap(modal_inner);
    trap.setTrap();

    // prev/next browse handling
    let goLeft = function() {
        return false;
    };
    let goRight = function() {
        return false;
    };
    let goDown = function() {
        destructModal(modal_root);
    };

    if (card.nextElementSibling) {
        goLeft = function() {
            destructModal(modal_root);
            window.requestAnimationFrame(function(){
                constructModal(card.nextElementSibling, 'left');
            });
        };
        modal_inner.querySelector('.next-button').onclick = goLeft;
    } else {
        modal_inner.querySelector('.next-button').remove();
    }
    if (card.previousElementSibling) {
        goRight = function() {
            destructModal(modal_root);
            window.requestAnimationFrame(function(){
                constructModal(card.previousElementSibling, 'right');
            });
        };
        modal_inner.querySelector('.prev-button').onclick = goRight;
    } else {
        modal_inner.querySelector('.prev-button').remove();
    }

    modal_inner.querySelector('.close-button').onclick = function() {
        destructModal(modal_root);
    };

    modal_root.addEventListener('keydown', (event) => {
        if (event.code === 'Escape') {
            destructModal(modal_root);
        }
        if (event.code === 'ArrowRight') {
            goLeft();
        }
        if (event.code === 'ArrowLeft') {
            goRight();
        }
    });

    handleSwipes(modal_inner, goLeft, goRight, goDown);


    // build video2
    modal_root.querySelectorAll('.video[data-video-url]').forEach(function(placeholder) {
        embed(placeholder, placeholder.dataset.videoUrl);
    });

    // enable on-click on video1
    modal_root.querySelectorAll('.thumb[data-video-url]').forEach(function(placeholder) {
        let button = placeholder.querySelector('button');

        if (! button) {
            return;
        }

        button.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation();

            placeholder.classList.add('video-loaded');
            embed(placeholder, placeholder.dataset.videoUrl, 'autoplay');
            button.remove();
        };
    });

    // handle add-to-cart clicks
    modal_root.querySelectorAll('[data-ajax-handle="cart"]').forEach(function(button) {
        button.onclick = function(event) {
            event.preventDefault();
            event.stopPropagation();
            cartHandling.addToCart(button);
        };
    });

    // re-attach the modal events
    wishlistHandling.init(modal_root);

    handleReveal(modal_root.querySelector('.events button.expand'));

    // ... and we're done
    window.requestAnimationFrame(function(){
        modal_inner.classList.add('visible');

        // let dataLayer know we opened the modal
        dataLayerEvent(card);

        // ensure modal starts at top of the page
        modal_root.scrollTop = 0;
    });
}


export default function initBrochures() {
    document.querySelectorAll('.brochureCard').forEach((card) => {
        card.querySelectorAll('a.detail-link').forEach(function(link) {
            link.onclick = function(event) {
                if (window.innerWidth < 600) {
                    return;
                }
                event.preventDefault();
                event.stopPropagation();

                resetPopovers();
                pauseAll();
                constructModal(card);
            };
        });

        card.querySelectorAll('[aria-controls^="brochure-popover"]').forEach(function(link) {
            link.onclick = function(event) {
                event.preventDefault();
                event.stopPropagation();

                let target = document.getElementById(link.getAttribute('aria-controls'));
                let status = link.getAttribute('aria-expanded');

                resetPopovers();

                if (status === 'false') {
                    card.classList.add('has-popover');
                    if (link.classList.contains('cart')) {
                        target.classList.add('cart');
                    } else if (link.classList.contains('heart')) {
                        target.classList.add('heart');
                    }
                    window.requestAnimationFrame(function(){
                        target.setAttribute('aria-expanded', true);
                        link.setAttribute('aria-expanded', true);

                    });
                }
            };
        });

        document.body.addEventListener('click', function() {
            resetPopovers();
        });

        handleReveal(card.querySelector('.event-list-popover button.expand'));
    });
}
