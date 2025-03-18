import { embed, play } from '@module/embed';

export default function initEventHeader() {
    let root_node = document.querySelector('#showHeaderImage, .headerImgContainer');
    if (! root_node) {
        return false;
    }

    let play_button = root_node.querySelector('.video-play-button');

    if (play_button) {
        play_button.onclick = function() {
            let video_parent = root_node.querySelector('[data-video-url]');
            let video_url = video_parent.dataset.videoUrl;

            embed(video_parent, video_url, 'autoplay');
            root_node.classList.add('video-started');

            // most browsers don't allow starting a video like this, but we try anyway
            play(video_url);
        };
    }
}
