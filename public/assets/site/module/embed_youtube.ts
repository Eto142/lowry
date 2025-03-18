declare const YT:any; // provided by the Youtube iFrame api https://developers.google.com/youtube/iframe_api_reference

import { enqueueYT } from './embed';
import { getYouTubeID } from './util';
import { EmbedMedia } from './embed_media_abstract';

export class YoutubeEmbed extends EmbedMedia {
    load() {
        if (typeof YT !== 'undefined') {
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

        // it's initialized and ready to go
        if (typeof this.player.playVideo === 'function') {
            this.player.playVideo();
            return;
        }
        // or else we need to wait for it to become ready
        this.player.addEventListener('onReady', function(){
            this.player.playVideo();
        });
    }

    pause() {
        if (!this.player) {
            return;
        }

        if (typeof this.player.pauseVideo === 'function') {
            this.player.pauseVideo();
        }
    }

    private getStartTime(url) {
        const match = url.match(/[?&]t=(\d+)/);
        if (match) {
            return parseInt(match[1]);
        }
        return 0;
    }

    private build(): Object {
        // youtube replaces the placeholder with the player
        const start = this.getStartTime(this.url);
        const player = new YT.Player(this.placeholder, {
            width: this.element.clientWidth,
            height: Math.round(this.element.clientWidth / 16 * 9),
            videoId: getYouTubeID(this.url),
            playerVars: {
                autoplay: this.mode === 'autoplay' ? 1 : 0,
                iv_load_policy: 3,
                playsinline: 1,
                disablekb: 1,
                start
            }
        });

        // ensure a 16x9 sized player that fills it's parent container
        window.addEventListener('resize', () => {
            player.setSize(this.element.clientWidth, this.element.clientWidth / 16 * 9);
        });

        return player;
    }

    api(callback): void {
        this.addScript('https://www.youtube.com/iframe_api');
        enqueueYT(callback);
    }
}
