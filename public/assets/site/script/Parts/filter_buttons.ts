const submitForm = (form: HTMLFormElement) => {
    const optionCheckboxes = form.querySelectorAll('input');

    optionCheckboxes.forEach(option => {
        option.onclick = () => {
            form.submit();
        };
    });
};

const handleShowMoreBtnClick = (form: HTMLFormElement) => {
    const options: HTMLLIElement[] = Array.from(form.querySelectorAll('li'));
    const firstHiddenOption = form.querySelector('li.hidden input') as HTMLInputElement;
    const showMoreBtn: HTMLButtonElement | null = form.querySelector('.show-more-btn');

    if (showMoreBtn) {
        showMoreBtn.onclick = (event) => {
            event.preventDefault();
            event.stopPropagation();

            options.forEach(option => {
                option.classList.remove('hidden');
            });

            showMoreBtn.classList.add('hidden');
            firstHiddenOption.focus();
        };
    }
};

export default function initFilterButtons() {
    const filtersForm: HTMLFormElement[] = Array.from(document.querySelectorAll('.filter-buttons form'));

    filtersForm.forEach((form) => {
        handleShowMoreBtnClick(form);
        submitForm(form);
    });
}
