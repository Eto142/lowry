import { VimeoEmbed } from './embed_vimeo';
import { YoutubeEmbed } from './embed_youtube';
import { SoundcloudEmbed } from './embed_soundcloud';
import { SpotifyEmbed } from './embed_spotify';
import { Embed } from './embed_abstract';
import { TallyFormEmbed } from './embed_tally';

declare global {
    interface Window {
        embeds: Object,
        youtubeQueue: Array<Function>,
        onYouTubeIframeAPIReady: Function,
        spotifyQueue: Array<Function>,
        onSpotifyIframeApiReady: Function,
    }
}

/*
    Abstractions for embedding "any" kind of URL in a placeholder element,
    and simplifies accessing those players for basic interactions
*/

// we store a global list of embeds
// so we can access all videos, for instance to pause everything
if (! window.embeds) {
    window.embeds = {};
}

/**
 * Pause all embedded media.
 */
const pauseAll = () => {
    Object.keys(window.embeds).forEach((key) => {
        window.embeds[key].pause();
    });
};

/**
 * Start an embedded media item for a given URL.
 */
const play = (url:string) => {
    if (window.embeds[url]) {
        window.embeds[url].play();
    }
};

type EmbedMode = 'default' | 'autoplay';
/**
 * Create an embedded player for a URL inside a root element.
 *
 * The root element should have a .placeholder child element,
 * which will be replaced by the embed code.
 */
const embed = (element:HTMLElement, url:string, mode:EmbedMode = 'default') => {
    let jetser:Embed;

    if (url.includes('soundcloud')) {
        jetser = new SoundcloudEmbed(element, url, mode);

    } else if (url.includes('spotify')) {
        jetser = new SpotifyEmbed(element, url, mode);

    } else if (url.includes('vimeo')) {
        jetser = new VimeoEmbed(element, url, mode);

    } else if (url.includes('tally')) {
        jetser = new TallyFormEmbed(element, url);

    } else {
        jetser = new YoutubeEmbed(element, url, mode);
    }

    window.embeds[url] = jetser;
    jetser.load();
};

/**
 * Add a YouTube video to the queue to be embedded once the API is ready.
 *
 * @param callback a function that will generate the embed code
 */
const enqueueYT = (callback) => {
    if (typeof window.youtubeQueue !== 'object') {
        window.youtubeQueue = [];
    }
    window.onYouTubeIframeAPIReady = () => {
        // called when the iFrame api is done loading
        window.youtubeQueue.forEach((callable) => {
            callable();
        });
    };

    window.youtubeQueue.push(callback);
};

/**
 * Add a Spotify player to the queue to be embedded once the API is ready.
 *
 * @param callback a function that will generate the embed code
 */
const enqueueSpotify = (callback) => {
    if (! window.spotifyQueue) {
        window.spotifyQueue = [];
    }
    if (! window.onSpotifyIframeApiReady) {
        window.onSpotifyIframeApiReady = (IFrameAPI) => {
            // called when the iFrame api is done loading
            window.spotifyQueue.forEach((callable) => {
                callable(IFrameAPI);
            });
        };
    }

    window.spotifyQueue.push(callback);
};

export { pauseAll, play, embed, EmbedMode, enqueueYT, enqueueSpotify };
