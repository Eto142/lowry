import { Modal } from '@module/modal';
import { Toggler } from '@module/toggler';

function initWaitingList() {
    let modal = document.getElementById('waitlistModal');

    if (!modal) {
        return;
    }

    const modalHandling = new Modal();
    let controller;
    let signal;
    modalHandling.setClickHandlers('[aria-controls="waitlistModal"]');

    const clear = () => {
        const nodes = modal.querySelector('.placeholder').childNodes;
        if (! nodes) {
            return;
        }
        nodes.forEach(node => {
            node.remove();
        });
        modal.querySelector('.spinner').style.display = 'none';
    };

    const resetSpinner = () => {
        clear();
        modal.querySelector('.spinner').removeAttribute('style');
    };

    const displayError = () => {
        clear();
        modal.querySelector('#waitlist-error').style.display = null;
        modal.querySelector('#waitlist-error ~ button').style.display = null;
        modal.querySelector('#waitlist-error ~ button').removeAttribute('disabled');
    };

    const submit = (payload) => {
        resetSpinner();

        fetch(
            modal.dataset.endpoint,
            {
                signal: signal,
                method: 'POST',
                body: payload
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(response.status);
                }
                return response.json();
            })
            .then(() => {
                clear();
                modal.querySelector('#waitlist-success').style.display = null;
                modal.querySelector('#waitlist-success ~ button').style.display = null;
                modal.querySelector('#waitlist-success ~ button').removeAttribute('disabled');
            }).catch(() => {
                displayError();
            });

    };

    const onOpen = (event_id) => {
        const payload = new FormData();
        payload.append('id', event_id);

        fetch(
            modal.dataset.endpoint,
            {
                signal: signal,
                method: 'POST',
                body: payload,
                headers: {'X-Requested-With': 'XMLHttpRequest'} // added so that the backend recognizes this as an AJAX call via isXmlHttpRequest
                // see also https://stackoverflow.com/questions/44202593/detect-a-fetch-request-in-php
            })
            .then(response => {
                if (!response.ok) {

                    throw new Error(response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.blnReload && data.strReloadUrl) {
                    window.location.href = data.strReloadUrl;
                    return;
                }

                clear();
                modal.querySelector('.placeholder').innerHTML = data.html;
                const toggler = new Toggler('wf_toggle_tiers', ['hideTiers', 'showTiers']);

                modal.querySelector('form').addEventListener('submit', (event) => {
                    event.preventDefault();
                    event.stopPropagation();
                    const form = new FormData(modal.querySelector('form'));
                    form.append('id', event_id);
                    submit(form);
                    toggler.unset();
                });
            }).catch(() => {
                displayError();
            });
    };

    modal.addEventListener('open', (event) => {
        controller = new AbortController();
        signal = controller.signal;
        resetSpinner();
        onOpen(event.detail.data.id);
    });
    modal.addEventListener('close', () => {
        if (controller) {
            controller.abort();
        }
        resetSpinner();
        modal.querySelector('#waitlist-success').style.display = 'none';
        modal.querySelector('#waitlist-success ~ button').style.display = 'none';
        modal.querySelector('#waitlist-success ~ button').setAttribute('disabled', 'disabled');
        modal.querySelector('#waitlist-error').style.display = 'none';
        modal.querySelector('#waitlist-error ~ button').style.display = 'none';
        modal.querySelector('#waitlist-error ~ button').setAttribute('disabled', 'disabled');
    });

}

export default initWaitingList;
