// automatically load Spektrix component scripts based on components on the page
// and attach our API FormHandler for newsletter signup forms
import { initialiseForms } from '../module/form';

export default function initSpektrix() {
    const components_on_page = [];
    let api_hanlders_on_page;

    if (!api_hanlders_on_page) {
        api_hanlders_on_page = initialiseForms('[data-api-type="spektrix"]');
    }

    [
        'spektrix-login-status',
        'spektrix-basket-summary',
        'spektrix-donate',
        'spektrix-gift-vouchers',
        'spektrix-memberships',
        'spektrix-merchandise'
    ].forEach(component => {
        if (document.querySelector(component)) {
            components_on_page.push(component);
        }
    });

    if (components_on_page.length > 0) {
        const loader = document.createElement('script');
        loader.setAttribute('src', 'https://webcomponents.spektrix.com/stable/webcomponents-loader.js');
        document.head.appendChild(loader);

        const component = document.createElement('script');
        component.setAttribute('src', 'https://webcomponents.spektrix.com/stable/spektrix-component-loader.js');
        component.setAttribute('data-components', components_on_page.join(','));
        document.head.appendChild(component);
    }
}
