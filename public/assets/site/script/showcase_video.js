/* global YT, Vimeo */

import { enqueueYT } from '@module/embed';
import { getYouTubeID } from '@module/util';

export default function initShowcaseVideo() {
    document.querySelectorAll('.showcaseplayer').forEach(parent => {
        const url = parent.dataset.videoUrl;
        const showcase_wrapper = document.getElementById('showcase') ?? parent.parentNode;
        const controlButtonTemplate = `
          <button type="button" class="video-control"
                aria-label="${parent.dataset.pauseLabel}"
                aria-pressed="false"
            >
            <i class="fa fa-pause" aria-hidden="true"></i>
          </button>
        `;
        let video;
        let isPlaying = false;
        let player;
        let show_video_on_mobile = parent.dataset.videoOnMobile.toLowerCase() === 'true';
        let aspect_ratio = 16 / 9;

        const playerDimensions = {
            width: parent.clientWidth,
            height: Math.round(parent.clientWidth / 16 * 9)
        };

        aspect_ratio = showcase_wrapper.clientHeight / showcase_wrapper.clientWidth;

        if (!show_video_on_mobile && aspect_ratio.toFixed(1) >= 0.7) {
            return;
        }

        function scaleVideo() {
            if (!video) {
                return;
            }

            let parent_aspect = parent.clientWidth / parent.clientHeight;
            let vid_aspect = 16 / 9;

            if (parent_aspect > vid_aspect) {
                // parent is landscape-ishier than the video
                video.style.transform = 'scale(' + parent.getBoundingClientRect().width / video.clientWidth + ')';
            } else {
                video.style.transform = 'scale(' + parent.getBoundingClientRect().height / video.clientHeight + ')';
            }
        }
        function muteYoutube(event) {
            event.target.mute();
        }
        function handleYTState(event) {
            let YTP = event.target;
            let rewindTO;

            if (event.data === YT.PlayerState.PLAYING){
                let remains = YTP.getDuration() - YTP.getCurrentTime();
                if (rewindTO) {
                    clearTimeout(rewindTO);
                }
                // To remove black screen end of the loop videos - rewind the video with seeking to a frame.
                rewindTO = setTimeout(function(){
                    YTP.seekTo(0);
                }, (remains - 0.3) * 1000);
                parent.classList.add('playing');
                isPlaying = true;
                video = parent.children[0];
                scaleVideo();
            }
        }

        function addPauseButton() {
            let item_wrapper = showcase_wrapper.querySelector('.has-video');
            if (!item_wrapper) {
                item_wrapper = showcase_wrapper;
            }

            // attach the pause button to the container if layout boxed
            if (item_wrapper.classList.contains('size-boxed')) {
                item_wrapper.querySelector('.container')?.insertAdjacentHTML('beforeend', controlButtonTemplate);
            } else {
                item_wrapper.insertAdjacentHTML('beforeend', controlButtonTemplate);
            }

            return showcase_wrapper.querySelector('.video-control');
        }

        const controlButton = addPauseButton();

        const updateControlUi = (button, playing) => {
            if (!button) {
                return;
            }
            if (playing) {
                button.innerHTML = `<i class="fa fa-pause"></i>`;
                button.setAttribute('aria-label', 'Pause video');
                button.setAttribute('aria-pressed', 'true');
            } else {
                button.innerHTML = `<i class="fa fa-play"></i>`;
                button.setAttribute('aria-label', 'Play video');
                button.setAttribute('aria-pressed', 'false');
            }
        };

        function toggleVimeo(vimPlayer) {
            if (isPlaying) {
                vimPlayer.pause().then(() => {
                    isPlaying = false;
                    updateControlUi(controlButton, isPlaying);
                });
            } else {
                vimPlayer.play().then(() => {
                    isPlaying = true;
                    updateControlUi(controlButton, isPlaying);
                });
            }
        }

        function toggleYouTube(ytPlayer) {
            if (isPlaying) {
                ytPlayer.pauseVideo();
                isPlaying = false;
                updateControlUi(controlButton, isPlaying);
            } else {
                ytPlayer.playVideo();
                isPlaying = true;
                updateControlUi(controlButton, isPlaying);
            }
        }

        function startVimeo() {
            // vimeo inserts the player in the placeholder
            parent.querySelector('.placeholder').remove();
            player = new Vimeo.Player(parent, {
                ...playerDimensions,
                url: url,
                background: true,
                byline: false,
                muted: true,
                title: false
            });
            player.on('play', function() {
                parent.classList.add('playing');
                video = parent.children[0];
                isPlaying = true;
                scaleVideo();
            });

            player.on('error', function() {
                parent.remove();
            });
            player.on('pause', function() {
                // SM - This pause callback totally removes the video from the DOM
                // when we click on our manual pause control.
                // autoplay is blocked by the browser so remove the player
                if (!controlButton) {
                    parent.remove();
                }
            });

            controlButton?.addEventListener('click', () => toggleVimeo(player));
            controlButton?.addEventListener('keydown', function(event) {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    toggleVimeo(player);
                }
            });
        }

        window.addEventListener('resize', () => {
            scaleVideo();
        });

        if (url.includes('vimeo')) {
            if (typeof Vimeo !== 'undefined') {
                startVimeo();
            } else {
                const tag = document.head.querySelector('[src="https://player.vimeo.com/api/player.js"]');
                if (! tag) {
                    const script = document.createElement('script');
                    script.setAttribute('src', 'https://player.vimeo.com/api/player.js');
                    script.onload = startVimeo;
                    document.head.appendChild(script);
                } else {
                    tag.addEventListener('load', startVimeo);
                }
            }

        } else {
            const tag = document.head.querySelector('[src="https://www.youtube.com/iframe_api"]');
            if (! tag) {
                const script = document.createElement('script');
                script.setAttribute('src', 'https://www.youtube.com/iframe_api');
                document.head.appendChild(script);
            }
            enqueueYT(() => {
                const ytPlayer = new YT.Player(parent.querySelector('.placeholder'), {
                    ...playerDimensions,
                    videoId: getYouTubeID(url),
                    events: {
                        'onReady': muteYoutube,
                        'onError': parent.remove,
                        'onStateChange': handleYTState
                    },
                    playerVars: {
                        autoplay: 1,
                        controls: 0,
                        iv_load_policy: 3,
                        modestbranding: 1,
                        playsinline: 1,
                        loop: 1,
                        playlist: getYouTubeID(url)
                    }
                });

                controlButton?.addEventListener('click', () => toggleYouTube(ytPlayer));

                controlButton?.addEventListener('keydown', function(event) {
                    if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault();
                        toggleYouTube(ytPlayer);
                    }
                });
            });
        }
    });
}
