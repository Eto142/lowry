import { enqueueSpotify } from './embed';
import { getSpotifyURI } from './util';
import { EmbedMedia } from './embed_media_abstract';

/*
    Spotify provides a limited API only for podcasts:
    https://developer.spotify.com/documentation/embeds/references/iframe-api/
    For other embeds we simply insert an iframe via oEmbed.

    For more control we _can_ enable the full api on that iframe,
    similar to what we do with soundcloud, but there
    is really no need for programmatic control (play/pause) right now.
    https://developer.spotify.com/documentation/web-api/
*/

export class SpotifyEmbed extends EmbedMedia {
    load() {
        if (this.url.includes('episode')) {
            this.api();
        } else {
            fetch('https://open.spotify.com/oembed?url=' + this.url).then(response => {
                if (!response.ok) {
                    throw new Error('HTTP status: ' + response.status);
                }
                return response.json();
            }).then(data => {
                if (data['html']) {
                    const target = <HTMLElement>this.placeholder?.parentNode;
                    target.innerHTML = data['html'];
                }
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
        // there is no real pause method in this api
    }

    api() {
        this.addScript('https://open.spotify.com/embed-podcast/iframe-api/v1');
        
        enqueueSpotify((IFrameAPI) => {
            IFrameAPI.createController(
                this.placeholder,
                {
                    uri: getSpotifyURI(this.url)
                },
                (EmbedController) => {
                    this.player = EmbedController;
                }
            );
        });
    }
}
