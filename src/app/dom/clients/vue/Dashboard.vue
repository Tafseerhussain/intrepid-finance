<script setup>

    import { ref, watch } from 'vue';
    import { useProfileStore } from './stores/Profile.js';
    import MxDisconnect from './components/MxDisconnect.vue';

    const profile = useProfileStore();

    const mxShowDisconnect = ref(false);

    function mxModalDisconnect() {
        mxShowDisconnect.value = true;
    };

    watch((mxShowDisconnect), (newVal, oldVal) => {
        if (true === oldVal && false === newVal) {
            profile.fetch();
        }
    });

</script>

<template>

    <div class="w-full px-3 pb-3 lg:px-4 lg:pb-4">

        <div>
            This area is still under construction.
        </div>

        <div v-if="profile.mx_needs_widget">
            <a href="/clients/mx/connect">Connect to MX</a>
        </div>

        <div v-if="!profile.mx_needs_widget" @click="mxModalDisconnect()">
            Disconnect Mx
        </div>

    </div>

    <MxDisconnect v-model:active="mxShowDisconnect" />

</template>
