<template>

    <div class="
        flex flex-col sm:flex-row items-center w-full
        border-b border-b-slate-200">

        <div class="text-2xl px-4 py-3">
            <img class="h-10" src="/img/logo-without-tags.svg" alt="Logo" />
        </div>

        <div class="sm:ml-auto flex h-12 sm:h-full">
            <a href="/clients/dashboard" class="h-full items-center flex mr-6">
                <div>
                    <i class="fa-solid fa-chart-line-up"></i>
                </div>
                <div class="pl-2">
                    Dashboard
                </div>
            </a>
            <a href="/clients/profile" class="h-full items-center flex mr-6">
                <div>
                    <i class="fa-solid fa-user"></i>
                </div>
                <div class="pl-2">
                    Profile
                </div>
            </a>
            <a href="/clients/logout" class="h-full items-center flex sm:mr-4">
                <div>
                    <i class="fa-solid fa-right-from-bracket"></i>
                </div>
                <div class="pl-2">
                    Logout
                </div>
            </a>
        </div>

    </div>

</template>

<script>

    import { computed, ref } from 'vue';
    import { useDeviceStore } from '@stores/Device.js';

    export default {
        setup() {
            const
                device = useDeviceStore();

            return {
                device
            };
        }
    };

</script>
