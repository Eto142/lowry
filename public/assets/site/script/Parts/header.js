import { CustomNavSelect } from '@module/custom_nav_select';
import { debounce } from '@module/debounce';

function smoothScroll(target) {
    let header_height = document.getElementById('fixed-box').clientHeight;
    let target_element = document.querySelector(target);
    let target_top = target_element.getBoundingClientRect().top;
    window.scrollTo({
        top: target_top + window.scrollY - header_height,
        left: 0,
        behavior: 'smooth'
    });
}

function initHeader(){
    const header = document.getElementById('header-v2');
    if (! header) {
        return;
    }

    const fixed_element = document.getElementById('fixed-box');

    header.querySelectorAll('.submenu-container').forEach(container => {
        let inner = container.querySelector('.inner');
        if (container && inner && inner.clientHeight > 0) {
            container.style.height = inner.clientHeight + 'px';
        }
    });

    // attach the language popup to the header
    const lang_popup = document.getElementById('language-popup');
    if (lang_popup) {
        header.appendChild(lang_popup);
    }

    // open the event detail if it's scrolled out of view
    // button for add-to-cart, or scroll to events list
    let inline_info = document.querySelector('.showHeaderWrapper');
    let header_info = document.getElementById('header-event-box');
    let toggle_button = document.querySelector('[href="#event-detail-header"]');
    function checkEventInfo() {
        if (! inline_info || !header_info) {
            // apparently we're not on a detail page
            return false;
        }
        if (inline_info.getBoundingClientRect().bottom < 0) {
            header_info.classList.remove('hidden');
        } else {
            header_info.classList.add('hidden');
        }
    }

    if (inline_info && header_info) {
        window.addEventListener('scroll', function(){
            checkEventInfo();
        });
        if (toggle_button) {
            toggle_button.onclick = function(event) {
                let multi_event_toggle = document.querySelector('.showHeader [data-toggle], #showHeaderImage [data-toggle]');
                event.preventDefault();
                event.stopPropagation();
                smoothScroll('#event-detail-header');

                if (multi_event_toggle && multi_event_toggle.getAttribute('aria-expanded') === 'false') {
                    multi_event_toggle.click();
                }
            };
        }
        checkEventInfo();
    }

    function closeMenus() {
        header.querySelectorAll('[aria-controls][aria-expanded="true"]').forEach(button => {
            button.click();
        });
    }
    function applyScrolled() {
        let position;
        let optional_menu = document.getElementById('suckerfish-menu');
        let reference = header;

        if (!optional_menu || optional_menu.clientHeight < 1) {
            reference = fixed_element;
        }

        if (window.scrollY === undefined) { // IE11
            position = window.pageYOffset;
        } else {
            position = window.scrollY;
        }

        if (position > reference.clientHeight) {
            if (!header.classList.contains('is-scrolled')) {
                header.classList.add('is-scrolled');
                closeMenus();
            }
        } else {
            if (header.classList.contains('is-scrolled')) {
                header.classList.remove('is-scrolled');
                closeMenus();
            }
        }
    }
    window.addEventListener('scroll', function() {
        applyScrolled();
    });
    applyScrolled();


    // select the search field contents on focus
    let searchForm = document.forms.zoekForm;
    if (searchForm) {
        searchForm.elements[0].addEventListener('focus', function() {
            event.target.select();
        });
    }

    const slider = document.querySelector('.theme-toggle .slider');

    if (slider) {
        slider.classList.add('animate');
    }

}

function initAnchorNav(){
    // anchor menu behaviour
    // if it doesn't fit we turn it into a select
    const nav_element = document.querySelector('#anchor-menu .anchor-nav');

    if (nav_element) {
        const anchor_links = nav_element.querySelectorAll('a[href]');
        const last_link_right = anchor_links[anchor_links.length - 1].getBoundingClientRect().right;

        const widget = new CustomNavSelect(nav_element, nav_element.closest('#anchor-menu'));

        document.addEventListener('click', function(event) {
            const isClickInsideWidget = event.target.closest('#anchor-menu');

            if (widget.isOpen) {
                if (!isClickInsideWidget) {
                    widget.close();
                    event.stopPropagation();
                }
            }
        });

        anchor_links.forEach(function(link) {
            link.addEventListener('click', function(event){
                event.preventDefault();
                event.stopPropagation();
                smoothScroll(link.hash);
                if (widget.isOpen) {
                    widget.close();
                }
            });
        });

        const scrollHandler = debounce(() => {
            let last_link = null;
            anchor_links.forEach(function(link) {
                const target = document.querySelector(link.hash);
                const rect = target.getBoundingClientRect();

                // Calculate a dynamic offset based on the section height
                const offset = window.innerHeight * (rect.height < window.innerHeight ? 0.5 : 0.8);
                link.classList.remove('active');
                if (rect.top <= offset) {
                    last_link = link;
                }
            });
            if (last_link) {
                last_link.classList.add('active');
                widget.setCurrent(last_link);
            }
        }, 100);

        window.addEventListener('scroll', scrollHandler);

        const resizeObserver = new ResizeObserver((entries) => {
            if (last_link_right > entries[0].contentRect.width) {
                if (!widget.isSet) {
                    widget.set();
                }
            } else {
                if (widget.isSet) {
                    widget.unset();
                }
            }
        });

        resizeObserver.observe(document.getElementById('anchor-menu'));
    }
}

export { initAnchorNav, initHeader };
