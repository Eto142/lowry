import { FocusTrap } from '@module/focus_trap';

export default function initSidebarFilters() {

    const sidebar_filter = document.querySelector('.sidebar-agenda');

    if (!sidebar_filter) {
        return;
    }

    const filter_sidebar = sidebar_filter.querySelector('.filter-sidebar');
    const click_layer = sidebar_filter.querySelector('.sidebar-click-overlay');
    const custom_link_wrapper = sidebar_filter.querySelector('.filter-sidebar .custom-links');
    const custom_links = sidebar_filter.querySelectorAll('.filter-sidebar .custom-links a');
    let trap;

    sidebar_filter.querySelector('.mobile-filters .other-filters').addEventListener('click', () => {
        click_layer.classList.add('open');
        filter_sidebar.classList.add('open');
        trap = new FocusTrap(filter_sidebar);
        trap.setTrap();
    });

    click_layer.addEventListener('click', () => {
        click_layer.classList.remove('open');
        filter_sidebar.classList.remove('open');
        if (trap) {
            trap.releaseTrap();
        }
    });

    filter_sidebar.querySelector('.close-button').onclick = () => {
        click_layer.classList.remove('open');
        filter_sidebar.classList.remove('open');
        trap.releaseTrap();
    };

    filter_sidebar.querySelectorAll('[aria-controls]').forEach(button => {
        if (! document.getElementById(button.getAttribute('aria-controls'))) {
            button.setAttribute('disabled', 'true');
        }
        button.onclick = event => {
            event.preventDefault();
            event.stopPropagation();

            const target = document.getElementById(button.getAttribute('aria-controls'));

            if (target.getAttribute('aria-expanded') !== 'true') {
                target.setAttribute('aria-expanded', 'true');
                button.setAttribute('aria-expanded', 'true');
            } else {
                target.setAttribute('aria-expanded', 'false');
                button.setAttribute('aria-expanded', 'false');
            }
        };
    });

    if (custom_links && custom_links.length) {
        custom_links.forEach((link) => {
            link.insertAdjacentHTML('beforeend', custom_link_wrapper.dataset.icon);
        });
    }

    filter_sidebar.querySelectorAll('input[type="checkbox"][name="themes[]"], input[type="checkbox"][name="genres[]"], input[type="checkbox"][name="locations[]"]').forEach(checkbox => {
        const button = checkbox.nextElementSibling;
        if (button) {
            button.onclick = function() {
                if (checkbox.checked) {
                    checkbox.checked = false;
                    button.classList.remove('active');
                } else {
                    checkbox.checked = true;
                    button.classList.add('active');
                }

                checkbox.form.submit();
            };
            return;
        }

        checkbox.onchange = () => {
            checkbox.closest('[data-fancy-select]').querySelector('[type="submit"]').removeAttribute('disabled');
        };
    });
}
