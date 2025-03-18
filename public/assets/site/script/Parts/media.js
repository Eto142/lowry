/* global GLightbox */

export default function initMediaSlider() {
    // init slideshow
    $('.mediaSlider').each(function(){
        let slider_element = $(this);
        let centered = true;
        let n_slides = 1;

        if (! slider_element.data().centered || slider_element.data().centered === 'false') {
            centered = false;
        }
        if (slider_element.data().numberVisible) {
            n_slides = Number(slider_element.data().numberVisible);
        }

        // slider variant - arrows have background and on top of the items
        const slider_arrow = this.parentNode.classList.contains('arrows');

        slider_element.slick({
            dots: false,
            infinite: true,
            speed: 300,
            slidesToShow: n_slides,
            centerMode: centered,
            variableWidth: true,
            arrows: true,
            appendArrows: '.arrowsMedia[data-part-id="' + slider_element.data().partId + '"]',
            nextArrow: `<button class="nextBtn ${slider_arrow ? 'with-bg' : ''}"><i class="fa fa-${slider_arrow ? 'arrow' : 'angle'}-right"></i><span class="screenreader">` + slider_element.data().labelNext + '</span></button>',
            prevArrow: `<button class="prevBtn ${slider_arrow ? 'with-bg' : ''}"><i class="fa fa-${slider_arrow ? 'arrow' : 'angle'}-left"></i><span class="screenreader">` + slider_element.data().labelPrevious + '</span></button>'
        });
    });

    // glight lightbox settings
    GLightbox({
        selector: '.glightbox3',
        loop: true,
        zoomable: false
    });
}
