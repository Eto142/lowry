export default function initReviews() {
    document.querySelectorAll('.reviewSlider').forEach(slider => {
        const slider_arrow = slider.parentNode.classList.contains('arrows');

        $(slider).slick({
            dots: false,
            infinite: true,
            speed: 300,
            slidesToShow: 1,
            adaptiveHeight: true,
            arrows: true,
            appendArrows: slider.parentNode.querySelector('.arrows'),
            nextArrow: `<button class="nextBtn ${slider_arrow ? 'with-bg' : ''}"><i class="fa fa-${slider_arrow ? 'arrow' : 'angle'}-right"></i><span class="screenreader">` + slider.dataset.labelNext + '</span></button>',
            prevArrow: `<button class="prevBtn ${slider_arrow ? 'with-bg' : ''}"><i class="fa fa-${slider_arrow ? 'arrow' : 'angle'}-left"></i><span class="screenreader">` + slider.dataset.labelPrevious + '</span></button>',
        });
    });
}
