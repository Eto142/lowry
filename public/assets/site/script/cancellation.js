import { Modal } from '@module/modal';

function initCancellation() {
    let modal = document.getElementById('cancellationModal');

    if (!modal) {
        return;
    }

    const modalHandling = new Modal();
    let controller;
    let signal;
    modalHandling.setClickHandlers('[aria-controls="cancellationModal"]');

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
        modal.querySelector('#cancellation-error').style.display = null;
        modal.querySelector('#cancellation ~ button').style.display = null;
        modal.querySelector('#cancellation ~ button').removeAttribute('disabled');
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

                clear();
                modal.querySelector('.placeholder').innerHTML = data.html;

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
        const error = modal.querySelector('#cancellation-error');
        if (error) {
            error.style.display = 'none';
            error.querySelector('button').style.display = 'none';
            error.querySelector('button').setAttribute('disabled', 'disabled');
        }
    });
}

export default initCancellation;
