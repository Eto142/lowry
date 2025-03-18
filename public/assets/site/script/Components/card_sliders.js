import '@node/slick-carousel/slick/slick.min.js';

export default function initCardSliders() {
    document.querySelectorAll('.slider-root').forEach(function(root){
        let arrows = root.querySelector('.slider-nav');
        let amount = 6;
        let breakpoints = [
            {
                breakpoint: 2560,
                settings: {
                    slidesToShow: 5
                }
            }, {
                breakpoint: 1920,
                settings: {
                    slidesToShow: 4
                }
            },
            {
                breakpoint: 1366,
                settings: {
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 769,
                settings: {
                    slidesToShow: 1,
                }
            }
        ];

        if (root.parentNode.classList.contains('variant-boxed') || root.parentNode.classList.contains('layout-boxed')) {
            amount = 3;
            breakpoints = [
                {
                    breakpoint: 1366,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 769,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ];
        }

        // slider variant - arrows have background and on top of the items
        const slider_arrow = root.parentNode.classList.contains('arrows');

        $('.slider', root).slick({
            infinite: false,
            speed: 300,
            slidesToShow: amount,
            variableWidth: false,
            arrows: true,
            appendArrows: arrows,
            nextArrow: `<button class="nextBtn ${slider_arrow ? 'with-bg' : ''}"><i class="fa fa-${slider_arrow ? 'arrow' : 'angle'}-right"></i><span class="screenreader">` + root.dataset.labelNext + '</span></button>',
            prevArrow: `<button class="prevBtn ${slider_arrow ? 'with-bg' : ''}"><i class="fa fa-${slider_arrow ? 'arrow' : 'angle'}-left"></i><span class="screenreader">` + root.dataset.labelPrevious + '</span></button>',
            responsive: breakpoints
        });
    });

}
