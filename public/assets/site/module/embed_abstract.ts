/**
 * Base class to generate embed code for a given URL.
 *
 * Derived classes can take any approach (oEmbed, API, etc),
 * as long as they implement the interface defined here.
 *
 * Expects a root element with a placeholder element that we can replace.
 */
export abstract class Embed {
    protected element:HTMLElement;
    protected url:string;

    constructor(element:HTMLElement, url:string) {
        this.element = element;
        this.url = url;

        if (!element.querySelector('.placeholder')) {
            // eslint-disable-next-line no-console
            console.error('Embeds require a child element with the .placeholder classname!');
        }
    }

    get placeholder(): HTMLElement | null {
        // we need a placeholder element to replace, or insert the embed into
        return this.element.querySelector('.placeholder') ?? null;
    }

    addScript(url: string, callback?) {
        const tag = document.head.querySelector(`[src="${url}"]`);

        if (!tag) {
            const script = document.createElement('script');
            script.setAttribute('src', `${url}`);
            script.setAttribute('async', 'true');
            script.setAttribute('defer', 'true');

            if (callback) {
                script.onload = callback;
            }
            
            document.head.appendChild(script);
        } else {
            tag.addEventListener('load', callback);
        }
    }

    /**
     * Generate the embed code and replace the placeholder with it.
     */
    abstract load(): void

    /**
     * Add script tag to page
     */
    abstract api(callback?: Function): void
}
