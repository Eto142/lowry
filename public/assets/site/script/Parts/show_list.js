// collapse toggle buttons

function registerToggle(button) {
    if (!button) {
        return;
    }

    let list_selector = button.getAttribute('aria-controls');
    let list = document.getElementById(list_selector);

    if (!list_selector || !list) {
        return;
    }

    button.onclick = function(event) {
        event.preventDefault();
        event.stopPropagation();

        if (! list) {
            return;
        }

        const isLoadMore = button.classList.contains('load-more-panel__button');
        let peer_buttons = document.querySelectorAll('[aria-controls="' + list_selector + '"]');

        if (list.getAttribute('aria-expanded') === 'true') {
            peer_buttons.forEach(peer => {
                peer.setAttribute('aria-expanded', false);
            });
            list.setAttribute('aria-expanded', false);
            $(list).betterSlideUp();
        } else {
            peer_buttons.forEach(peer => {
                peer.setAttribute('aria-expanded', true);
            });
            list.setAttribute('aria-expanded', true);
            $(list).betterSlideDown();
        }

        // Not keen on the super specificity here to hide the load more panel
        // Live components will make this kind of thing trivial so I don't
        // want to waste too much time worrying about it just now
        if (isLoadMore) {
            const panel = list.closest('.subshows')?.querySelector('.load-more-panel');
            const isExpanded = list.getAttribute('aria-expanded') === 'true';

            if (panel) {
                if (isExpanded) {
                    panel.style.display = 'none';
                } else {
                    panel.style.display = null;
                }
            }
        }
    };
}

export default function initShowList() {
    document.querySelectorAll('[data-toggle="slide"]').forEach((button) => registerToggle(button));
}
