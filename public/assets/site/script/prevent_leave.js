/* global pep_global_config */

/*
    Prevent from accidentally leaving a test environment
    by clicking a non-relative link to the live site.
    Tries to throw a confirmation message if you do.
*/

export default function initPreventLeave() {
    if (pep_global_config['app_environment'] !== 'prod' && typeof pep_global_config['app_environment'] !== 'undefined') {
        document.querySelectorAll('a[href]').forEach(function(link){
            if (typeof pep_global_config['http_host'] !== 'undefined' && link.hostname.toLowerCase() !== pep_global_config['http_host']) {
                link.addEventListener('click', function(event) {
                    // eslint-disable-next-line no-alert
                    if (!window.confirm('You are about to leave the ' + pep_global_config['app_environment'] + ' environment for this website and go to:\n' + link.href)) {
                        event.preventDefault();
                    }
                });
            }
        });
    }
}
