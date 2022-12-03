<script setup>

    import { useDeviceStore } from '@stores/Device.js';
    import { useNavStore } from '../stores/Nav.js';
    import WidgetNotify from './Notify.vue';
    import WidgetProfile from './Profile.vue';
    import WidgetSupport from './Support.vue';

    const device = useDeviceStore();
    const nav    = useNavStore();

</script>

<style scoped="scoped">

    #shrink-nav {
        position: absolute;
        left: 20px;
        font-size: 30px;
        line-height: 36px;
        height: 36px;
    }

</style>

<template>

    <header v-if="!device.isPhone" class="main-header flex items-center h-16 py-3 px-5 bg-if-emerald-700">

        <div
            v-on:click="nav.toggleShrunk()"
            id="shrink-nav"
            class="z-20 cursor-pointer text-white hover:text-if-shark">
            <i class="fa-solid fa-bars"></i>
        </div>

        <div class="ml-auto flex items-center">
            <WidgetSupport className="cursor-pointer pr-6 text-3xl text-white hover:text-if-shark" />
            <WidgetNotify className="cursor-pointer pr-6 text-3xl text-white hover:text-if-shark" />
            <WidgetProfile className="cursor-pointer rounded-full h-8 md:border md:border-white" />
        </div>

    </header>

</template>
