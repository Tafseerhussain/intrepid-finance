<template>

    <div v-if="active" class="
        flex items-center
        fixed z-40 bottom-0 right-0
        w-full rounded
        bg-red-900 text-red-100
        md:w-auto md:bottom-5 md:right-5">

        <div class="px-3 py-2">
            {{ message }}
        </div>

        <div @click="close()" class="ml-auto pr-3 cursor-pointer">
            <i class="fa-sharp fa-solid fa-xmark-large"></i>
        </div>

    </div>

</template>

<script>

    import { ref, watch } from 'vue';
    import { useDeviceStore } from '@stores/Device.js';

    export default {
        props : [
            'message',
        ],
        setup(props, { emit }) {
            const device = useDeviceStore();

            const active = ref(false);

            function close() {
                emit('update:message', '');
            };

            watch(() => props.message, (newVal) => {
                active.value = newVal ? true : false;
            });

            return {
                device,
                active,
                close,
            };
        }
    };

</script>
