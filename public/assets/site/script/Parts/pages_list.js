import { CustomNavSelect } from '@module/custom_nav_select';
import { debounce } from '@module/debounce';

function initFilterWidgetAndObserve(filter) {
    const nav_element = filter.querySelector('ul');
    if (!nav_element) {
        return;
    }

    const anchor_links = nav_element.querySelectorAll('a[href]');

    if (anchor_links.length === 0) {
        return;
    }

    // offset prevents jumping behaviour when screen size is right on the observer point
    // This is because entry.contentRext.width changes when nav select gets initialized
    const offset = 31;

    const initialLastLinkRight = anchor_links[anchor_links.length - 1].getBoundingClientRect().right + offset;

    const widget = new CustomNavSelect(nav_element, nav_element.closest('.pageListFilterWrapper'));

    const onResize = debounce(function(entry) {
        if (initialLastLinkRight > entry.contentRect.width) {
            widget.set();
        } else if (initialLastLinkRight + offset < entry.contentRect.width) {
            widget.unset();
        }
    }, 100);

    const resizeObserver = new ResizeObserver(entries => {
        entries.forEach(entry => onResize(entry));
    });

    resizeObserver.observe(filter);

    return resizeObserver;
}

const observers = [];
document.querySelectorAll('.pageListFilterWrapper').forEach(filter => {
    const observer = initFilterWidgetAndObserve(filter);
    if (observer) {
        observers.push(observer);
    }
});

export default function initPageList() {
    const selected_filter = document.querySelector('.pageTypeFilter li.current');
    if (selected_filter) {
        selected_filter.addEventListener('click', () => {
            window.location.href = window.location.origin + window.location.pathname;
        });
    }
}
