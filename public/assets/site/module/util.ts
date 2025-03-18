/*
    a re-usable function for waiting for transitionend
    transitionend famously is not always fired so we also wait for a timeout
    at that timeout we clean up the eventlistener
    sets an ".animating" class that can be used in stylesheets
*/
const animating = (element: HTMLElement, wait:number = 500): void => {
    let anim;

    element.classList.add('animating');
    element.addEventListener('transitionend', anim = () => {
        element.classList.remove('animating');
    });
    window.setTimeout(() => {
        element.classList.remove('animating');
        element.removeEventListener('transitionend', anim);
    }, wait);
};

/*
    window.getComputedStyle returns colors in rgb,
    but sometimes you need a hex value (e.g. in some embed api's)
    this function does the conversion
*/
const rgbToHex = (rgb): string => {
    function convert(color:string): string {
        let hex = Number(color).toString(16);
        if (hex.length < 2) {
            hex = '0' + hex;
        }
        return hex;
    }

    let regex = /^.*\((\d+), (\d+), (\d+)/;
    let colors = rgb.match(regex);
    let red = convert(colors[1]);
    let green = convert(colors[2]);
    let blue = convert(colors[3]);

    return red + green + blue;
};

// because YT has a pretty useless oembed endpoint, we resort to regex
// adapted from: https://gist.github.com/takien/4077195
const getYouTubeID = (yt_url:string): string => {
    let id = '';
    const url = yt_url.replace(/(>|<)/gi, '').split(/(vi\/|v=|\/v\/|youtu\.be\/|\/embed\/|\/shorts\/)/);
    if (url[2] !== undefined) {
        id = url[2].split(/[^0-9a-z_-]/i)[0];
    }
    if (id) {
        return id;
    } else {
        // it the url wasn't parseable perhaps it was just a video id
        return yt_url;
    }
};

const getSpotifyURI = (url:string): string => {
    const regex = url.match(/^\S+spotify\.com\/([^/]+)\/([^/?#]+)/);
    return 'spotify:' + regex[1] + ':' + regex[2];
};

export { animating, rgbToHex, getYouTubeID, getSpotifyURI };
