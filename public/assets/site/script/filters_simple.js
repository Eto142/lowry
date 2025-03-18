export default function initSimpleFilters() {
    const simple_filter = document.querySelector('.simple-filter');

    if (!simple_filter) {
        return;
    }
    const filter_buttons = document.querySelectorAll('.filter-btn[aria-haspopup="true"]');
    let form_is_dirty = false;

    const findPopup = (toggle_button) => {
        const id = toggle_button.getAttribute('aria-controls');
        const target = document.getElementById(id);

        return {id, target};
    };

    const hide = (id, target) => {
        target.setAttribute('aria-expanded', 'false');
        document.querySelectorAll('[aria-controls="' + id + '"]').forEach(function(button) {
            button.setAttribute('aria-expanded', 'false');
        });
        if (form_is_dirty) {
            simple_filter.querySelector('form').submit();
        }
    };

    const show = (id, target) => {
        // close the other popups
        simple_filter.querySelectorAll('[aria-expanded="true"]').forEach((open)=> {
            open.setAttribute('aria-expanded', 'false');
        });
        target.setAttribute('aria-expanded', 'true');
        document.querySelectorAll('[aria-controls="' + id + '"]').forEach(function(button) {
            button.setAttribute('aria-expanded', 'true');
        });
    };

    const toggle = (toggle_button) => {
        const {id, target} = findPopup(toggle_button);

        if (target.getAttribute('aria-expanded') === 'false' || target.getAttribute('aria-expanded') === null) {
            show(id, target);
        } else {
            hide(id, target);
        }
    };

    Array.from(filter_buttons).forEach((button)=> {
        button.addEventListener('click', event => {
            event.preventDefault();
            event.stopPropagation();
            toggle(button);
        });
    });

    // close popup on outside click
    const body = document.body;
    ['click', 'keyup'].forEach(ev => {
        body.addEventListener(ev, () => {
            Array.from(filter_buttons).forEach((button) => {
                const {id, target} = findPopup(button);
                hide(id, target);
            });
        });
    });

    // tag filter buttons
    const tag_genre_buttons = simple_filter.querySelectorAll('input[type="checkbox"][name="genres[]"]');
    const reset_buttons = simple_filter.querySelectorAll('[data-reset="all"]');

    reset_buttons.forEach(button => {
        button.onclick = event => {
            event.stopPropagation();
            event.preventDefault();
            tag_genre_buttons.forEach(checkbox => {
                if (checkbox.checked) {
                    checkbox.nextElementSibling.click();
                }
            });
        };
    });

    tag_genre_buttons.forEach(checkbox => {
        const button = checkbox.nextElementSibling;
        const close_tag = button.querySelector('.remove-tag');

        button.onclick = function(event) {
            event.preventDefault();
            event.stopPropagation();
            form_is_dirty = true;

            if (checkbox.checked) {
                checkbox.checked = false;
                button.classList.remove('active');
                close_tag.classList.remove('active');
            } else {
                checkbox.checked = true;
                button.classList.add('active');
                close_tag.classList.add('active');
            }
            const has_selected = Array.from(tag_genre_buttons).some((c) => c.checked);
            if (has_selected) {
                reset_buttons.forEach(btn => {
                    btn.removeAttribute('disabled');
                });
            } else {
                reset_buttons.forEach(btn => {
                    btn.setAttribute('disabled', true);
                });
            }
        };
    });
}
