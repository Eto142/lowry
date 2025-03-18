import initAccordions from './accordion';
import initBrochures from './brochure';
import initCardSliders from './card_sliders';
import initCookieConsent from './cookie_consent';
import initDateFilters from './date_filter';
import initHeaderMenus from './header_menus';
import initLanguageMenu from './language_menu';
import initLocationGroupToggle from './location_group_toggle';
import initOrderTimer from './order_timer';
import initRichtextAnchors from './richtext_anchors';
import initUserMenu from './user_menu';
import initVideoCards from './video_card';

function initComponents() {
    initAccordions();
    initBrochures();
    initCardSliders();
    initCookieConsent();
    initDateFilters();
    initHeaderMenus();
    initLanguageMenu();
    initLocationGroupToggle();
    initOrderTimer();
    initRichtextAnchors();
    initUserMenu();
    initVideoCards();
}

export default initComponents;
