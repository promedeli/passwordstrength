const PasswordStrength = require('./PasswordStrength');

window.addEventListener("load", function() {

    let passwordField = document.getElementsByName('password')[0];
    let meter = document.getElementById('password-strength-meter');
    let textDiv = document.getElementById('password-strength-text');

    let psm = new PasswordStrength(window.passwdStrengthConfig, passwordField, meter, textDiv);
    psm.init();

});
