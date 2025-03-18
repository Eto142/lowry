import { Accordion } from '@module/accordion';

export default function initAccordions() {
    document.querySelectorAll('.richtext details').forEach((el) => {
        const bannerTemplate = document.getElementById('icon-chevron');
        if (bannerTemplate) {
            const node = el.querySelector('summary');
            node.prepend(bannerTemplate.content.cloneNode(true));
        }

        new Accordion(el);
    });

    document.querySelectorAll('.collapsed-heading details').forEach((el) => {
        new Accordion(el);
    });
}
