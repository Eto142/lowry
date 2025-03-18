import initDonation from './donation';
import initEventHeader from './event_header';
import initMediaSlider from './media';
import initFilterButtons from './filter_buttons';
import { initHeader, initAnchorNav } from './header';
import initPageList from './pages_list';
import initProductItem from './product_item';
import initReviews from './reviews';
import initShowList from './show_list';
import initSoon from './soon';
import initThemeStickyHeader from './theme_sticky_header';
import initVideo from './video';
import { shortcutsMenu } from './shortcuts_menu';


function initParts() {
    initDonation();
    initEventHeader();
    initMediaSlider();
    initFilterButtons();
    initHeader();
    initAnchorNav();
    initPageList();
    initProductItem();
    initReviews();
    initShowList();
    initSoon();
    initThemeStickyHeader();
    initVideo();
    shortcutsMenu.init();
}

export default initParts;
