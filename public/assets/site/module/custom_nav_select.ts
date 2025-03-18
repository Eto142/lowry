/**
 * Turn a navigation menu list into a custom dropdown
 */
export class CustomNavSelect {
    nav: HTMLElement;
    parent: HTMLElement;
    container: HTMLElement;
    triggerContainer: HTMLElement;
    items: HTMLAnchorElement[];
    current: HTMLElement | null = null;
    trigger: HTMLElement | null = null;
    isOpen: boolean = false;
    height: number;
    icon: string = '<i class="fa fa-angle-down" aria-hidden="true"></i>';
    isSet: boolean = false;

    constructor(nav: HTMLElement, parent: HTMLElement) {
        this.nav = nav;
        this.parent = parent;
        this.container = parent.querySelector('.inner');
        this.items = Array.from(this.nav.querySelectorAll('a'));
    }

    public unset(): void {
        if (this.isSet) {
            this.parent.classList.remove('anchor-menu--dropdown');

            this.removeListeners();
            if (this.trigger && this.triggerContainer) {
                this.triggerContainer.remove();
                this.trigger = null;
            }
            if (this.nav) {
                this.container.removeAttribute('style');
            }
            this.isSet = false;
            this.isOpen = false;
        }
    }

    public set(): void {
        if (!this.isSet) {
            if (!this.nav.parentNode || this.items.length < 1) {
                return;
            }

            this.parent.classList.add('anchor-menu--dropdown');
            this.buildItemList();
            this.buildTrigger();
            this.setHeight();
            this.attachListeners();
            this.isSet = true;
        }
    }

    public setCurrent(item: HTMLAnchorElement) {
        this.current = item;
    }

    public open() {
        this.parent.classList.add('is-open');
        this.trigger.setAttribute('aria-expanded', 'true');
        this.container.style.overflow = '';
        this.container.style.height = `${this.height}px`;
        this.isOpen = true;
    }

    public close() {
        this.parent.classList.remove('is-open');
        this.trigger.setAttribute('aria-expanded', 'false');
        this.container.style.overflow = 'hidden';
        this.container.style.height = '0px';
        this.isOpen = false;
    }

    private buildItemList() {
        this.parent.classList.add('anchor-menu--dropdown');
        this.current = this.items.length > 0 ? this.items[0] : null;
    }

    private buildTrigger() {
        if (this.trigger) {
            return;
        }
        const text = this.nav.getAttribute('data-trigger-label');
        this.triggerContainer = document.createElement('div');
        this.triggerContainer.classList.add('container');
        this.trigger = document.createElement('button');
        this.trigger.innerHTML = text ? `${this.icon} ${text}` : `${this.icon} Jump to section`;
        this.trigger.classList.add('anchor-menu__trigger');
        this.trigger.setAttribute('aria-controls', '#anchor-nav');
        this.trigger.setAttribute('aria-expanded', 'false');
        this.parent.insertBefore(this.triggerContainer, this.parent.firstChild);
        this.triggerContainer.insertBefore(this.trigger, this.triggerContainer.firstChild);
    }

    private setHeight() {
        this.height = this.nav.offsetHeight;
        this.container.style.height = '0px';
        this.container.style.overflow = 'hidden';
    }

    private attachListeners() {
        if (this.trigger) {
            this.trigger.addEventListener('click', (e) => this.toggleState(e));
        }
    }

    private removeListeners() {
        if (this.trigger) {
            this.trigger.removeEventListener('click', (e) => this.toggleState(e));
        }
    }

    private toggleState(e: Event) {
        e.preventDefault();
        if (this.isOpen) {
            this.close();
        } else {
            this.open();
        }
        e.stopPropagation();
    }
}
