let err_counter = 0;
function displayErrors(errors) {
    $('.err-msg').text('');
    $('.err-msg').siblings('input, select').removeClass('error');

    $.each(errors, function(field, messages) {
        let errMsgSelector = '.err-' + field;
        let inputSelector = '#' + field;
        $(errMsgSelector).text(messages[0]);
        $(inputSelector).addClass('error');
    });
}