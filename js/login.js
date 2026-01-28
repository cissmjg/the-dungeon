export function checkForm(loginForm) {
    let pwdField = $('#' + "password");
    let pwdValue = pwdField.val();
    if (pwdValue.length == 0) {
        alert('Please enter a password');
        return false;
    }

    loginForm.submit();
}

window.checkForm = checkForm;