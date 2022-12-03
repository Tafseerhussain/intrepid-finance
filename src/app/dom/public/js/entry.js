import '@mixins/Axios.js';
import '../css/main.css';

import { createApp, h } from 'vue';
import { createPinia } from 'pinia';
import { createGettext } from 'vue3-gettext';
import CountryRegionSelect from 'vue3-country-region-select';
import Maska from 'maska';

import { ClickOutside } from '@plugins/ClickOutside.js';
import { Vue3TellEvents } from '@plugins/Vue3TellEvents.js';
import translations from '../../../lang/translations.json';

const Account      = require('../vue/CreateAccount.vue').default;
const Application  = require('../vue/Application.vue').default;
const LoanCalculator  = require('../vue/LoanCalculator.vue').default;
const Confirmation = require('../vue/Confirmation.vue').default;
const Pinia        = createPinia();
const Gettext      = createGettext({
    availableLanguages : {
        en : 'English',
    },
    defaultLanguage : 'en',
    silent          : true,
    translations    : translations,
});

function customCreateApp(component, selector, props = {}) {
    const app = createApp({
        render() {
            return h(component);
        }
    }, props);

    app.config.warnHandler = () => null;
    app.use(Pinia)
    app.use(Gettext)
    app.use(Vue3TellEvents)
    app.use(ClickOutside)
    app.use(CountryRegionSelect)
    app.use(Maska)
    app.mount(selector);
};

export function createAccount(selector, props) {
    customCreateApp(Account, selector, props);
};

export function createApplication(selector, props) {
    customCreateApp(Application, selector, props);
};

export function createLoanCalculator(selector, props) {
    customCreateApp(LoanCalculator, selector, props);
};


export function createConfirmation(selector, props) {
    customCreateApp(Confirmation, selector, props);
};
