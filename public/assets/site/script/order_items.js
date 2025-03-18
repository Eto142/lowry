import { datalayerDelete } from '@module/datalayer_delete';

export default function initOrderItems() {
    document.querySelectorAll('.orderList form.removeItem').forEach(form => {
        const kassacode = form.dataset.eventBoxofficeid;

        if (! kassacode) {
            return;
        }

        form.addEventListener('submit', () => {
            datalayerDelete(kassacode);
        });
    });
}
