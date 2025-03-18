import Cookies from 'js-cookie';
import { Select } from '@module/select.js';

let envelope_size;

function setHeight(envelope) {
    const widgets = document.getElementById('filterWidgets');
    if (! widgets) {
        return;
    }

    window.requestAnimationFrame(() => {
        if (! envelope_size) {
            envelope_size = widgets.getBoundingClientRect().height + 'px';
        }

        envelope.style.height = envelope_size;
    });
}

export default function initFilters() {
    const form_root = document.getElementById('filtersForm');
    if (! form_root) {
        return;
    }

    const filterToggle = document.getElementById('filterToggle');
    const envelope = document.getElementById('filterWrapper');
    if (filterToggle && envelope) {
        window.addEventListener('resize', () => {
            envelope_size = null;
            if (filterToggle.getAttribute('aria-expanded') !== 'false') {
                setHeight(envelope);
            }
        });
        setHeight(envelope);

        filterToggle.onclick = function(event) {
            event.preventDefault();
            event.stopPropagation();

            setHeight(envelope);

            window.requestAnimationFrame(() => {
                if (filterToggle.getAttribute('aria-expanded') === 'false') {
                    $('#filterToggle span').text($('#filterToggle').data().labelHide);
                    filterToggle.setAttribute('aria-expanded', 'true');
                    envelope.setAttribute('aria-expanded', 'true');
                    Cookies.set('filtershidden', 'false');
                } else {
                    $('#filterToggle span').text($('#filterToggle').data().labelShow);
                    filterToggle.setAttribute('aria-expanded', 'false');
                    envelope.setAttribute('aria-expanded', 'false');
                    Cookies.set('filtershidden', 'true');
                }
            });
        };
    }

    const moreTagsToggle = document.getElementById('moreTagsToggle');
    if (moreTagsToggle) {
        let too_many_tags = false;
        const toggle_label = moreTagsToggle.innerText;
        const tag_limit = Number(moreTagsToggle.dataset.limit) ?? 7;

        moreTagsToggle.onclick = function(event) {
            event.preventDefault();
            event.stopPropagation();

            if (too_many_tags) {
                too_many_tags = false;
                form_root.querySelectorAll('fieldset.tagSelector button').forEach((button, i) => {
                    moreTagsToggle.innerText = toggle_label;
                    if (i >= tag_limit) {
                        button.classList.add('hidden');
                    }
                });

            } else {
                too_many_tags = true;
                moreTagsToggle.innerText = moreTagsToggle.dataset.resetLabel;
                form_root.querySelectorAll('fieldset.tagSelector button').forEach(button => {
                    button.classList.remove('hidden');
                });
            }

            envelope_size = null;
            if (filterToggle.getAttribute('aria-expanded') !== 'false') {
                setHeight(envelope);
            }
        };
    }

    form_root.querySelectorAll('[data-fancy-select]').forEach(element => {
        new Select(element, document.getElementById('filtersForm'), element.dataset.style);
    });

    form_root.querySelectorAll('.filterBtns input[type="checkbox"]').forEach(checkbox => {
        const button = checkbox.nextElementSibling;
        if (!button) {
            return;
        }
        button.onclick = function() {
            form_root.querySelector('.submitFilters')?.removeAttr('disabled');
            if (checkbox.checked) {
                checkbox.checked = false;
                button.classList.remove('active');
            } else {
                checkbox.checked = true;
                button.classList.add('active');
            }

            form_root.submit();
        };
    });
}
