import { FocusTrap } from '../module/focus_trap';

export default function initPopupBanners() {
    let trap = null;

    function makeBanner(name) {
        const banner_template = document.getElementById('generic-banner');

        if (! banner_template) {
            return;
        }

        const fragment = banner_template.content.cloneNode(true);
        const element = fragment.querySelector('.root');

        element.setAttribute('data-popup-banner-name', name);
        element.classList.add('loading');

        element.querySelector('.banner').onclick = event => {
            event.stopPropagation();
        };
        element.onclick = () => {
            element.remove();
        };
        element.querySelector('.close-button').onclick = () => {
            element.remove();
        };
        element.addEventListener('keyup', (event) => {
            if (event.code === 'Escape') {
                element.remove();
            }
        });

        document.body.appendChild(element);

        trap = new FocusTrap(element.querySelector('.banner'));
        trap.setTrap();
    }

    function fillBanner(name, banner, image) {
        const element = document.querySelector('[data-popup-banner-name="' + name + '"]');

        if (! element) {
            return;
        }

        element.classList.remove('loading');
        element.querySelector('.spinner').remove();

        element.querySelectorAll('a').forEach(link => {
            link.setAttribute('href', banner.url);
            if (Number(banner.new_window) > 0) {
                link.setAttribute('target', '_blank');
            }
        });
        element.querySelector('.title').innerHTML = banner.name;

        if (image) {
            element.querySelector('.banner').setAttribute('style', 'background-image: url(' + image + ');');
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

        if (trap) {
            trap.releaseTrap();
        }

        trap = new FocusTrap(element.querySelector('.banner'));
        trap.setTrap();
    }

    document.querySelectorAll('a[data-popup]').forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();

            makeBanner(button.dataset.popup);

            fetch(button.dataset.popup)
                .then(response => response.json())
                .then(data => {
                    fillBanner(button.dataset.popup, data.banner, data.image);
                });
        });
    });
}
