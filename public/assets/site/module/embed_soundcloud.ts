declare const SC:any; // provided by the Soundcloud widget api https://developers.soundcloud.com/docs/api/html5-widget

import { rgbToHex } from './util';
import { EmbedMedia } from './embed_media_abstract';

export class SoundcloudEmbed extends EmbedMedia {
    load() {
        if (typeof SC !== 'undefined') {
            this.build();
        } else {
            this.api(() => {
                this.build();
            });
        }
    }

    play() {
        if (!this.player) {
            return;
        }

        if (typeof this.player.play === 'function') {
            this.player.play();
        }
    }

    pause() {
        if (!this.player) {
            return;
        }

        if (typeof this.player.pause === 'function') {
            this.player.pause();
        }
    }

    private setPlayer(): void {
        this.player = SC.Widget(this.element.querySelector('iframe'));
    }

    private build(): void {
        // we're using the oEmbed wrapper
        // https://developers.soundcloud.com/docs/api/sdks#oEmbed
        SC.oEmbed(this.url, {
            element: this.placeholder,
            show_comments: false,
            color: rgbToHex(window.getComputedStyle(this.element).color)
        }).then(() => {
            // ready loading the embed, now create a player widget we can manipulate
            this.setPlayer();
        });
    }

    api(callback): void {
        /*
            if the api hasn't been loaded yet, add the script tag to the document
            and via the callback finally initialize the embed
            or if the script has already been added but hasn't loaded yet,
            add the callback to be called when it finishes doing do
        */

        const tag = document.head.querySelector('[src="https://connect.soundcloud.com/sdk/sdk-3.3.2.js"]');
        if (! tag) {
            const sdk = document.createElement('script');
            sdk.setAttribute('src', 'https://connect.soundcloud.com/sdk/sdk-3.3.2.js');
            sdk.setAttribute('async', 'true');
            sdk.setAttribute('defer', 'true');
            sdk.onload = callback;

            const script = document.createElement('script');
            script.setAttribute('src', 'https://w.soundcloud.com/player/api.js');
            script.setAttribute('async', 'true');
            script.setAttribute('defer', 'true');

            document.head.appendChild(sdk);
            document.head.appendChild(script);
        } else {
            tag.addEventListener('load', callback);
        }
    }
}
