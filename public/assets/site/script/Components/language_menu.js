export default function initLanguageMenu() {
    const language_popup = document.getElementById('language-popup');

    document.querySelectorAll('[aria-controls="language-popup"]').forEach(function(button) {
        button.addEventListener('click', function() {
            language_popup.style.right = document.documentElement.clientWidth - button.getBoundingClientRect().right + button.getBoundingClientRect().width / 2 + 'px';
            language_popup.style.top = button.getBoundingClientRect().bottom + 'px';
        });
    });
}
