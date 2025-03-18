import { animating } from '@module/util.ts';
import { Theme } from '@module/theme.ts';

export default function initHeaderMenus(){
    // user and language menu popup handling and positioning
    let header = document.getElementById('header-v2');
    let menus = {}; // every button controls a menu, which we'll store here
    let timer;

    if (! header) { // order process header
        header = document.getElementById('order-header');
    }

    if (! header) { // there is no header, so whatever
        return;
    }

    const theme_toggle = document.querySelector('.theme-toggle > .switch');
    if (theme_toggle) {
        new Theme(theme_toggle);
    }

    function updateFocusability(root) {
        root.querySelectorAll('a').forEach(link => {
            if (root.getAttribute('aria-expanded') === 'true') {
                link.removeAttribute('tabindex');
            } else {
                link.setAttribute('tabindex', '-1');
            }
        });
        // because it recurses and menus on lower levels might still be closed:
        root.querySelectorAll('[aria-expanded="false"]').forEach(menu => {
            menu.querySelectorAll('a').forEach(link => {
                link.setAttribute('tabindex', '-1');
            });
        });
    }

    // we always look for the menu belonging to a specific button,
    // not the other way around.
    // we try to have focus restored to a relevant button, unless the action
    // was triggered by an "unfocused" event like a click outside the menu.
    function closeMenu(toggle_button, refocus) {
        let id = toggle_button.getAttribute('aria-controls');
        let menu = document.getElementById(id);

        menu.setAttribute('aria-expanded', 'false');
        animating(menu);

        document.querySelectorAll('[aria-controls="' + id + '"]').forEach(button => {
            button.setAttribute('aria-expanded', 'false');
        });

        updateFocusability(menu);
        Object.keys(menus).forEach(i => {
            const m = menus[i];
            const is_parent = m.menu.querySelector('#' + menu.id);
            if (is_parent) {
                window.clearTimeout(timer);
                timer = window.setTimeout(function() {
                    m.menu.style.height = null;
                }, 200);
            }
        });

        if (! toggle_button.classList.contains('v1') && refocus) {
            toggle_button.focus();
        }

        // a special extra behaviour for the hamburger
        if (toggle_button.id === 'foldout-toggle') {
            header.classList.remove('menu-opened');
            document.body.classList.remove('menu-opened');
        }
    }

    function openMenu(toggle_button) {
        let id = toggle_button.getAttribute('aria-controls');
        let menu = document.getElementById(id);

        window.clearTimeout(timer);

        Object.keys(menus).forEach(i => {
            const m = menus[i];
            let is_parent = m.menu.querySelector('#' + menu.id);
            let is_child = menu.querySelector('#' + m.menu.id);
            // close all but this menu, or parents of this menu, or children of this menu
            if (m.menu !== menu && ! is_parent && ! is_child) {
                closeMenu(m.button, false);
            }
            if (is_parent) {
                m.menu.style.height = null;
            }
        });

        window.requestAnimationFrame(function() {
            let first_target = menu.querySelector('a[href]');
            if (! first_target) {
                first_target = menu.querySelector('input[type="text"]');
            }

            document.querySelectorAll('[aria-controls="' + id + '"]').forEach(button => {
                button.setAttribute('aria-expanded', 'true');
            });
            menu.setAttribute('aria-expanded', 'true');
            if (header.classList.contains('suckerfish-small') && menu.classList.contains('suckerfish-submenu')) {
                menu.style.left = toggle_button.getBoundingClientRect().left + 'px';
            }
            updateFocusability(menu);
            window.setTimeout(function() {
                if (first_target) {
                    first_target.focus();
                }
            }, 200);
        });

        // a special extra behaviour for the hamburger
        if (toggle_button.id === 'foldout-toggle') {
            header.classList.add('menu-opened');
            document.body.classList.add('menu-opened');
        }
    }

    function toggleMenu(toggle_button) {
        let id = toggle_button.getAttribute('aria-controls');
        let menu = document.getElementById(id);

        if (menu.getAttribute('aria-expanded') === 'false') {
            openMenu(toggle_button);
        } else {
            closeMenu(toggle_button, true);
        }
    }

    header.querySelectorAll('[aria-controls]:not([aria-controls="waitlistModal"])').forEach(button => {
        let id = button.getAttribute('aria-controls');
        let menu = document.getElementById(id);

        button.onclick = function(event) {
            event.preventDefault();
            event.stopPropagation();
            toggleMenu(button);
        };
        button.addEventListener('keyup', function(event) {
            if (button.classList.contains('submenu-toggle') && (event.key === 'ArrowLeft' || event.key === 'ArrowRight')) {
                toggleMenu(button);
            } else if (event.key === 'Escape') {
                closeMenu(button, true);
            }
        });

        // because in header v1 elements are exchanged on scroll, it's
        // better to close the user popup
        if (button.classList.contains('v1')) {
            window.addEventListener('scroll', function() {
                closeMenu(button, false);
            });
        }

        if (! menus[id]) {
            menus[id] = {
                'menu': menu,
                'button': button
            };
        }
    });

    Object.keys(menus).forEach(i => {
        const menu = menus[i];
        menu.menu.addEventListener('click', function(event) {
            // stop clicks inside the popup from propagating to the body
            event.stopPropagation();
        });

        menu.menu.addEventListener('keyup', function(event) {
            if (event.key === 'Escape') {
                event.stopPropagation();
                event.preventDefault();
                const parent_list = document.activeElement.closest('li').parentElement.closest('li');
                let button;
                if (parent_list) {
                    button = parent_list.querySelector('a');
                }
                if (button && JSON.parse(button.getAttribute('aria-expanded'))) {
                    toggleMenu(button);
                } else {
                    closeMenu(menu.button, true);
                }
            }
        });

        document.body.addEventListener('click', function() {
            closeMenu(menu.button, false);
        });
    });
}
