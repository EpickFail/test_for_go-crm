function ValidPhone() {
    var re = /^\d[\d\(\)\ -]{9}\d$/;
    var myPhone = $('#phone').val();
    var valid = re.test(myPhone);
    return valid;
}

function ValidMail() {
    var re = /^[\w-\.]+@[\w-]+\.[a-z]{2,6}$/i;
    var myMail = $('#email').val();
    var valid = re.test(myMail);
    return valid;
}
