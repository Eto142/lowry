/**
 * Delete array type values, e.g. "genres[]"
 */
function deleteArray(key, params) {
    const target_params = [];
    for (const entry of params.entries()) {
        // entry = [key, value]
        if (entry[0].indexOf(key) > -1) {
            target_params.push(entry[0]);
        }
    }

    target_params.forEach((param) => {
        params.delete(param);
    });
}

export default function initFilterResets() {
    document.querySelectorAll('[data-reset]').forEach(button => {
        button.onclick = e => {
            e.preventDefault();
            e.stopPropagation();

            const uri = new URL(window.location);
            const params = new URLSearchParams(uri.search);

            switch (button.dataset.reset) {
            case 'date_range':
                params.delete('start');
                params.delete('end');
                uri.search = params.toString();
                break;
            case 'production_type':
                params.delete('production_type');
                uri.search = params.toString();
                break;
            case 'genres':
                params.delete('genre');
                params.delete('genres');
                deleteArray('genres', params);
                uri.search = params.toString();
                if (button.dataset.slug && uri.pathname.includes(button.dataset.slug)) {
                    uri.pathname = uri.pathname.replace('/' + button.dataset.slug, '');
                }
                break;
            case 'locations':
                params.delete('location');
                params.delete('locations');
                params.delete('locationselect');
                deleteArray('locations', params);
                deleteArray('locationselect', params);
                uri.search = params.toString();
                break;
            case 'themes':
                params.delete('theme');
                params.delete('themes');
                deleteArray('themes', params);
                uri.search = params.toString();
            case 'all':
                uri.search = '';
                break;
            }

            window.location = uri.href;
        };
    });
}
