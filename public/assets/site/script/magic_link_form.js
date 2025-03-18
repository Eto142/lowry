export default function initMagicLinkForm() {
    const button = document.getElementById('magic_link_form_save');

    if (!button) {
        return;
    }

    const bar = document.createElement('span');
    bar.classList.add('timer-bar');
    button.appendChild(bar);

    const timer = window.setTimeout(() => {
        button.click();
    }, 20000);

    const clear = () => {
        window.clearTimeout(timer);
        bar.remove();
    };

    button.addEventListener('mouseenter', () => {
        clear();
    });

    document.querySelectorAll('input[type="password"]').forEach(input => {
        input.addEventListener('focus', () => {
            clear();
        });
    });

    window.addEventListener('keyup', (event) => {
        if (event.code === 'Escape') {
            clear();
        }
        if (event.code === 'Tab') {
            clear();
        }
    });
}
