/* eslint-disable no-multi-spaces */
class Formatter {

    php2moment(strftime_format) {
        // maps strftime to moment format string

        let moment_format = strftime_format.replace(/\bE\b/, 'ddd');       // An abbreviated textual representation of the day
        moment_format = moment_format.replace(/\bEEEE\b/, 'dddd');         // A full textual representation of the day
        moment_format = moment_format.replace(/\bD\b/, 'DDDD');            // Day of the year, 3 digits with leading zeros
        moment_format = moment_format.replace(/\bdd\b/, 'DD');             // Two-digit day of the month
        moment_format = moment_format.replace(/\bd\b/, 'D');               // Day of the month, with a space preceding single digits.
        moment_format = moment_format.replace(/\be\b/, 'E');               // Day of the year, 3 digits with leading zeros
        moment_format = moment_format.replace(/\bc\b/, 'd');               // Numeric representation of the day of the week
        moment_format = moment_format.replace(/\bw\b/, 'w');               // Week number of the given year
        moment_format = moment_format.replace(/\bww\b/, 'W');              // ISO-8601:1988 week number of the given year
        moment_format = moment_format.replace(/\byyyy\b/, 'YYYY');         // Four digit representation for the year
        moment_format = moment_format.replace(/\byy\b/, 'YY');             // Two digit representation of the year going by ISO-8601:1988
        moment_format = moment_format.replace(/\ba\b/, 'A');               // UPPER-CASE 'AM' or 'PM' based on the given time
        moment_format = moment_format.replace(/\bv\b/, 'z');               // The time zone abbreviation
        moment_format = moment_format.replace('MM/dd/yy', 'MMDDYY');       // Same as "%m/%d/%y"
        moment_format = moment_format.replace('yyyy-MM-dd', 'YYYY-MM-DD'); // Same as "%Y-%m-%d"
        moment_format = moment_format.replace('MM/dd/yy', 'l');            // Preferred date representation based on locale, without the time
        moment_format = moment_format.replace('HH:mm:ss', 'HH:MM:SS');     // Same as "%H:%M:%S"
        moment_format = moment_format.replace(/'(\w+)'/g, '[$1]');         // Single quoted strings to brackets
        moment_format = moment_format.replace('\'\'', '\'');               // Escaped double-esingle quote to single quote

        return moment_format;
    }

    patchLocales(moment) {
        if (!moment.locale) {
            return;
        }
        // updates moment locales to match the output of strftime
        const nl_like_strftime = {
            monthsShort : [
                'jan', 'feb', 'mrt', 'apr', 'mei', 'jun',
                'jul', 'aug', 'sep', 'okt', 'nov', 'dec'
            ],
            weekdaysShort : [
                'zo', 'ma', 'di', 'wo', 'do', 'vr', 'za'
            ]
        };

        const current_locale = moment.locale();
        moment.updateLocale('nl', nl_like_strftime);
        moment.updateLocale('nl', nl_like_strftime);
        // updating the locales in moment also sets the locale, so set it back now
        moment.locale(current_locale);
    }

    floatToMoney(float, currencyLocale, currency) {
        if (typeof Intl.NumberFormat.prototype.formatToParts !== 'function') {
            return String(float);
        }

        // the server uses locales in a diffent format, but we can translate them:
        let server_locale = currencyLocale;
        let locale_without_charset = server_locale.split('.')[0];
        let bcp47_locale = locale_without_charset.toLowerCase().replace('_', '-');

        let formatted = Intl.NumberFormat(
            bcp47_locale,
            {
                style: 'currency',
                currency: currency
            }
        ).formatToParts(float);
        let output = '';

        formatted.forEach((part) => {
            if (part.type !== 'decimal' && part.type !== 'fraction') {
                output += part.value;
            }
        });

        return output;
    }

    moneyToFloat(money) {
        // naively convert a money value back to a float
        let matches = money.match(/\D*(\d+)[,.](\d+)/);
        return Number(matches[1] + '.' + matches[2]);
    }
}

export const formatter = new Formatter();
