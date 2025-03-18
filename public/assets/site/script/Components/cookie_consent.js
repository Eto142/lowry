/* global ga */

import Cookies from 'js-cookie';
import { FocusTrap } from '@module/focus_trap';

function updateDatalayer(level) {
    const layer = window.dataLayer ?? [];

    // send an event with the newly selected level
    layer.push({
        'event': 'select_cookie_consent',
        'cookie_consent': level
    });

    // update values (modern and legacy) in place
    layer[0].cookie_consent = level;
    layer[0].cookieConsent = level; // for compatibility
}

export default function initCookieConsent() {
    const consent_forms = document.querySelectorAll('.cookieConsentForm');

    /**
     * Return the current domain in the format .example.com
     * to allow cookies to work across all subdomains.
     *
     * Jest not implemented yet
     * @example - passing tests https://codesandbox.io/s/jest-playground-3dntr
     * test('More robust TLD handling', () => {
     * expect(getCurrentDomain('www.joyce.org')).toBe('joyce.org');
     * expect(getCurrentDomain('www.joyce.org.uk')).toBe('joyce.org.uk');
     * expect(getCurrentDomain('joyce.org.uk')).toBe('joyce.org.uk');
     * });
     * @todo - move to test suite once integrated
     */
    function getCurrentDomain(domain) {
        const parts = domain.split('.');
        if (parts.length >= 2) {
            if (parts[parts.length - 1].length === 2 && parts[parts.length - 2].length <= 3) {
                // Handle cases like .co.uk, .org.uk, etc.
                return parts.slice(-3).join('.');
            } else {
                return parts.slice(-2).join('.');
            }
        }
        // fallback and just return hostname
        return window.location.hostname;
    }

    function setCookie(level) {
        Cookies.set('cookie_consent', level, { expires: 365, domain: getCurrentDomain(window.location.hostname) });
    }

    function setConsent(level) {
        const tagmanager_placeholder = document.getElementById('tagmanager-placeholder');

        if (! level) {
            return;
        }

        setCookie(level);
        updateDatalayer(level);

        consent_forms.forEach(function(form) {
            form.classList.add('waiting');
        });

        if (level !== 'default' && tagmanager_placeholder) {
            /*
                dynamically add the tagmanager code snippet
                this allows analytics etc to catch the initial visit and
                record information like the referrer
                this loads the tagmanager with the cookie consent correctly set
                we also reload to rebuild things like iframes and video embeds
            */

            tagmanager_placeholder.innerHTML = tagmanager_placeholder.dataset.snippet;

            // if analytics loads really fast we're ready to reload asap
            window.setTimeout(function() {
                if (window.ga && ga.loaded) {
                    window.location.reload();
                }
            }, 500);
            // otherwise we give it a second extra and reload anyway
            window.setTimeout(function() {
                window.location.reload();
            }, 1500);

        } else {
            window.location.reload();
        }
    }

    consent_forms.forEach(function(form) {
        const options_form = form.querySelector('[name="cookieConsentOptions"]');
        const more_link = form.querySelector('.more-link');
        if (more_link) {
            more_link.onclick = function(event) {
                event.preventDefault();
                event.stopPropagation();
                form.classList.toggle('show-more');
            };
        }

        const all_the_cookies_button = form.querySelector('button[name="all"]');
        if (all_the_cookies_button) {
            all_the_cookies_button.onclick = function(event) {
                event.preventDefault();
                event.stopPropagation();
                setConsent('all');
            };
        }

        form.querySelectorAll('button[name="settings"]').forEach(function(button) {
            button.onclick = function(event) {
                event.preventDefault();
                event.stopPropagation();
                form.classList.toggle('settings');
            };
        });

        form.querySelectorAll('button[type="button"][value]').forEach(function(button) {
            button.onclick = function(event) {
                event.preventDefault();
                event.stopPropagation();
                setConsent(button.value);
            };
        });

        options_form.onsubmit = function(event) {
            event.preventDefault();
            event.stopPropagation();
            setConsent(options_form.elements.cookieConsentLevel.value);
        };
    });

    // lengthen cookie expiration date every time someone visits the site
    if (Cookies.get('cookie_consent')) {
        setCookie(Cookies.get('cookie_consent'));
    }

    document.querySelectorAll('#cookie-consent-banner').forEach((banner) => {
        const trap = new FocusTrap(banner);
        trap.setTrap();
    });
}
