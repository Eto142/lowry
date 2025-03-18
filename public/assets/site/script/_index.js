/* global moment */

import { formatter } from '@lib/formatter';
import { Wishlist } from '@module/wishlist';

const initGlobal = () => {
    /*
        correct NL locales for formatting in moment to match strftime
    */
    formatter.patchLocales(moment);

    /*
        discover primary navigation mode to disable the focus highlight
        for mouse users and mobile devices
    */
    document.querySelectorAll('body, [aria-controls], [href]').forEach(function(element) {
        element.addEventListener('mousedown', function() {
            document.body.dataset.navigationMode = 'mouse';
        });
    });

    document.body.addEventListener('keydown', function(event) {
        if (event.key === 'Tab' ||
            event.key === 'ArrowUp' ||
            event.key === 'ArrowRight' ||
            event.key === 'ArrowDown' ||
            event.key === 'ArrowLeft' ||
            event.key === ' '
        ) {
            document.body.dataset.navigationMode = 'keyboard';
        }
    });


    /*
        initialize the wishlist
    */
    const wishlistHandling = new Wishlist();
    wishlistHandling.init(document);
};

export default initGlobal;
