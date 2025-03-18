/**
 * Section toggler utility.
 * Setup to handle checkbox at the moment, extend as needed to make more generic
 *
 * @usage
 * const toggler = new Toggler('controlID', ['section1', 'section2'])
 */
class Toggler {
    private controller: HTMLInputElement;
    private elements: HTMLElement[];

    constructor(controllerId: string, elementIds: string[]) {
        this.controller = document.getElementById(controllerId) as HTMLInputElement;
        this.elements = elementIds.map(id => document.getElementById(id) as HTMLElement);

        if (!this.controller || this.elements.some(element => !element)) {
            return;
        }

        this.updateVisibility();

        this.controller.addEventListener('change', () => this.updateVisibility());
    }

    public unset() {
        if (this.controller) {
            this.controller.removeEventListener('change', () => this.updateVisibility());
        }
    }

    private updateVisibility() {
        const isVisible = this.controller.checked;

        const element1 = this.elements && this.elements[0];

        if (element1) {
            element1.style.display = isVisible ? 'none' : 'block';
            element1.setAttribute('aria-hidden', isVisible ? 'true' : 'false');
        }

        const element2 = this.elements && this.elements[1];

        if (element2) {
            element2.style.display = isVisible ? 'block' : 'none';
            element2.setAttribute('aria-hidden', isVisible ? 'false' : 'true');
        }
    }
}

export { Toggler };
