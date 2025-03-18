// function to fill the extras selectbox according to the
// number of tickets selected for the main event.
function fillExtrasSelect() {
    let sum = 0;

    $('#ticketsForm-kaartsoorten select').each(function() {
        sum += parseInt($(this).val());
    });

    // remove all extra options
    $('#ticketsForm-extras select>option').remove();

    // and then add exactly the number of options there should be
    let optiontemplate = '<option val=XVAL>XVAL</option>';
    for (let i = 0; i <= sum; i++) {
        $('#ticketsForm-extras select').append(optiontemplate.replace(/XVAL/g, i));
    }
}


// call once onload
export { fillExtrasSelect };
