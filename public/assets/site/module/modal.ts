import { FocusTrap } from './focus_trap';
import { animating } from './util';

type ExpandedState = 'false' | 'true';

export class Modal {
    setClickHandlers(button_selector:string) {
        if (! button_selector) {
            return;
        }
        const buttons = document.querySelectorAll(button_selector);
        const target_modal = document.getElementById(buttons[0].getAttribute('aria-controls'));
        const trap = new FocusTrap(target_modal.querySelector('[tabindex="0"]'));
        let open_button:HTMLButtonElement;
        let handler;
        let click_started_inside = true;

        function propagateState(state:ExpandedState) {
            target_modal.setAttribute('aria-expanded', state);
            buttons.forEach(function(button){
                button.setAttribute('aria-expanded', state);
            });
        }

        function close() {
            trap.releaseTrap();
            target_modal.dispatchEvent(new CustomEvent('close'));
            window.removeEventListener('keydown', handler);
            if (open_button) {
                open_button.focus();
            }
            animating(target_modal);
        }

        function open() {
            trap.setTrap();
            target_modal.dispatchEvent(new CustomEvent('open', {
                'detail': {
                    'data': open_button ? open_button.dataset : null
                }
            }));
            window.addEventListener('keydown', handler = (event:KeyboardEvent) => {
                if (event.code === 'Escape') {
                    propagateState('false');
                    close();
                }
            });
        }

        target_modal.addEventListener('click', event =>{
            event.stopPropagation();
            event.preventDefault();
            if (click_started_inside) {
                return;
            }
            propagateState('false');
            close();
        });

        target_modal.addEventListener('mousedown', () => {
            click_started_inside = false;
        });

        target_modal.querySelector('.modal-content').addEventListener('click', event => {
            event.stopPropagation();
        });

        target_modal.querySelector('.modal-content').addEventListener('mousedown', event => {
            click_started_inside = true;
            event.stopPropagation();
        });

        buttons.forEach((button:HTMLButtonElement) => {
            button.onclick = function(event) {
                event.preventDefault();
                event.stopPropagation();

                if (target_modal.getAttribute('aria-expanded') === 'true') {
                    propagateState('false');
                    close();
                } else {
                    open_button = button;
                    propagateState('true');
                    open();
                }
            };
        });
    }
}
