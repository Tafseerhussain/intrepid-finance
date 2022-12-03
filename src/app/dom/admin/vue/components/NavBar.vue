<script setup>

    import $ from 'jquery';
    import { ref } from 'vue';
    import { useDeviceStore } from '@stores/Device.js';
    import { useNavStore } from '../stores/Nav.js';
    import WidgetNotify from './Notify.vue';
    import WidgetProfile from './Profile.vue';
    import WidgetSupport from './Support.vue';

    const
        status = ref('closed'),
        opened = ref(false),
        device = useDeviceStore(),
        nav    = useNavStore();

    function programNavItemShow(i) {
        nav.list[i].show = true;
    };

    function programNavItemHide(i) {
        nav.list[i].show = false;
    };

    function mobileNavToggle() {
        if ('opened' === status.value) {
            mobileNavClose();
        } else {
            mobileNavOpen();
        }
    };

    function mobileNavOpen(fromGesture = false) {
        $('#mobile-nav').animate({'margin-left': '0%'}, 250);
        opened.value = true;
        status.value = 'opened';
    };

    function mobileNavClose() {
        if (opened.value) {
            $('#mobile-nav').animate({'margin-left': '-100%'}, 250, function() {
                opened.value = false;
            });

            status.value = 'closed';
        }
    };

</script>

<style scoped="scoped">
    nav a {
        display: flex;
        align-items: center;
        padding: 0 16px;
    }

    .shrunk nav a {
        padding: 0;
        position: relative;
    }

    .shrunk nav span {
        position: absolute;
        left: 74px;
        top: 6px;
        padding: 0 10px;
        height: 36px;
        line-height: 36px;
        background-color: #1c1f21;
        border-radius: 4px;
        box-shadow: 0 7px 14px 0 #4145581a, 0 3px 6px 0 #00000012;
        z-index: 10;
    }

    .shrunk nav span:before {
        content: "";
        position: absolute;
        top: 50%;
        margin-top: -6px;
        left: -12px;
        border: solid 6px transparent;
        border-right-color: #1c1f21;
        z-index: 10;
    }

    nav i {
        font-size: 20px;
        width: 32px;
        height: 48px;
        line-height: 48px;
        margin-right: 16px;
        text-align: center;
        opacity: 0.8;
    }

    .shrunk nav i {
        margin: 0 16px;
    }

    nav a:hover {
        background-color: #494c4d;
    }

    nav a:hover i {
        color: #86cd92;
        opacity: 1;
    }

    #main-sidebar {
        width: 200px;
        min-width: 200px;
    }

    #main-sidebar #main-sidebar-fixed {
        width: 200px;
        position: fixed;
        height: 100%;
        z-index: 1;
    }

    #main-sidebar.shrunk {
        width: 64px;
        min-width: 64px;
    }

    #main-sidebar.shrunk #main-sidebar-fixed {
        width: 64px;
    }

    #mobile-nav {
        position: fixed;
        top: 0;
        left: 0;
        margin-left: -100%;
        width: 100%;
        height: 100vh;
        display: flex;
        z-index: 50;
    }
</style>

<template>

    <div>

        <div
            v-if="!device.isPhone"
            id="main-sidebar"
            :class="nav.shrunk ? 'shrunk flex flex-wrap flex-col' : 'flex flex-wrap flex-col'">

            <div id="main-sidebar-fixed" class="bg-if-shark">

                <div class="flex w-full items-center bg-if-emerald">

                    <div class="main-sidebar-branding p-3 md:p-0 md:w-full">
                        <div v-if="nav.shrunk" class="h-12 md:h-16 pl-[2px] flex flex-col items-center">
                            <img
                                class="w-[32px] main-sidebar-logo m-auto"
                                src="/img/logo-triangles-white.svg"
                                alt="Logo"
                            />
                        </div>
                        <img
                            v-if="!nav.shrunk"
                            class="main-sidebar-logo m-auto h-12 md:h-16 md:p-3.5"
                            src="/img/logo-without-tags-white.svg"
                            alt="Logo"
                        />
                    </div>

                </div>

                <nav id="main-nav" class="w-full mt-2.5">

                    <ul>

                        <li v-for="(item, i) in nav.list">
                            <a
                                v-on:mouseover="programNavItemShow(i)"
                                v-on:mouseout="programNavItemHide(i)"
                                :href="item.uri">
                                <i :class="item.icon"></i>
                                <span v-if="!nav.shrunk || item.show">
                                    {{ item.name }}
                                </span>
                            </a>
                        </li>

                    </ul>

                </nav>

            </div>

        </div>

        <div v-if="device.isPhone">

            <div v-if="opened" class="dark-overlay">
                <!-- Overlay when opened -->
            </div>

            <div
                v-if="!opened"
                v-tell:swipe.right="mobileNavOpen"
                v-tell-options="{swipeTolerance: 15}"
                class="fixed z-10 top-[44px] left-0 w-5 h-full">
                <!-- Swipe nav open -->
            </div>

            <div class="flex items-center">

                <div v-on:click="mobileNavToggle" class="px-2 py-1 text-3xl text-white">
                    <i class="fa-solid fa-bars"></i>
                </div>

                <div class="ml-auto flex items-center">
                    <WidgetSupport className="cursor-pointer pr-6 text-xl text-white" />
                    <WidgetNotify className="cursor-pointer pr-6 text-xl text-white" />
                    <WidgetProfile className="cursor-pointer rounded-full h-6 mr-2" />
                </div>

            </div>

            <nav
                v-tell:slide.left="mobileNavClose"
                id="mobile-nav">

                <div id="mobile-nav-wrap" class="w-10/12 max-w-[400px] bg-if-shark shadow-md overflow-y-auto">

                    <div class="bg-if-emerald py-5">
                        <img
                            class="main-sidebar-logo mx-auto h-12"
                            src="/img/logo-without-tags-white.svg"
                            alt="Logo"
                        />
                    </div>

                    <ul class="pt-3 w-full border-t border-if-shark">

                        <li v-for="item in nav.list">
                            <a :href="item.uri">
                                <i :class="item.icon"></i>
                                <span>{{ item.name }}</span>
                            </a>
                        </li>

                    </ul>

                </div>

                <div v-on:click="mobileNavToggle" class="grow">
                    <!-- Close nav if clicked -->
                </div>

            </nav>

        </div>

    </div>

</template>
