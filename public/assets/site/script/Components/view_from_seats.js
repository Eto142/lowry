/**
 * See https://integrate.spektrix.com/docs/viewfromseat
 * Spektrix expects this function to be exposed globally
 * so we must attach it to the window and include this file
 * without a standard ES export in our entrypoint
 */

/* global GLightbox */

const processTooltip = (tip) => {
    try {
        return decodeURIComponent(tip);
    } catch {
        return tip;
    }
};

function spekShowViewFromSeat(url, tooltip) {
    const assetBase = document.getElementById('SpektrixIFrame')?.getAttribute('data-spektrix-asset-base');

    if (assetBase) {
        const gallery = GLightbox({
            draggable: false,
            zoomable: false,
            elements: [
                {
                    'href': `${assetBase}/${url}`,
                    'type': 'image',
                    'description': processTooltip(tooltip)
                }
            ]
        });

        gallery.open();
    }
}

window.spekShowViewFromSeat = spekShowViewFromSeat;
