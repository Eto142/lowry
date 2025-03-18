/* global moment */

let timer;
let deadline = null;

/**
 * Display the timer when the current time is a given number of minutes before the reference time (the deadline).
 */
function shouldDisplay(reference, offset) {
    return moment().isAfter(reference.clone().subtract(offset, 'minutes'));
}

/**
 * Flash the timer if the current time is 4 minutes before the reference time (the deadline).
 */
function shouldFlash(reference) {
    return moment().isAfter(reference.clone().subtract(4, 'minutes'));
}

function countDown() {
    const timer_element = document.getElementById('orderTimer');
    if (! timer_element) {
        // apparantly we're done and the loop has been killed
        return;
    }

    const message_node = timer_element.querySelector('span.message');
    const reader_node = timer_element.querySelector('span.screenreader');
    const new_message = timer_element.dataset.baseLabel.replace('[!time!]', deadline.fromNow(true));
    let message = '';

    message_node.innerText = '(' + deadline.fromNow(true) + ')';

    if (message !== new_message) {
        message = new_message;
        reader_node.innerText = message;
    }

    if (shouldDisplay(deadline, timer_element.dataset.thresholdMinutes)) {
        timer_element.classList.remove('hidden');
    }

    if (shouldFlash(deadline)) {
        timer_element.classList.add('timeup');
    }

    // we passed the deadline, stop and remove the timer
    if (moment().isAfter(deadline)) {
        clearInterval(timer);
        timer_element.remove();
    }
}


export default function initOrderTimer() {
    const timer_element = document.getElementById('orderTimer');

    if (! timer_element) {
        return;
    }

    fetch(timer_element.dataset.deadlineRoute)
        .then(response => {
            if (!response.ok) {
                timer_element.remove();
                throw new Error('HTTP status: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (!('date' in data)) {
                // maybe null for some reason, probably no order was started yet
                timer_element.remove();
                return;
            }

            deadline = moment(data.date);

            if (!deadline.isValid()) {
                // this should never happen
                timer_element.remove();
                return;
            }

            // run initially
            countDown();

            // looptyloop every 15 seconds
            timer = setInterval(function() {
                countDown();
            }, 15000);
        });
}
