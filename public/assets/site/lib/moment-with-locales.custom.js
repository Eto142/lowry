/**
 * Include moment with the locales that we use, configure and make
 * globally available using the set locale
 * if we need a new locale, it should be added here
 */
import moment from 'moment';
import 'moment/dist/locale/nl';
import 'moment/dist/locale/nn';
import 'moment/dist/locale/de';
import 'moment/dist/locale/da';
import 'moment/dist/locale/nb';
import 'moment/dist/locale/fr';

moment.locale(document.documentElement.getAttribute('lang'));

window.moment = moment;
