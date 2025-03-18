declare const Tally: any;

import { Embed } from './embed_abstract';

export class TallyFormEmbed extends Embed {
    private iFrameEl: HTMLIFrameElement;
    private config = {
        'data-tally-src': `${this.url}`,
        loading: 'lazy',
        width: '100%',
        height: '300',
        frameborder: '0',
        marginheight: '0',
        marginwidth: '0'
    };

    addIframeEl(): void {
        this.iFrameEl = document.createElement('iframe');

        Object.entries(this.config).forEach(([option, value]) => {
            this.iFrameEl.setAttribute(option, value);
        });

        this.placeholder?.replaceWith(this.iFrameEl);
    }

    load(): void {
        this.addIframeEl();
        this.api(() => this.build());
    }

    build(): void {
        if (typeof Tally !== 'undefined') {
            Tally.loadEmbeds();
        }
    }

    api(callback): void {
        this.addScript('https://tally.so/widgets/embed.js', callback);
    }
}