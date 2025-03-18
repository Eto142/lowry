/*
    Prevent multiple requests because they aren't deduplicated in the
    backend, which can cause duplicate reservations in the ticketing
    system.
    Even if the backend would deal with this properly, it's not a bad idea
    to prevent multiple submits. It might really take a while for this
    request to return, and we want to tell users:
    "don't worry, we're working on it"
*/
export default function initSubmitLockout() {
    document.querySelectorAll('form[data-double-click-prevention]').forEach(function(form){
        form.addEventListener('submit', function(){
            form.classList.add('is-submitted');
            form.querySelectorAll('button').forEach(function(button){
                button.setAttribute('disabled', true);
            });
        });
    });
    // select payment method submit/continue button sits outside the form
    document.querySelectorAll('#selectPayment').forEach(function(button){
        button.addEventListener('click', function() {
            button.setAttribute('disabled', true);
            document.querySelector('#paymentForm').classList.add('is-submitted');
        });
    });
}
