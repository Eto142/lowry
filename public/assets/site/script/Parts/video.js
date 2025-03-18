import { embed } from '@module/embed';

export default function initVideo() {
    // inserts inline video/audio embed players in "video" parts
    document.querySelectorAll('.videoWrapper [data-video-url]').forEach(function(parent){
        embed(parent, parent.dataset.videoUrl);
    });
}
