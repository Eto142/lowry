export default function initTicketListExtras() {
    document.querySelectorAll('.extras-toggle button').forEach(button => {
        button.onclick = event => {
            event.preventDefault();
            event.stopPropagation();

            const target = document.getElementById(button.getAttribute('aria-controls'));

            if (target.getAttribute('aria-expanded') === 'true') {
                target.setAttribute('aria-expanded', 'false');
                button.setAttribute('aria-expanded', 'false');
                button.classList.add('btn-active');
                button.classList.remove('btn-default');
            } else {
                target.setAttribute('aria-expanded', 'true');
                button.setAttribute('aria-expanded', 'true');
                button.classList.remove('btn-active');
                button.classList.add('btn-default');
            }
        };
    });
}
