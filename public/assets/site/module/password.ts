import PasswordStrength from 'tai-password-strength/lib/password-strength.js';

type whichType = 'first' | 'second';

/**
 * Password strength validation
 * using http://tests-always-included.github.io/password-strength/
*/
export class Password {
    private PasswordStrength;
    private strength: Number;
    private time_out;
    private new_pw: HTMLInputElement;
    private new_pw_check: HTMLInputElement;
    private warning_length: string;
    private warning_mismatch: string;
    private warning_strength: string;

    constructor(
        strength: Number,
        new_pw: HTMLInputElement,
        new_pw_check: HTMLInputElement,
        warning_length: string,
        warning_mismatch: string,
        warning_strength: string
    ) {
        this.PasswordStrength = new PasswordStrength();
        this.strength = strength;
        this.new_pw = new_pw;
        this.new_pw_check = new_pw_check;
        this.warning_length = warning_length;
        this.warning_mismatch = warning_mismatch;
        this.warning_strength = warning_strength;

        fetch('/platform/passwords/common-passwords.json')
            .then(response => {
                if (!response.ok) {
                    throw new Error('HTTP status: ' + response.status);
                }
                return response.json();
            }).then(data => {
                this.PasswordStrength.addCommonPasswords(data);
            });
        fetch('/platform/passwords/trigraphs.json')
            .then(response => {
                if (!response.ok) {
                    throw new Error('HTTP status: ' + response.status);
                }
                return response.json();
            }).then(data => {
                this.PasswordStrength.addTrigraphMap(data);
            });

        new_pw.onkeyup = event => {
            this.validate(event);
        };
        new_pw.onchange = event => {
            this.validate(event);
        };

        new_pw_check.onkeyup = event => {
            this.validate(event);
        };
        new_pw_check.onchange = event => {
            this.validate(event);
        };
    }

    private strong(password): boolean {
        const code = this.PasswordStrength.check(password).strengthCode;
        switch (this.strength) {
        case 1:
            return true;

        case 2:
            if (code !== 'VERY_WEAK') {
                return true;
            }
            break;

        case 3:
            if (code.indexOf('WEAK') < 0) {
                return true;
            }
            break;

        case 4:
            if (code.indexOf('STRONG') > -1) {
                return true;
            }
            break;

        case 5:
            if (code === 'VERY_STRONG') {
                return true;
            }
            break;

        default:
            return false;
        }

        return false;
    }

    private setFeedback(which: whichType, message: string): void {
        let el = null;
        if (which === 'first') {
            el = this.new_pw.parentNode.querySelector('[role="alert"]');
        } else {
            el = this.new_pw_check.parentNode.querySelector('[role="alert"]');
        }

        if (! el) {
            return;
        }

        el.innerText = message;
    }

    /**
     * Check the length of the password against the minlength of the input field
     */
    private validateLength(event: Event): boolean {
        if (this.new_pw.value.length < Number(this.new_pw.getAttribute('minlength'))) {
            this.new_pw.setCustomValidity(this.warning_length);
            this.setFeedback('first', this.warning_length);

            if (event.type === 'keyup') {
                this.time_out = window.setTimeout(() => {
                    this.new_pw.classList.add('invalid');
                }, 1000);
            } else {
                this.new_pw.classList.add('invalid');
            }

            return false;
        }

        return true;
    }

    /**
     * Check the password for strength characteristics, and against known easy passwords.
     */
    private validateStrength(event: Event): boolean {
        // password is not a common or known hacked password
        // password is strong enough
        if (this.PasswordStrength.check(this.new_pw.value).commonPassword || !this.strong(this.new_pw.value)) {
            this.new_pw.setCustomValidity(this.warning_strength);
            this.setFeedback('first', this.warning_strength);

            if (event.type === 'keyup') {
                this.time_out = window.setTimeout(() => {
                    this.new_pw.classList.add('invalid');
                }, 1000);
            } else {
                this.new_pw.classList.add('invalid');
            }

            return false;
        }

        return true;
    }

    /**
     * Check that the password does not contain the user's email address.
     */
    private validateNotEmail(event: Event): boolean {
        if (this.new_pw.value.toLowerCase().includes(this.new_pw.form?.dataset.visitorEmail?.toLowerCase() ?? '')) {
            this.new_pw.setCustomValidity(this.warning_strength);
            this.setFeedback('first', this.warning_strength);

            if (event.type === 'keyup') {
                this.time_out = window.setTimeout(() => {
                    this.new_pw.classList.add('invalid');
                }, 1000);
            } else {
                this.new_pw.classList.add('invalid');
            }

            return false;
        }

        return true;
    }

    private validate(event: Event): boolean {
        window.clearTimeout(this.time_out);

        // we first validate the primary field
        // and only if that passes do we check that the second one matches

        if (! this.validateLength(event)) {
            return false;
        }

        if (! this.validateStrength(event)) {
            return false;
        }

        if (! this.validateNotEmail(event)) {
            return false;
        }

        // done validating the first input for the new password and it passes
        this.new_pw.setCustomValidity('');
        this.new_pw.classList.remove('invalid');
        this.setFeedback('first', '');

        // now both fields need to match
        // we suppress the feedback if the second field is empty,
        // but the validation still applies so that it blocks the submit
        if (this.new_pw.value !== this.new_pw_check.value) {
            this.new_pw.classList.remove('invalid');
            this.new_pw_check.setCustomValidity(this.warning_mismatch);
            this.setFeedback('second', this.warning_mismatch);

            if (event.type === 'keyup') {
                this.time_out = window.setTimeout(() => {
                    if (this.new_pw_check.value.length > 0) {
                        this.new_pw_check.classList.add('invalid');
                    }
                }, 2000);
            } else {
                if (this.new_pw_check.value.length > 0) {
                    this.new_pw_check.classList.add('invalid');
                }
            }

            return false;
        }

        // reached the end, all good apparently
        this.new_pw_check.setCustomValidity('');
        this.new_pw_check.classList.remove('invalid');
        this.setFeedback('second', '');

        return true;
    }
}
