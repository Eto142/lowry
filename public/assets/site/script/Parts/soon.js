function initSoonBoxes(root) {
    root.querySelectorAll('.soon-box').forEach(box => {
        const soon_revealer = box.querySelector('.revealButton');
        const soon_table = box.querySelector('.soon-table');
        const soon_envelope = box.querySelector('.soon-envelope');

        box.querySelectorAll('.extra').forEach(element => {
            element.style.display = 'none';
        });

        soon_envelope.style.height = soon_table.getBoundingClientRect().height + 'px';

        if (soon_revealer) {
            soon_revealer.classList.remove('toggled');
            soon_revealer.onclick = event => {
                event.preventDefault();
                event.stopPropagation();
                soon_revealer.classList.toggle('toggled');
                if (soon_revealer.classList.contains('toggled')) {
                    box.querySelectorAll('.extra').forEach(element => {
                        element.style.display = null;
                    });
                } else {
                    box.querySelectorAll('.extra').forEach(element => {
                        element.style.display = 'none';
                    });
                }
                soon_envelope.style.height = soon_table.getBoundingClientRect().height + 'px';
            };
        }
    });
}

export default function initSoon() {
    const soon_roots = document.querySelectorAll('.soon2Wrapper');
    let soon_toggle;
    let envelope;

    if (soon_roots.length > 0) {
        soon_toggle = document.querySelector('.soonButton');
        envelope = document.querySelector('.toggleEnvelope');

        if (soon_toggle && envelope) {
            soon_toggle.onclick = function(event) {
                event.preventDefault();
                event.stopPropagation();

                if (soon_toggle.getAttribute('aria-expanded') === 'false') {
                    envelope.querySelectorAll('li').forEach(option => {
                        option.classList.remove('hidden');
                        if (soon_toggle.textContent.trim() === option.textContent.trim()) {
                            option.classList.add('hidden');
                        }
                    });
                    soon_toggle.setAttribute('aria-expanded', 'true');
                    envelope.setAttribute('aria-expanded', 'true');
                } else {
                    soon_toggle.setAttribute('aria-expanded', 'false');
                    envelope.setAttribute('aria-expanded', 'false');
                }
            };
        }
    }

    soon_roots.forEach(function(soon_root) {
        /*
            for the "default" soon list type
            implements both date-group and .extra events visibility toggles
        */
        const soon_options = soon_root.querySelectorAll('.select-wrapper a');
        initSoonBoxes(soon_root);
        if (soon_options && soon_toggle) {
            soon_options.forEach(option => {
                option.onclick = event => {
                    event.preventDefault();
                    event.stopPropagation();
                    soon_toggle.setAttribute('aria-expanded', 'false');
                    envelope.setAttribute('aria-expanded', 'false');
                    soon_root.querySelectorAll('.soon-box').forEach(element => {
                        element.style.display = 'none';
                    });
                    soon_root.querySelector('.soon-box[data-date="' + option.dataset.value + '"]').style.display = 'block';
                    soon_root.querySelector('.button-label').innerHTML = option.innerHTML;
                    initSoonBoxes(soon_root);
                };
            });
        }

        /*
            for the "toggle" soon list type
            implements production type toggle between movie and default aka theater
        */
        const toggle_button = soon_root.querySelector('button.soon-toggle');
        if (toggle_button) {
            toggle_button.onclick = event => {
                event.preventDefault();
                event.stopPropagation();
                if (soon_root.getAttribute('data-list-toggled') !== 'movie') {
                    soon_root.setAttribute('data-list-toggled', 'movie');
                } else {
                    soon_root.removeAttribute('data-list-toggled');
                }
            };
        }

        /*
            for the "toggle" soon list type
            implements simple .extra events visibility toggles, slightly different
            from the "default" list type because the markup is significantly different
        */
        if (soon_root.classList.contains('list-toggle')) {
            soon_root.querySelectorAll('.revealButton').forEach(revealer => {
                let targets = soon_root.querySelectorAll('tr.production-type-default');
                if (revealer.classList.contains('movie')) {
                    targets = soon_root.querySelectorAll('tr.production-type-movie');
                }
                revealer.onclick = function(event) {
                    event.preventDefault();
                    event.stopPropagation();
                    revealer.classList.toggle('toggled');
                    if (revealer.classList.contains('toggled')) {
                        targets.forEach(target => {
                            target.classList.add('revealed');
                        });
                    } else {
                        targets.forEach(target => {
                            target.classList.remove('revealed');
                        });
                    }
                };
            });
        }
    });
}
