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

import WidgetModal from "@widgets/Modal.vue";
import WidgetError from "@widgets/MessageError.vue";
import WidgetSuccess from "@widgets/MessageSuccess.vue";

const NavBar      = require('../vue/components/NavBar.vue').default;
const Dashboard   = require('../vue/Dashboard.vue').default;
const MxContainer = require('../vue/MxContainer.vue').default;
const Pinia       = createPinia();
const Gettext     = createGettext({
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
    app.component('WidgetModal', WidgetModal)
    app.component('WidgetError', WidgetError)
    app.component('WidgetSuccess', WidgetSuccess)
    app.mount(selector);
};

export function createNavBar(selector, props = {}) {
    customCreateApp(NavBar, selector, props);
};

export function createDashboard(selector, props = {}) {
    customCreateApp(Dashboard, selector, props);
};

export function createMxContainer(selector, props = {}) {
    customCreateApp(MxContainer, selector, props);
};
