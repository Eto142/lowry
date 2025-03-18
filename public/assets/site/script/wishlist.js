export default function initWishlist() {
    const wishlist_form = document.querySelector('form.wishlistForm');

    if (wishlist_form) {
        wishlist_form.addEventListener('submit', () => {
            document.querySelector('.spinner-wrapper').style.display = null;
        });
    }
}
