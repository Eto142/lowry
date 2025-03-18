export class Accordion {
    el: HTMLDetailsElement;
    summary: HTMLElement | null;
    wrapper: HTMLDivElement | null;
    animation: Animation | null;

    constructor(el: HTMLDetailsElement) {
        this.el = el;
        this.summary = el.querySelector('summary');
        this.wrapper = null;
        this.animation = null;

        this.createWrapper();
        this.summary.addEventListener('click', (e) => this.handleClick(e));
    }

    get isOpen(): boolean {
        return this.el.open;
    }

    get startHeight(): string | null {
        if (this.summary && this.wrapper) {
            return this.isOpen ? `${this.summary.offsetHeight}px` : `${this.summary.offsetHeight + this.wrapper.offsetHeight}px`;
        } else {
            return null;
        }
    }

    get endHeight(): string | null {
        if (this.summary && this.wrapper) {
            return this.isOpen ? `${this.summary.offsetHeight + this.wrapper.offsetHeight}px` : `${this.summary.offsetHeight}px`;
        } else {
            return null;
        }
    }

    createWrapper(): void {
        const content = this.el.querySelectorAll('details > *:not(summary)');
        const wrapper = document.createElement('div');

        content.forEach(el => wrapper.append(el));
        this.el.append(wrapper);
        this.wrapper = wrapper;
    }

    slide(): void {
        if (this.startHeight && this.endHeight) {
            this.animation = this.el.animate({
                height: [this.startHeight, this.endHeight]
            }, {
                duration: 200,
                easing: 'ease-in-out'
            });
        }

        if (this.animation) {
            this.animation.onfinish = () => {
                this.animation = null;
                this.el.style.height = '';
                this.el.style.overflow = '';
            };
        }
    }

    handleClick(e: Event): void {
        e.preventDefault();

        if (!this.animation && this.startHeight) {
            this.el.style.overflow = 'hidden';
            this.el.style.height = this.startHeight;

            this.el.open = !this.isOpen;

            window.requestAnimationFrame(() => this.slide());
        }
    }
}
