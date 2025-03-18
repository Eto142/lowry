declare const Vimeo:any; // provided by the Vimeo api https://developer.vimeo.com/player/sdk/basics

import { rgbToHex } from './util';
import { EmbedMedia } from './embed_media_abstract';

export class VimeoEmbed extends EmbedMedia {
    load() {
        if (typeof Vimeo !== 'undefined') {
            this.player = this.build();
        } else {
            this.api(() => {
                this.player = this.build();
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

    private getStartTime(url) {
        const match = url.match(/[?#&]t=(\d+h)?(\d+m)?(\d+s)?/);
        if (match) {
            const hours = match[1] ? parseInt(match[1]) : 0;
            const minutes = match[2] ? parseInt(match[2]) : 0;
            const seconds = match[3] ? parseInt(match[3]) : 0;
            return hours * 3600 + minutes * 60 + seconds;
        }
        return undefined;
    }

    private build(): Object {
        const startTime = this.getStartTime(this.url);
        const player = new Vimeo.Player(this.placeholder?.parentNode, {
            url: this.url,
            width: this.element.clientWidth,
            height: Math.round(this.element.clientWidth / 16 * 9),
            byline: false,
            title: false,
            autoplay: this.mode === 'autoplay' ? true : false,
            color: rgbToHex(window.getComputedStyle(this.element).color),
        });
        // vimeo inserts the player in the placeholder
        this.placeholder?.remove();

        // vimeo auto-scales to the iframe so make sure it's 16/9
        window.addEventListener('resize', () => {
            this.element.querySelector('iframe').height = (this.element.clientWidth / 16 * 9).toString();
        });

        // Set start time of video
        if(startTime){
            player.setCurrentTime(startTime);
        }

        return player;
    }

    api(callback): void {
        this.addScript('https://player.vimeo.com/api/player.js', callback);
    }
}
