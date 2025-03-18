export default function initRichtextAnchors(){
    $('.richtext a[href*=\\#]').on('click', function(event){
        let fixed_element = document.getElementById('fixed-box');
        let header_height = fixed_element.clientHeight;
        let target = event.target.hash.replace(/^#/, '');
        let target_element = document.querySelector('[name="' + target + '"]');

        if (target_element) {
            let target_top = target_element.getBoundingClientRect().top;
            event.preventDefault();
            event.stopPropagation();

            window.scrollTo({
                top: target_top + window.scrollY - header_height,
                left: 0,
                behavior: 'smooth'
            });
        }
    });
}
