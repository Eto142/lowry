/* global dataLayer, moment */

import Cookies from 'js-cookie';
import { FocusTrap } from '@module/focus_trap';

export default function initAbandondedCartBanner() {
    let banner_template = document.getElementById('cart-banner');

    if (! banner_template) {
        return;
    }

    function closeBanner(event) {
        event.preventDefault();
        event.stopPropagation();
        document.querySelector('.abandoned-cart-banner').remove();
        Cookies.set('cart_banner_rejected', 'true');
    }

    function renderBanner(banner, image) {
        let element = banner_template.content.cloneNode(true);
        element.querySelectorAll('a').forEach(function(link) {
            link.setAttribute('href', banner.url);
            if (Number(banner.new_window) > 0) {
                link.setAttribute('target', '_blank');
            }
        });
        element.querySelector('.title a').innerHTML = banner.name;
        element.querySelector('.root').classList.add('abandoned-cart-banner');

        if (!image) {
            element.querySelector('.imageLayer').remove();
        } else {
            element.querySelector('.imageLayer').setAttribute('style', 'background-image: url(' + image + ');');
        }

        if (!banner.description) {
            element.querySelector('.subtitle').remove();
        } else {
            element.querySelector('.subtitle').innerHTML = banner.description;
        }

        if (!banner.button_label) {
            element.querySelector('.footer').remove();
        } else {
            element.querySelector('.footer .btn').innerHTML = banner.button_label;
        }

        element.querySelector('.banner').onclick = function(event){
            event.stopPropagation();
        };
        element.querySelector('.root').addEventListener('keydown', (event) => {
            if (event.code === 'Escape') {
                closeBanner(event);
            }
        });
        element.querySelector('.root').onclick = function(event){
            closeBanner(event);
        };
        element.querySelector('.close-button').onclick = function(event){
            closeBanner(event);
        };
        const trap = new FocusTrap(element.querySelector('.banner'));

        document.body.appendChild(element);

        trap.setTrap();
    }

    function getBanner() {
        $.ajax({
            type: 'GET',
            url: banner_template.dataset.bannerRetrieveEndpoint,
            data: {
                'thumb': 'FE3_2by1'
            },
            dataType: 'json',
            success: function(data){
                if (!data.banner) {
                    // es gibt es keine banners
                    return;
                }
                renderBanner(data.banner, data.image);
            },
            error: function(jqXHR, textStatus, errorThrown){
                // eslint-disable-next-line no-console
                console.error(textStatus + '  ' + errorThrown);
            }
        });
    }

    // trip the trigger during checkout, but don't show the banner yet
    if (dataLayer[0].location_category.indexOf('checkout') > -1) {
        Cookies.set(
            'cart_banner_triggered',
            moment().toISOString(), // a parseable timestamp string so we can read back later and compare
            { expires: 14 } // remember for 2 weeks
        );
        return;
    }

    // don't display the banner during login, or editing accounts
    if (document.location.pathname.indexOf('/my/') > -1 || document.location.pathname.indexOf('/mijntheater/') > -1) {
        return;
    }

    // when the banner is closed, it won't come back this session
    if (Cookies.get('cart_banner_rejected')) {
        return;
    }

    // it has to be triggered first of course
    if (!Cookies.get('cart_banner_triggered')) {
        return;
    }

    // and it has to be triggered some seconds ago
    if (moment().diff(
        moment(Cookies.get('cart_banner_triggered')),
        'seconds'
    ) < Number(banner_template.dataset.bannerTimeout)) {
        return;
    }

    $.ajax({
        type: 'POST',
        url: banner_template.dataset.cartRetrieveEndpoint,
        dataType: 'json',
        success: function(data) {
            Cookies.remove('cart_banner_triggered');
            if (data.cartItems.length > 0) {
                getBanner();
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            // eslint-disable-next-line no-console
            console.error(textStatus + '  ' + errorThrown);
        }
    });
}
