import { EmbedMode } from './embed';
import { Embed } from './embed_abstract';

export abstract class EmbedMedia extends Embed {
    protected mode:EmbedMode;
    protected player:any;

    constructor(element:HTMLElement, url:string, mode:EmbedMode = 'default') {
        super(element, url);
        this.mode = mode;
        this.player;

        if (!element.querySelector('.placeholder')) {
            // eslint-disable-next-line no-console
            console.error('Embeds require a child element with the .placeholder classname!');
        }
    }

    get placeholder(): HTMLElement | null {
        // we need a placeholder element to replace, or insert the embed into
        return this.element.querySelector('.placeholder') ?? null;
    }

    /**
     * Generate the embed code and replace the placeholder with it.
     */
    abstract load(): void

    /**
     * Expose a way to start the embedded media.
     */
    abstract play(): void

    /**
     * Expose a way to stop the embedded media.
     */
    abstract pause(): void
}
