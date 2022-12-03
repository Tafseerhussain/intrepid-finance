<template>

    <div v-if="active" class="
        fixed z-40 top-0 left-0
        w-full h-screen
        flex flex-col md:items-center md:justify-center
        md:bg-black md:bg-opacity-70">

        <div v-if="!device.isPhone" @click="close()" class="absolute w-full h-full">
            <!-- Close dialog when clicked -->
        </div>

        <div :class="className" class="
            z-40 flex flex-col
            md:px-1 pb-1
            w-full h-screen md:w-2/3 md:max-w-[760px] md:h-auto md:max-h-[66%]
            md:shadow-lg md:rounded-md
            bg-white text-if-shark">

            <div class="flex items-center md:-mx-1 md:mb-1 border-b">
                <div class="flex-auto px-2 md:px-3 font-bold">
                    {{ title }}
                </div>
                <div @click="close()" class="
                    cursor-pointer px-3 py-1 text-3xl leading-9 hover:opacity-50">
                    <i class="fa-regular fa-xmark"></i>
                </div>
            </div>

            <div class="scrollbox scrollbox-rounded h-full overflow-auto">

                <div class="scrollbox-content px-2 py-1">

                    <slot name="content" />

                </div>

            </div>

            <div v-if="$slots.actions" class="flex mt-1 px-3 pt-3 pb-2 md:-mx-1 border-t">

                <slot name="actions" />

            </div>

        </div>

    </div>

</template>

<script>

    import { useDeviceStore } from '@stores/Device.js';

    export default {
        props : {
            'active' : {
                type : Boolean,
                default : false,
            },
            'title' : {
                type : String,
                default : '',
            },
            'className' : {
                type : String,
                default : '',
            },
        },
        setup(props, { emit }) {
            const device = useDeviceStore();

            function close() {
                emit('close');
            };

            return {
                device,
                close,
            };
        }
    };

</script>
