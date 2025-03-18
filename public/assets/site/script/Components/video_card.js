import { embed } from '@module/embed';

export default function initVideoCards() {
    // video in show list cards
    document.querySelectorAll('.thumb[data-video-url]').forEach((parent) => {
        let button = parent.querySelector('button');

        button.onclick = function(event) {
            event.preventDefault();
            event.stopPropagation();

            parent.classList.add('video-loaded');
            embed(parent, parent.dataset.videoUrl, 'autoplay');
            button.remove();
        };
    });
}
