/*
    Because :invalid pseudo class is set before the user had a chance to
    interact with the form, it cannot be used to present validation feedback.
    Therefore, we add a class after the user touched a field and
    left it invalid. This class can then be used to present feedback before
    the form is submitted (which will trigger the browser's own validation and
    feedback)
*/
export default function initFormCheckValidationMsg() {
    document.querySelectorAll('form :required').forEach(function(element){
        let time_out;

        element.addEventListener('blur', function(){
            if (! element.checkValidity()) {
                element.classList.add('checked-invalid');
            } else {
                element.classList.remove('checked-invalid');
            }
        });
        element.addEventListener('input', function(){
            window.clearTimeout(time_out);
            time_out = window.setTimeout(function(){
                if (! element.checkValidity()) {
                    element.classList.add('checked-invalid');
                } else {
                    element.classList.remove('checked-invalid');
                }
            }, 1500);
        });
    });
}
