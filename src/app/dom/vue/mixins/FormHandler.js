import { nextTick } from 'vue';

export default class FormHandler {
    constructor(errors, state = {}) {
        this.errors  = errors;
        this.state   = state;
        this.loading = false;
    }

    handleErrors(errors) {
        this.resetErrors();
        Object.assign(this.errors, errors);
        this.scrollToError();
    }

    resetErrors() {
        for (let key in this.errors) {
            this.errors[key] = null;
        }
    }

    async scrollToError() {
        await nextTick();

        let el;

        el = document.querySelector('.has-error');

        if ( ! el) {
            el = document.querySelector('.field-error');
        }

        if (el) {
            el.scrollIntoView();
        }
    }
};
