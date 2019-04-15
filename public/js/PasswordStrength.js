'use strict';

const zxcvbn = require('zxcvbn');


class PasswordStrength {

    constructor(config, passwordField, meter, textDiv) {
        this.config = config;
        this.passwordField = passwordField;
        this.meter = meter;
        this.textDiv = textDiv;

    }

    init() {
        let container = document.getElementById('meter');
        if(this.passwordField && container) {
            this.passwordField.addEventListener('input', this.updateMeterAndText.bind(this));
            this.passwordField.parentNode.parentNode.appendChild(container);
        }
    }

    getScore() {
        return zxcvbn(this.passwordField.value).score;
    }

    updateMeterAndText() {
        let score = this.getScore();
        this.meter.value = score;

        var regex = /dog/gi;

        if (this.passwordField.val !== "" && this.config.displayText) {
            this.textDiv.innerHTML = this.sprintf(this.config.text, this.config.strength[score]);
        } else {
            this.textDiv.innerHTML = "";
        }

    }

    sprintf(format) {
        let args = Array.prototype.slice.call(arguments, 1);
        let i = 0;
        return format.replace(/%s/g, function () {
            return args[i++];
        });
    }
}

module.exports = PasswordStrength;


