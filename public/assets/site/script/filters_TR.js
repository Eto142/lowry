/* global moment */

import { Dates } from '@module/dates.js';
import { formatter } from '@lib/formatter';

export default function initTRFilters() {
    /*
        TR_Filter is handled with old school obtrusive javascript
        with onclicks on elements etc.
        so we need to pollute the global namespace here
    */
    window.TR_Filter = {
        setSelected: function(e) {
            let label = $(e).text();
            let labelContainer = $(e).attr('data-labelContainer');
            let container = $(e).data('container');
            let value = $(e).data('value');

            $(labelContainer).text(label);
            $(container).val(value);

            $('#filtersFormTR').submit();
        }
    };

    const form_root = document.getElementById('filtersFormTR');

    if (!form_root) {
        return;
    }

    // the inputs we fill with the value and send to the server
    let start_input = document.getElementById('startDate');
    let end_input = document.getElementById('endDate');
    // the labels display the value, the inputs are visually hidden
    let start_button = form_root.querySelector('.popoverBtn.start');
    let start_label = start_button.querySelector('.popoverBtn.start label');
    let end_button = form_root.querySelector('.popoverBtn.end');
    let end_label;

    let human_readable_format = formatter.php2moment(form_root.dataset.dateformat);

    // process event dates and ranges
    let dates = form_root.dataset.dates.split(',');
    let ranges = [];
    form_root.dataset.ranges.split(',').forEach(i => {
        if (i !== '') {
            ranges.push(i.split('|'));
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
    ranges_list[form_root.dataset.todayLabel] = [moment(), moment()];
    ranges_list[form_root.dataset.tomorrowLabel] = [moment().add(1, 'days'), moment().add(1, 'days')];
    ranges_list[form_root.dataset.thisweekendLabel] = [moment().endOf('week').subtract(2, 'days'), moment().endOf('week')];
    ranges_list[form_root.dataset.thisweekLabel] = [moment(), moment().endOf('week')];
    ranges_list[form_root.dataset.nextweekLabel] = [moment().endOf('week').add(1, 'days'), moment().endOf('week').add(1, 'weeks')];

    if (form_root.dataset.birthday) {
        let this_year = moment().format('YYYY');
        let next_year = moment().add(1, 'years').format('YYYY');
        let birthday = moment(form_root.dataset.birthday).format('-MM-DD');
        let bday_this = moment(this_year + birthday);
        let bday_next = moment(next_year + birthday);

        if (bday_this.isSameOrAfter(moment())) {
            ranges_list[form_root.dataset.birthdayLabel] = [bday_this, bday_this];
        } else {
            ranges_list[form_root.dataset.birthdayLabel] = [bday_next, bday_next];
        }
    }
    if (form_root.dataset.customRange) {
        let period = form_root.dataset.customRange.split(',');
        ranges_list[period[0]] = [moment(period[1]), moment(period[2])];
    }

    let shared_config = {
        opens: 'center',
        alwaysShowCalendars: true,
        showCustomRangeLabel: false,
        singleDatePicker: true,
        autoApply: true,
        minDate: dateHandling.getMinDate(form_root.dataset.showHistory),
        maxDate: dateHandling.getMaxDate(),
        isCustomDate: () => {
            // expects a function that returns a class name for each date
            dateHandling.getDateClassnames();
        },
        locale: {
            format: 'YYYY-MM-DD',
            firstDay: 1,
            applyLabel: form_root.dataset.applyLabel,
            cancelLabel: form_root.dataset.cancelLabel
        }
    };
    let start_picker;
    let end_picker;

    form_root.querySelector('.popoverBtn.start').addEventListener('click', function() {
        start_input.click();
    });
    start_input.addEventListener('focus', function(event) {
        // prevent the keyboard from opening on mobile devices
        event.target.blur();
    });


    start_picker = $(start_input).daterangepicker($.extend(shared_config, {
        startDate: dateHandling.getSelectedDate(start_input),
        autoUpdateInput: false,
        ranges: ranges_list
    }), function(start, end) {
        start_input.value = start.format('YYYY-MM-DD');
        start_label.innerText = start.format(human_readable_format);

        if (moment(end_input.value).isBefore(start_input.value)) {
            end_input.value = end.format('YYYY-MM-DD');
            end_label.innerText = end.format(human_readable_format);
            if (end_picker) {
                end_picker.data('daterangepicker').setStartDate(end);
                end_picker.data('daterangepicker').setEndDate(end);
            }
        } else if (start.format('MMDD') !== end.format('MMDD')) {
            // you selected a range!
            end_input.value = end.format('YYYY-MM-DD');
            if (end_picker) {
                end_picker.data('daterangepicker').setStartDate(end);
                end_picker.data('daterangepicker').setEndDate(end);
            }
        }

        $('#filtersFormTR').submit();
    });

    if (end_button) {
        end_label = end_button.querySelector('.popoverBtn.end label');
        end_button.addEventListener('click', function() {
            end_input.click();
        });
        end_input.addEventListener('focus', function(event) {
            // prevent the keyboard from opening on mobile devices
            event.target.blur();
        });
        end_picker = $(end_input).daterangepicker($.extend(shared_config, {
            startDate: dateHandling.getSelectedDate(end_input),
            autoUpdateInput: false,
            ranges: false
        }), function(start, end) {
            end_input.value = end.format('YYYY-MM-DD');
            end_label.innerText = end.format(human_readable_format);

            if (moment(end_input.value).isBefore(start_input.value)) {
                start_input.value = start.format('YYYY-MM-DD');
                start_label.innerText = start.format(human_readable_format);
                start_picker.data('daterangepicker').setStartDate(start);
                start_picker.data('daterangepicker').setEndDate(start);
            }

            $('#filtersFormTR').submit();
        });
    }
}
