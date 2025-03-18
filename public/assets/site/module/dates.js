/* global moment */

export class Dates {
    constructor(dates, ranges) {
        this.dates = dates;
        this.ranges = ranges;
    }

    /*
        tries to return the user selected date (in moment format) or a sane fallback:
        - get the date value of a given input,
        - or the first date in a range, if it's later than today
        - if all else fails return the current time i.e. "today"
    */
    getSelectedDate(input) {
        let dates = this.dates;
        if (input.value) {
            return moment(input.value);
        }
        if (dates.length > 0 && dates[0] !== '') {
            if (! moment(dates[0]).isBefore(moment())) {
                return moment(dates[0]);
            }
        }
        return moment();
    }

    /*
        if show_history, or the date range isn't usable, just return some
        date a few years ago,
        but ideally return, in moment format, the first date in the range
    */
    getMinDate(show_history) {
        let dates = this.dates;
        if (!show_history && dates.length > 0 && dates[0] !== '') {
            return moment(dates[0]);
        } else {
            return moment().subtract(10, 'years').startOf('month');
        }
    }

    /*
        ideally return, in moment format, the last date in the range,
        and fall back to some time several years in the future
    */
    getMaxDate() {
        let dates = this.dates;
        if (dates.length > 0 && dates[0] !== '') {
            return moment(dates[dates.length - 1]);
        } else {
            return moment().add(10, 'years').startOf('month');
        }
    }

    /*
        for a given date
        - if it falls in the ranges set it should be marked as
          being a date that where a longterm event is ongoing
        - if it is in the dates collection it should be marked as
          being a date with an event
    */
    getDateClassnames(date) {
        let dates = this.dates;
        let ranges = this.ranges;
        let return_string = '';
        if (dates.length < 1 || dates[0] === '') {
            return '';
        }
        if (ranges.length > 0) {
            ranges.forEach(function(range) {
                if (moment(date).isBetween(range[0], range[1])) {
                    return_string += ' has-longterm';
                }
            });
        }
        if (dates.indexOf(moment(date).format('YYYY-MM-DD')) > -1) {
            return_string += ' has-event';
        }
        return return_string;
    }
}
