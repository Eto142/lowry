/* global pep_global_config */

/*
    https://support.tixly.com/hc/en-us/articles/360018943458-Website-integration

    Example data as returned from Tixly:
    {
        "order": {
            "items": [{
                    "type": "Ticket",
                    "name": "An evening with Q on stage",
                    "details": "13-3-2022 20:00:00",
                    "price": 12.0000
                },
                {
                    "type": "Ticket",
                    "name": "An evening with Q on stage",
                    "details": "13-3-2022 20:00:00",
                    "price": 12.0000
                }
            ],
            "expires": 896.0954898,
            "url": "https://tix.theaterexample.nl/nl/buyingflow/purchase/"
        },
        "user": {
            "id": 544803,
            "name": "James Bond",
            "email": "JamesBond@Example.com",
            "hash": "2ac9f3a26521d0194530daf3e68c88589d4490617059793dd2eba1b14b786b84"
            "tags": [{
                "id": 7,
                "name": "Premium",
                "abbr": "PP"
            }]
        },
        "profile": "https://tix.theaterexample.nl/nl/profile/"
    }
*/

export default function initIframeSSO() {
    const iframe_url = pep_global_config['sso_iframe_url'];

    if (! iframe_url) {
        return;
    }

    const cache = JSON.parse(sessionStorage.getItem('sso-user-data'));
    let handler;
    let counter = 0;

    function getMonogram(name) {
        let monogram = '';

        if (! name) {
            return monogram;
        }

        monogram = name.split(' ')[0].substr(0, 1);

        if (name.split(' ')[1]) {
            monogram += name.split(' ')[1].substr(0, 1);
        }

        return monogram.toUpperCase();
    }

    function updateAvatars() {
        const data = JSON.parse(sessionStorage.getItem('sso-user-data'));
        const active_avatars = document.querySelectorAll('[data-sso-avatar="active"]');
        const static_avatars = document.querySelectorAll('[data-sso-avatar="static"]');

        document.querySelectorAll('[data-sso-avatar]').forEach(avatar => {
            const label = avatar.querySelector('.icon-label');
            avatar.href = data.profile;

            if (!label) {
                return;
            }
            if (! data.user) {
                label.innerText = avatar.dataset.defaultLabel;
            } else {
                label.innerText = getMonogram(data.user.name);
            }
        });

        if (! data.user) {
            // user is not logged in, display avatar with login link
            active_avatars.forEach(avatar => {
                avatar.style.display = 'none';
            });
            static_avatars.forEach(avatar => {
                avatar.style.display = null;
            });

            return;
        } else {
            static_avatars.forEach(avatar => {
                avatar.style.display = 'none';
            });
            active_avatars.forEach(avatar => {
                avatar.style.display = null;
            });
        }

        document.querySelectorAll('[data-tixly-usermenu]').forEach(usermenu => {
            usermenu.querySelectorAll('.name').forEach(label => {
                label.innerText = data.user.name;
            });
            usermenu.querySelectorAll('.email').forEach(label => {
                label.innerText = data.user.email;
            });
            usermenu.querySelectorAll('a[href="#"]').forEach(link => {
                link.href = data.profile;
            });
        });
    }

    function updateCart() {
        const data = JSON.parse(sessionStorage.getItem('sso-user-data'));
        const cart_buttons = document.querySelectorAll('header .cart');

        if (!data || !data.order) {
            cart_buttons.forEach(cart_button => {
                cart_button.setAttribute('data-count', 0);
            });
            return;
        }

        if (data.order.items.length > 0) {
            cart_buttons.forEach(cart_button => {
                cart_button.setAttribute('data-count', data.order.items.length);
            });
        }

        cart_buttons.forEach(cart_button => {
            cart_button.href = data.order.url;
        });
    }

    function cleanUp() {
        // we're done with the iframe, let's get rid of it and stop listening for messages
        const iframe = document.getElementById('sso_iframe');
        window.removeEventListener('message', handler);
        if (iframe) {
            iframe.remove();
        }
    }

    function startListener() {
        window.addEventListener('message', handler = (event) => {
            if (! pep_global_config['sso_iframe_url'].includes(event.origin)) {
                // messages might come from other stuff like recaptcha,
                // we're only interested in those from tixly
                return;
            }

            const data = event.data;
            cleanUp();

            sessionStorage.setItem('sso-user-timestamp', String(Date.now()));

            if (JSON.stringify(data) === sessionStorage.getItem('sso-user-data')) {
                // incoming data matches what we already have, we're done here
                return;
            }

            // new data, send it to our backend
            fetch(pep_global_config['iframe_sso_endpoint'], {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            // cache the data in session storage and update the UI
            sessionStorage.setItem('sso-user-data', JSON.stringify(data));
            updateAvatars();
            updateCart();
        });
    }

    function maybeInjectIframe(force) {
        if (force === true) {
            counter = 0;
        } else {
            const data = JSON.parse(sessionStorage.getItem('sso-user-data'));
            if ('user' in (data ?? {}) && 'id' in (data.user ?? {})) {
                // we already have a user, no need to update
                return;
            }
        }

        if (counter > 10) {
            // don't poll for more than 5 minutes
            return;
        }
        counter++;

        if (!document.getElementById('sso_iframe')) {
            const iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.id = 'sso_iframe';
            iframe.src = iframe_url;
            iframe.onload = function(){
                iframe.contentWindow.postMessage('GetSession', '*');
            };

            document.body.appendChild(iframe);
        }
        startListener();
    }

    // update the ui using cached data
    if (cache) {
        updateAvatars();
        updateCart();
    }

    // get fresh data on page load but not more often than once every 30 secs
    // e.g. when quickly browsing the site
    let timestamp = Number(sessionStorage.getItem('sso-user-timestamp'));
    if (! cache || ! timestamp || Date.now() - timestamp > 30 * 1000) {
        // cache miss -> get data
        maybeInjectIframe(true);
    }

    // maybe get fresh data every 30 secs for stuff happening in another tab
    window.setInterval(maybeInjectIframe, 30 * 1000);
}
