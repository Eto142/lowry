export default function initShowcaseSlideShow() {
    document.querySelectorAll('.showcase-slideshow #showcase').forEach(showcase => {
        const slider_arrow = showcase.classList.contains('arrows');

        $(showcase.querySelector('.slider-inner')).slick({
            adaptiveHeight: true,
            dots: false,
            infinite: true,
            speed: 300,
            autoplay: Number(showcase.dataset.autoplaySpeed) > 0,
            autoplaySpeed: Number(showcase.dataset.autoplaySpeed) * 1000,
            arrows: true,
            appendArrows: showcase.querySelector('.slider-nav'),
            nextArrow: `<button class="nextBtn ${slider_arrow ? 'with-bg' : ''}"><i class="fa fa-${slider_arrow ? 'arrow' : 'angle'}-right"></i><span class="screenreader">` + showcase.querySelector('.slider-nav').dataset.labelNext + '</span></button>',
            prevArrow: `<button class="prevBtn ${slider_arrow ? 'with-bg' : ''}"><i class="fa fa-${slider_arrow ? 'arrow' : 'angle'}-left"></i><span class="screenreader">` + showcase.querySelector('.slider-nav').dataset.labelPrevious + '</span></button>'
        });
    });
}
