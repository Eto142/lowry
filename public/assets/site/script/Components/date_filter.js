/* global moment */

import { Dates } from '@module/dates.js';
import { formatter } from '@lib/formatter';

function connectLabels(i, datepicker, unified_input, single_input) {
    const label = datepicker.querySelector('label');
    if (! label) {
        return;
    }

    unified_input.id = 'unified-datepicker-' + i;
    single_input.id = 'single-datepicker-' + i;

    if (window.innerWidth < 601) {
        label.setAttribute('for', single_input.id);
    } else {
        label.setAttribute('for', unified_input.id);
    }

    window.addEventListener('resize', () => {
        if (window.innerWidth < 601) {
            label.setAttribute('for', single_input.id);
        } else {
            label.setAttribute('for', unified_input.id);
        }
    });
}

export default function initDateFilters() {
    // date range filter
    document.querySelectorAll('.input-daterange').forEach((datepicker, i) => {
        // the inputs the user sees
        let unified_input = datepicker.querySelector('.input-daterange .dateRange');
        if (!unified_input) {
            return;
        }

        let single_input = datepicker.querySelector('.input-daterange .dateRangeSingle');
        // the inputs we fill with the value and send to the server
        let hidden_start_value = datepicker.querySelector('.input-daterange .startDate');
        let hidden_end_value = datepicker.querySelector('.input-daterange .endDate');

        let single_picker;
        let config = datepicker.dataset;
        let human_readable_format = formatter.php2moment(config.dateformat);

        connectLabels(i, datepicker, unified_input, single_input);

        // process event dates and ranges
        let dates = config.dates.replace(/,\s*$/, '').split(',');
        let ranges = [];
        config.ranges.replace(/,\s*$/, '').split(',').forEach(range => {
            if (range !== '') {
                ranges.push(range.split('|'));
            }
        });
        ranges.forEach(function(range) {
            // ensure the dates set has at least all the min and max values
            range.forEach(function(date) {
                if (date !== '') {
                    dates.push(date);
                }
            });
        });

        const dateHandling = new Dates(
            dates.sort(function(a, b) {
                if (a < b) {
                    return -1;
                } else if (a > b) {
                    return 1;
                }
                return 0;
            }),
            ranges
        );

        let ranges_list = {};
        ranges_list[config.todayLabel] = [moment(), moment()];
        ranges_list[config.tomorrowLabel] = [moment().add(1, 'days'), moment().add(1, 'days')];
        ranges_list[config.thisweekendLabel] = [moment().endOf('week').subtract(2, 'days'), moment().endOf('week')];
        ranges_list[config.thisweekLabel] = [moment(), moment().endOf('week')];
        ranges_list[config.nextweekLabel] = [moment().endOf('week').add(1, 'days'), moment().endOf('week').add(1, 'weeks')];

        if (config.birthday) {
            let this_year = moment().format('YYYY');
            let next_year = moment().add(1, 'years').format('YYYY');
            let birthday = moment(config.birthday).format('-MM-DD');
            let bday_this = moment(this_year + birthday);
            let bday_next = moment(next_year + birthday);

            if (bday_this.isSameOrAfter(moment())) {
                ranges_list[config.birthdayLabel] = [bday_this, bday_this];
            } else {
                ranges_list[config.birthdayLabel] = [bday_next, bday_next];
            }
        }
        if (config.customRange) {
            let period = config.customRange.split(',');
            ranges_list[period[0]] = [moment(period[1]), moment(period[2])];
        }

        const shared_config = {
            alwaysShowCalendars: true,
            showCustomRangeLabel: false,
            autoApply: config.autosubmit === 'true' ? true : false,
            autoUpdateInput: false,
            parentEl: 'body',
            startDate: dateHandling.getSelectedDate(hidden_start_value),
            endDate: dateHandling.getSelectedDate(hidden_end_value),
            minDate: dateHandling.getMinDate(config.showHistory),
            maxDate: dateHandling.getMaxDate(),
            isCustomDate: d => {
                // expects a function that returns a class name for each date
                return dateHandling.getDateClassnames(d);
            },
            locale: {
                format: human_readable_format,
                separator: ' ' + config.separator + ' ',
                firstDay: Number(config.weekStart),
                applyLabel: config.applyLabel,
                cancelLabel: config.cancelLabel
            },
            ranges: ranges_list
        };

        function updateDatepickerStyle() {
            if (config.style) {
                if (datepicker.querySelector('.daterangepicker')) {
                    datepicker.querySelector('.daterangepicker').classList.add(config.style);
                }
                if (config.style === 'simple') {
                    shared_config['opens'] = 'left';
                }
            }
        }

        // Add custom style class to datepicker
        updateDatepickerStyle();

        function syncValues(start, end) {
            if (end) {
                unified_input.value = start.format(human_readable_format) + ' ' + config.separator + ' ' + end.format(human_readable_format);
                single_input.value = start.format(human_readable_format) + ' ' + config.separator + ' ' + end.format(human_readable_format);
            } else {
                unified_input.value = config.startrangeLabel + ' ' + start.format(human_readable_format);
                single_input.value = config.startrangeLabel + ' ' + start.format(human_readable_format);
            }
            hidden_start_value.value = start.format('YYYY-MM-DD');
            hidden_end_value.value = end ? end.format('YYYY-MM-DD') : null;
            single_picker.data('daterangepicker').setStartDate(start);
            single_picker.data('daterangepicker').setEndDate(end);
            $('.submitFilters').removeAttr('disabled');
            unified_input.form.submit();
        }

        // prevent the keyboard from opening on mobile devices
        unified_input.addEventListener('click', function(event) {
            updateDatepickerStyle();
            event.target.blur();
        });
        single_input.addEventListener('click', function(event) {
            updateDatepickerStyle();
            event.target.blur();
        });

        $(unified_input).daterangepicker($.extend(shared_config, {
            linkedCalendars: true
        }), function(start, end) {
            syncValues(start, end);
        });

        $(unified_input).on('apply.daterangepicker', function(ev, picker) {
            syncValues(picker.startDate, picker.endDate);
        });

        single_picker = $(single_input).daterangepicker($.extend(shared_config, {
            singleDatePicker: true,
            singleRangePicker: true,
            endDateFallback: dateHandling.getMaxDate()
        }), function(start, end) {
            syncValues(start, end);
        });
    });
}
