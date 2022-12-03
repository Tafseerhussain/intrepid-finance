import $ from 'jquery';
import { ref } from 'vue';
import { defineStore } from 'pinia';

export const useDeviceStore = defineStore('device', {
    state : () => {
        const
            width  = ref($(window).outerWidth()),
            height = ref($(window).outerHeight());

        $(window).on('resize', () => {
            width.value  = $(window).outerWidth();
            height.value = $(window).outerHeight();
        }).trigger('resize');

        $(function() {
            $(window).trigger('resize');
        });

        return {
            width  : width,
            height : height,
        };
    },
    getters : {
        xs(state) {
            return state.width < 640;
        },
        sm(state) {
            return state.width >= 640 && state.width < 768;
        },
        md(state) {
            return state.width >= 768 && state.width < 1024;
        },
        lg(state) {
            return state.width >= 1024 && state.width < 1280;
        },
        xl(state) {
            return state.width >= 1280 && state.width < 1600;
        },
        xxl(state) {
            return state.width >= 1600;
        },
        isPhone(state) {
            return state.width < 768;
        },
        isTablet(state) {
            return state.width >= 768 && state.value < 1024;
        },
        isMobile(state) {
            return state.width < 1024;
        },
        isPortrait(state) {
            return state.height > state.width;
        },
        isAppleDevice(state) {
            return /iPhone|iPod|iPad/.test(state.platform);
        },
        platform() {
            return navigator?.userAgentData?.platform || navigator?.platform || 'unknown';
        }
    },
});
