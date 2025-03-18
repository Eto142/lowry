export default function initUserMenu(){
    let user_popup = document.getElementById('user-popup');

    document.querySelectorAll('[aria-controls="user-popup"]').forEach(button => {
        button.addEventListener('click', () => {
            let offset = document.documentElement.clientWidth - button.getBoundingClientRect().right + button.getBoundingClientRect().width / 2;
            if (offset < 60) {
                offset = 60; // because we have a negative margin here and it would fall of the screen
            }
            user_popup.style.right = offset + 'px';
            user_popup.style.top = button.getBoundingClientRect().bottom + 'px';
        });
    });
}
