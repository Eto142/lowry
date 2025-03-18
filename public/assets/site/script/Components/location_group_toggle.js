export default function initLocationGroupToggle() {
    document.querySelectorAll('.location-group-toggle-wrapper').forEach(listitem => {
        const list = listitem.parentNode;
        const toggle = list.querySelector('input.location-group-toggle');

        list.setAttribute('data-only-own-locations', toggle.checked);

        toggle.onchange = function(event) {
            event.preventDefault();
            event.stopPropagation();
            list.setAttribute('data-only-own-locations', toggle.checked);
        };
    });
}
