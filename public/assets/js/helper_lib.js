/* 
    require : 'YYYY-MM-DD'
    return : 'DD-MM-YYYY'
*/
function datefsql(date) {
    if (date !== undefined && date !== null && date !== 'null') {
        var elem = date.split('-');
        var tahun = elem[0];
        var bulan = elem[1];
        var hari = elem[2];
        return hari + '/' + bulan + '/' + tahun;
    } else {
        return '';
    }
}

function validFeedback(inputEl, message) {
    // validFeedback('#old_pw_change', 'Please provide a valid password');

    const inputElement = $(inputEl);
    const feedbackElement = inputElement.siblings('.valid-feedback');

    if (!feedbackElement.length) {
        inputElement.after('<div class="valid-feedback"><small>' + message + '</small></div>');
    }

    inputElement.removeClass('is-invalid');
    inputElement.addClass('is-valid');
    feedbackElement.hide();
}

function invalidFeedback(inputEl, message) {
    const inputElement = $(inputEl);
    const feedbackElement = inputElement.siblings('.invalid-feedback');

    if (!feedbackElement.length) {
        inputElement.after('<div class="invalid-feedback"><small>' + message + '</small></div>');
    }

    inputElement.removeClass('is-valid');
    inputElement.addClass('is-invalid');
    feedbackElement.text(message).show();
}

function removeFeedback(inputEl) {
    const inputElement = $(inputEl);
    const feedbackElement = inputElement.siblings('.invalid-feedback');

    inputElement.removeClass('is-valid');
    inputElement.removeClass('is-invalid');
    feedbackElement.hide();
}


