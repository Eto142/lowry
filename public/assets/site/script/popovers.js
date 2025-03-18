export default function initPopovers() {
    let buttons = document.querySelectorAll('[aria-controls^="popover"]');

    if (! buttons) {
        return;
    }

    function closeAll(not_this_one) {
        buttons.forEach(function(b){
            if (b === not_this_one) {
                return;
            }
            b.setAttribute('aria-expanded', false);
            document.getElementById(b.getAttribute('aria-controls')).setAttribute('aria-expanded', false);
        });
    }

    buttons.forEach(function(button){
        let target_modal = document.getElementById(button.getAttribute('aria-controls'));
        let new_target = target_modal.cloneNode(true);
        target_modal.remove();
        document.body.appendChild(new_target);

        button.onclick = function(event) {
            event.preventDefault();
            event.stopPropagation();

            let button_pos = button.getBoundingClientRect();
            let opened = button.getAttribute('aria-expanded') === 'true';

            new_target.onclick = function(e) {
                e.stopPropagation();
            };

            closeAll(button);
            if (!opened) {
                button.setAttribute('aria-expanded', true);
                new_target.setAttribute('aria-expanded', true);

                window.requestAnimationFrame(function(){
                    new_target.style.left = button_pos.left + button_pos.width / 2 - new_target.getBoundingClientRect().width / 2 + 'px';
                    new_target.style.top = button_pos.bottom + window.scrollY + 'px';
                });
            } else {
                button.setAttribute('aria-expanded', false);
                new_target.setAttribute('aria-expanded', false);
            }
        };

        document.body.addEventListener('click', function(){
            closeAll();
        });
    });
}
