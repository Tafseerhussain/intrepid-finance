<template>

    <div>

        <div class="flex items-center relative">

            <img
                v-on:click="profileToggle"
                :alt="admin.first_name + ' ' + admin.last_name"
                :src="admin.gravatar"
                :class="className"
            />

            <div v-if="!device.isPhone">

                <div
                    v-click-outside="programProfileClose"
                    id="program-profile"
                    tabindex="0"
                    class="
                        dropdown
                        min-w-[250px] absolute top-12 -right-5 z-10
                        bg-slate-100 whitespace-nowrap">

                    <div class="p-3 text-center border-b border-slate-200 font-bold">
                        {{ admin.first_name }} {{ admin.last_name }}
                    </div>

                    <ul>

                        <li>
                            <div @click="modalProfile.open()">
                                <i class="fa-solid fa-user"></i>
                                <span>{{ $gettext('My Profile') }}</span>
                            </div>
                        </li>

                        <li>
                            <a href="/admin/logout" class="rounded-b">
                                <i class="fa-solid fa-arrow-up-left-from-circle"></i>
                                <span>{{ $gettext('Logout') }}</span>
                            </a>
                        </li>

                    </ul>

                </div>

            </div>

        </div>

        <div v-if="device.isPhone">

            <div v-if="opened" class="dark-overlay">
                <!-- Overlay when opened -->
            </div>

            <div
                v-tell:slide.right="mobileProfileClose"
                id="mobile-profile">

                <div v-on:click="mobileProfileToggle" class="grow">
                    <!-- Close profile if clicked -->
                </div>

                <div class="w-10/12 max-w-[400px] bg-if-shark shadow-md overflow-y-auto">

                    <div class="flex flex-col items-center">

                        <div class="my-5 w-1/2">
                            <img
                                :alt="admin.first_name + ' ' + admin.last_name"
                                :src="admin.gravatar"
                                class="rounded-full"
                            />
                        </div>

                        <div class="text-xl">
                            {{ admin.first_name }} {{ admin.last_name }}
                        </div>

                        <ul class="mt-5 pt-3 w-full border-t border-slate-900">

                            <li>
                                <div @click="modalProfile.open()">
                                    <i class="fa-solid fa-user"></i>
                                    <span>{{ $gettext('My Profile') }}</span>
                                </div>
                            </li>

                            <li>
                                <a href="/admin/logout">
                                    <i class="fa-solid fa-arrow-up-left-from-circle"></i>
                                    <span>{{ $gettext('Logout') }}</span>
                                </a>
                            </li>

                        </ul>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <WidgetModal @close="modalProfile.close()" title="Edit Profile" :active="modalProfile.active">
        <template #content>

            <div>
                <div class="pb-1">
                    {{ $gettext('Email Address') }}
                </div>
                <input
                    v-model="modalState.email"
                    :class="inputCss('email')"
                    class="styled w-full"
                    type="email"
                />
                <div v-if="modalErrors.email" class="field-error">
                    {{ modalErrors.email }}
                </div>
            </div>

            <div class="md:flex mt-3">

                <div class="md:w-1/2 lg:w-1/2 md:mr-1.5">
                    <div class="pb-1">
                        {{ $gettext('First Name') }}
                    </div>
                    <input
                        v-model="modalState.first_name"
                        :class="inputCss('first_name')"
                        class="styled w-full"
                        type="text"
                    />
                    <div v-if="modalErrors.first_name" class="field-error">
                        {{ modalErrors.first_name }}
                    </div>
                </div>

                <div class="md:w-1/2 lg:w-1/2 mt-3 md:mt-0 md:ml-1.5">
                    <div class="pb-1">
                        {{ $gettext('Last Name') }}
                    </div>
                    <input
                        v-model="modalState.last_name"
                        :class="inputCss('last_name')"
                        class="styled w-full"
                        type="text"
                    />
                    <div v-if="modalErrors.last_name" class="field-error">
                        {{ modalErrors.last_name }}
                    </div>
                </div>

            </div>

            <div class="md:flex mt-3">

                <div class="md:w-1/2 lg:w-1/2 md:mr-1.5">
                    <div v-if="modalState.id" class="pb-1">
                        {{ $gettext('New Password (Optional)') }}
                    </div>
                    <div v-else class="pb-1">
                        {{ $gettext('Password') }}
                    </div>
                    <input
                        v-model="modalState.password"
                        :class="inputCss('password')"
                        class="styled w-full"
                        type="password"
                    />
                    <div v-if="modalErrors.password" class="field-error">
                        {{ modalErrors.password }}
                    </div>
                </div>

                <div class="md:w-1/2 lg:w-1/2 mt-3 md:mt-0 md:ml-1.5">
                    <div v-if="modalState.id" class="pb-1">
                        {{ $gettext('New Password Again') }}
                    </div>
                    <div v-else class="pb-1">
                        {{ $gettext('Password Again') }}
                    </div>
                    <input
                        v-model="modalState.password_again"
                        :class="inputCss('password_again')"
                        class="styled w-full"
                        type="password"
                    />
                    <div v-if="modalErrors.password_again" class="field-error">
                        {{ modalErrors.password_again }}
                    </div>
                </div>

            </div>

        </template>
        <template #actions>
            <div class="flex w-full justify-end">
                <button @click="modalProfile.submit()" class="generic h-10 px-3 mr-3">
                    {{ $gettext('Submit') }}
                </button>
                <button @click="modalProfile.close()" class="plain h-10 px-3">
                    {{ $gettext('Cancel') }}
                </button>
            </div>
        </template>
    </WidgetModal>

    <WidgetError v-model:message="noticeError" />

    <WidgetSuccess v-model:message="noticeSuccess" />

</template>

<style scoped="scoped">
    .dropdown {
        box-shadow: 0px 5px 5px 2px rgba(0, 0, 0, 0.05);
    }

    .dropdown:after {
        background: rgb(241, 245, 249);
        border-radius: .125rem;
        content: "";
        height: 1rem;
        position: absolute;
        right: 1.75rem;
        top: -0.5rem;
        transform: rotate(45deg);
        width: 1rem;
        z-index: 1;
    }

    ul a,
    ul div {
        display: flex;
        align-items: center;
        padding: 0 16px;
        cursor: pointer;
    }

    ul i {
        font-size: 20px;
        width: 32px;
        height: 48px;
        line-height: 48px;
        margin-right: 16px;
        text-align: center;
        opacity: 0.8;
    }

    #program-profile {
        display: none;
    }

    #program-profile ul a:hover,
    #program-profile ul div:hover {
        background-color: #fff;
    }

    #mobile-profile ul a:hover,
    #mobile-profile ul div:hover {
        background-color: #494c4d;
    }

    #mobile-profile ul a:hover i,
    #mobile-profile ul div:hover i {
        color: #5ac278;
    }

    #mobile-profile {
        position: fixed;
        top: 0;
        right: 0;
        margin-right: -100%;
        width: 100%;
        height: 100vh;
        display: flex;
        z-index: 50;
    }
</style>

<script>

    import $ from 'jquery';
    import axios from 'axios';
    import { reactive, ref } from 'vue';
    import { useDeviceStore } from '@stores/Device.js';
    import { useAdminStore } from '../stores/Admin.js';
    import { useAdminsStore } from '../stores/Admins.js';
    import FormHandler from '@mixins/FormHandler.js';

    class ModalProfile extends FormHandler {
        constructor(errors, state, noticeError, noticeSuccess) {
            super(errors, state);
            this.noticeError   = noticeError;
            this.noticeSuccess = noticeSuccess;
            this.active        = false;
            this.loading       = false;
        }

        open() {
            this.active = true;
        }

        close() {
            this.active = false;
        }

        async submit() {
            this.loading = true;

            await axios
                .post('/admin/profile?ajax=1', this.state)
                .then((res) => {
                    this.close();
                    this.loading       = false;
                    this.noticeError   = null;
                    this.noticeSuccess = 'Profile edited successfully.';
                    useAdminStore().fetch();

                    if (this.state.access_level > 2) {
                        useAdminsStore().fetch();
                    }
                }).catch((error) => {
                    this.loading = false;
                    this.handleErrors(error.response.data.errors);
                });
        }
    };

    export default {
        props : {
            className : {
                default : 'rounded-full h-6 mr-2',
            },
        },
        setup() {
            const
                status = ref('closed'),
                opened = ref(false),
                device = useDeviceStore(),
                admin  = useAdminStore();

            const noticeError = ref('');

            const noticeSuccess = ref('');

            const modalState = admin;

            const modalErrors = reactive({});

            const modalProfile = ref(new ModalProfile(
                modalErrors,
                modalState,
                noticeError,
                noticeSuccess
            ));

            function profileToggle() {
                if (device.isPhone) {
                    mobileProfileToggle();
                } else {
                    programProfileToggle();
                }
            };

            function programProfileToggle() {
                if ('opened' === status.value) {
                    programProfileClose();
                } else {
                    programProfileOpen();
                }
            };

            function programProfileOpen() {
                $('#program-profile').focus().slideDown(50, function() {
                    opened.value = true;
                });

                status.value = 'opened';
            };

            function programProfileClose() {
                if (opened.value) {
                    $('#program-profile').slideUp(50, function() {
                        opened.value = false;
                    });

                    status.value = 'closed';
                }
            };

            function mobileProfileToggle() {
                if ('opened' === status.value) {
                    mobileProfileClose();
                } else {
                    mobileProfileOpen();
                }
            };

            function mobileProfileOpen() {
                $('html,body').css('overscroll-behavior-y', 'contain');
                $('#mobile-profile').animate({'margin-right': '0%'}, 250);
                opened.value = true;
                status.value = 'opened';
            };

            function mobileProfileClose() {
                if (opened.value) {
                    $('html,body').css('overscroll-behavior-y', 'auto');
                    $('#mobile-profile').animate({'margin-right': '-100%'}, 250, function() {
                        opened.value = false;
                    });

                    status.value = 'closed';
                }
            };

            function inputCss(field) {
                return modalErrors[field] ? 'has-error' : '';
            };

            return {
                noticeError,
                noticeSuccess,
                modalState,
                modalErrors,
                modalProfile,
                profileToggle,
                programProfileToggle,
                programProfileOpen,
                programProfileClose,
                mobileProfileToggle,
                mobileProfileOpen,
                mobileProfileClose,
                inputCss,
                status,
                opened,
                device,
                admin,
            };
        }
    };

</script>
