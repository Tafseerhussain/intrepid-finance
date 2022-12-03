<script setup>

    import { reactive, ref } from 'vue';
    import { useDeviceStore } from '@stores/Device.js';
    import { useAdminStore } from './stores/Admin.js';
    import { useAdminsStore } from './stores/Admins.js';
    import FormHandler from '@mixins/FormHandler.js';

    class ModalAdmin extends FormHandler {
        constructor(errors, state, initial, admins, noticeError, noticeSuccess) {
            super(errors, state);
            this.initial       = initial;
            this.admins        = admins;
            this.noticeError   = noticeError;
            this.noticeSuccess = noticeSuccess;
            this.active        = false;
            this.loading       = false;
        }

        open() {
            this.active = true;
        }

        close() {
            Object.assign(this.state, this.initial);
            this.resetErrors();
            this.active = false;
        }

        async load(id) {
            this.loading = true;

            await axios
                .get('/admin/fetch/admins/' + id + '?ajax=1')
                .then((res) => {
                    this.state.id           = res.data.id;
                    this.state.first_name   = res.data.first_name;
                    this.state.last_name    = res.data.last_name;
                    this.state.email        = res.data.email;
                    this.state.status       = res.data.status;
                    this.state.access_level = res.data.access_level;
                    this.loading            = false;
                    this.open();
                }).catch((error) => {
                    this.loading = false;
                });
        }

        async submit() {
            let uri;

            if (this.state.id) {
                uri = '/admin/admins/edit/' + this.state.id + '?ajax=1';
            } else {
                uri = '/admin/admins/add?ajax=1';
            }

            this.loading = true;

            await axios
                .post(uri, this.state)
                .then((res) => {
                    this.close();
                    this.loading       = false;
                    this.noticeError   = null;
                    this.noticeSuccess = 'Admin record edited successfully.';
                    this.admins.fetch();
                    useAdminStore().fetch();
                }).catch((error) => {
                    this.loading = false;
                    this.handleErrors(error.response.data.errors);
                });
        }
    };

    class ModalDelete  {
        constructor(state, admins, noticeError, noticeSuccess) {
            this.state         = state;
            this.admins        = admins;
            this.noticeError   = noticeError;
            this.noticeSuccess = noticeSuccess;
            this.active        = false;
            this.loading       = false;
            this.id            = null;
        }

        open(id) {
            this.active = true;
            this.id     = id;
        }

        close() {
            this.active = false;
        }

        async submit() {
            this.loading = true;

            await axios
                .post('/admin/admins/delete/' + this.id + '?ajax=1', this.state)
                .then((res) => {
                    this.close();
                    this.loading       = false;
                    this.noticeSuccess = 'Admin record deleted successfully.';
                    this.admins.fetch();
                }).catch((error) => {
                    this.close();
                    this.loading       = false;
                    this.noticeSuccess = null;
                    this.noticeError   = error.response.data.errors.id;
                });
        }
    };

    const device = useDeviceStore();

    const admins = useAdminsStore();

    const noticeError = ref('');

    const noticeSuccess = ref('');

    const initialState = {
        id             : null,
        first_name     : null,
        last_name      : null,
        email          : null,
        password       : null,
        password_again : null,
        status         : 'Active',
        access_level   : 1,
    };

    const modalState = reactive({...initialState});

    const modalErrors = reactive({});

    const modalAdmin = ref(new ModalAdmin(
        modalErrors,
        modalState,
        initialState,
        admins,
        noticeError,
        noticeSuccess
    ));

    const modalDelete = ref(new ModalDelete(
        { },
        admins,
        noticeError,
        noticeSuccess
    ));

    function inputCss(field) {
        return modalErrors[field] ? 'has-error' : '';
    };

</script>

<template>

    <div>

        <div v-if="!device.isMobile" class="pt-5 px-5">

            <div class="bg-white">

                <div class="flex p-3">

                    <div class="w-1/3">
                        <input
                            @keyup="admins.prefetch()"
                            v-model="admins.query.search"
                            :placeholder="$gettext('Search') + '...'"
                            type="text" class="styled w-full"
                            />
                    </div>

                    <div class="flex items-center ml-auto">
                        <button @click="modalAdmin.open()" class="
                            h-10 ml-3 px-3 rounded-md
                            text-white bg-if-hippie-blue hover:bg-if-hippie-blue-dark">
                            {{ $gettext('Add Admin') }}
                        </button>
                    </div>

                </div>

                <table class="styled w-full text-sm xl:text-base">

                    <thead>

                        <tr class="bg-if-silver-100 border-t border-gray-200">
                            <th scope="col">
                                <div @click="admins.sortBy('first_name')" class="flex sortable">
                                    <div>
                                        {{ $gettext('First Name') }}
                                    </div>
                                    <div class="ml-auto pl-2">
                                        <i :class="admins.sortIcon('first_name')"></i>
                                    </div>
                                </div>
                            </th>
                            <th scope="col">
                                <div @click="admins.sortBy('last_name')" class="flex sortable">
                                    <div>
                                        {{ $gettext('Last Name') }}
                                    </div>
                                    <div class="ml-auto pl-2">
                                        <i :class="admins.sortIcon('last_name')"></i>
                                    </div>
                                </div>
                            </th>
                            <th scope="col">
                                <div @click="admins.sortBy('email')" class="flex sortable">
                                    <div>
                                        {{ $gettext('Email Address') }}
                                    </div>
                                    <div class="ml-auto pl-2">
                                        <i :class="admins.sortIcon('email')"></i>
                                    </div>
                                </div>
                            </th>
                            <th scope="col">
                                <div @click="admins.sortBy('access_level')" class="flex sortable">
                                    <div>
                                        {{ $gettext('Access') }}
                                    </div>
                                    <div class="ml-auto pl-2">
                                        <i :class="admins.sortIcon('access_level')"></i>
                                    </div>
                                </div>
                            </th>
                            <th scope="col">
                                <div @click="admins.sortBy('status')" class="flex sortable">
                                    <div>
                                        {{ $gettext('Status') }}
                                    </div>
                                    <div class="ml-auto pl-2">
                                        <i :class="admins.sortIcon('status')"></i>
                                    </div>
                                </div>
                            </th>
                            <th class="icons px-4 py-2">
                                &nbsp;
                            </th>
                        </tr>

                    </thead>

                    <tbody class="relative">

                        <tr v-for="(item, i) in admins.list" class="border-t border-gray-200">
                            <td
                                @click="modalAdmin.load(item.id)"
                                class="cursor-pointer px-4 h-16">
                                {{ item.first_name }}
                            </td>
                            <td
                                @click="modalAdmin.load(item.id)"
                                class="cursor-pointer px-4 h-16">
                                {{ item.last_name }}
                            </td>
                            <td class="px-4 h-16">
                                <a :href="'mailto:' + item.email" class="text-if-denim">
                                    {{ item.email }}
                                </a>
                            </td>
                            <td
                                @click="modalAdmin.load(item.id)"
                                class="cursor-pointer px-4 h-16">
                                {{ $gettext('Level') }} {{ item.access_level }}
                            </td>
                            <td
                                @click="modalAdmin.load(item.id)"
                                class="cursor-pointer px-4 h-16">
                                {{ item.status }}
                            </td>
                            <td class="px-4 h-16 icons">
                                <i @click="modalAdmin.load(item.id)" class="
                                    fa-light fa-pen-to-square
                                    mr-3.5 text-xl align-middle cursor-pointer
                                    opacity-70 hover:opacity-100">
                                </i>
                                <i @click="modalDelete.open(item.id)" class="
                                    fa-light fa-trash-can
                                    text-xl align-middle cursor-pointer
                                    opacity-70 hover:opacity-100">
                                </i>
                            </td>
                        </tr>

                        <tr
                            v-if="!admins.loading && !admins.list.length"
                            class="border-t border-gray-200">
                            <td colspan="6" class="text-center p-3">
                                No results matched the query criteria.
                            </td>
                        </tr>

                        <tr v-if="admins.loading">
                            <td colspan="6">
                                <LoadingArea />
                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

        <div v-if="device.isMobile" class="py-2">

            <div class="pt-2 px-2">

                <div class="p-2 text-sm bg-white rounded">

                    <button @click="modalAdmin.open()" class="
                        w-full h-10 px-3 rounded
                        text-white bg-if-hippie-blue hover:bg-if-hippie-blue-dark">
                        {{ $gettext('Add Admin') }}
                    </button>

                    <div class="flex mt-2">

                        <div class="flex flex-col whitespace-nowrap pr-4 pl-3">
                            <div class="h-10 leading-10">
                                {{ $gettext('Search') }}:
                            </div>
                            <div class="h-10 leading-10 mt-2 text-right">
                                {{ $gettext('Sort By') }}:
                            </div>
                        </div>

                        <div class="flex-grow">
                            <input
                                @keyup="admins.prefetch()"
                                v-model="admins.query.search"
                                type="text"
                                class="styled w-full"
                                :placeholder="$gettext('Search') + '...'"
                            />
                            <select
                                @change="admins.fetch()"
                                v-model="admins.query.sort_by"
                                class="styled w-full mt-2">
                                <option value="first_name">{{ $gettext('First Name') }}</option>
                                <option value="last_name">{{ $gettext('Last Name') }}</option>
                                <option value="email">{{ $gettext('Email Address') }}</option>
                                <option value="access_level">{{ $gettext('Access Level') }}</option>
                                <option value="status">{{ $gettext('Status') }}</option>
                            </select>
                        </div>

                    </div>

                </div>

            </div>

            <div v-for="(item, i) in admins.list" class="px-2 pt-2">

                <div class="relative p-2 text-sm bg-white rounded">
                    <div class="absolute top-2 right-2">
                        <i @click="modalAdmin.load(item.id)" class="
                            fa-light fa-pen-to-square
                            mr-3 text-xl align-middle cursor-pointer
                            opacity-70 hover:opacity-100">
                        </i>
                        <i @click="modalDelete.open(item.id)" class="
                            fa-light fa-trash-can
                            text-xl align-middle cursor-pointer
                            opacity-70 hover:opacity-100">
                        </i>
                    </div>
                    <div>
                        {{ item.first_name }} {{ item.last_name }}
                    </div>
                    <div>
                        <a :href="'mailto:' + item.email" class="text-if-denim">
                            {{ item.email }}
                        </a>
                    </div>
                    <div class="flex items-center">
                        <div>
                            {{ $gettext('Level') }} {{ item.access_level }}
                        </div>
                        <div class="ml-auto italic text-xs">
                            {{ item.status }}
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <div class="px-2 pb-2 lg:px-5 lg:pb-5">
            <div class="
                flex justify-end p-2 bg-white rounded
                md:justify-between
                lg:p-3 lg:border-t lg:border-gray-200 lg:rounded-none">
                <div v-if="!device.isPhone" class="py-1 opacity-50">
                    {{ admins.text }}
                </div>
                <ul class="flex justify-end">
                    <li v-for="page in admins.pages">
                        <div v-if="'...' === page"
                            class="pl-2 py-1">
                            ...
                        </div>
                        <div v-else-if="page === admins.query.page"
                            @click="admins.setPage(page)"
                            class="
                                ml-2 px-2.5 py-1 cursor-pointer rounded
                                text-white bg-if-hippie-blue hover:bg-if-hippie-blue-dark">
                            {{ page }}
                        </div>
                        <div v-else
                            @click="admins.setPage(page)"
                            class="
                                ml-2 px-2.5 py-1 cursor-pointer rounded
                                bg-if-silver-300 hover:bg-if-silver-500">
                            {{ page }}
                        </div>
                    </li>
                </ul>
            </div>
        </div>

    </div>

    <WidgetModal @close="modalAdmin.close()" :title="modalState.id ? 'Edit Admin Record' : 'Add Admin Record'" :active="modalAdmin.active">
        <template #content>

            <div>
                <div class="pb-1">
                    {{ $gettext('Email Address') }}
                </div>
                <input v-model="modalState.email" :class="inputCss('email')" class="styled w-full" type="email" />
                <div v-if="modalErrors.email" class="field-error">
                    {{ modalErrors.email }}
                </div>
            </div>

            <div class="md:flex mt-3">

                <div class="md:w-1/2 lg:w-1/2 md:mr-1.5">
                    <div class="pb-1">
                        {{ $gettext('First Name') }}
                    </div>
                    <input v-model="modalState.first_name" :class="inputCss('first_name')" class="styled w-full" type="text" />
                    <div v-if="modalErrors.first_name" class="field-error">
                        {{ modalErrors.first_name }}
                    </div>
                </div>

                <div class="md:w-1/2 lg:w-1/2 mt-3 md:mt-0 md:ml-1.5">
                    <div class="pb-1">
                        {{ $gettext('Last Name') }}
                    </div>
                    <input v-model="modalState.last_name" :class="inputCss('last_name')" class="styled w-full" type="text" />
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
                    <input v-model="modalState.password" :class="inputCss('password')" class="styled w-full" type="password" />
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
                    <input v-model="modalState.password_again" :class="inputCss('password_again')" class="styled w-full" type="password" />
                    <div v-if="modalErrors.password_again" class="field-error">
                        {{ modalErrors.password_again }}
                    </div>
                </div>

            </div>

            <div class="md:flex mt-3 pb-1">

                <div class="md:w-1/2 lg:w-1/2 md:mr-1.5">
                    <div class="pb-1">
                        {{ $gettext('Status') }}
                    </div>
                    <select v-model="modalState.status" :class="inputCss('status')" class="styled w-full">
                        <option value="Active">
                            {{ $gettext('Active') }}
                        </option>
                        <option value="Disabled">
                            {{ $gettext('Disabled') }}
                        </option>
                    </select>
                    <div v-if="modalErrors.status" class="field-error">
                        {{ modalErrors.status }}
                    </div>
                </div>

                <div class="md:w-1/2 lg:w-1/2 mt-3 md:mt-0 md:ml-1.5">
                    <div class="pb-1">
                        {{ $gettext('Access Level') }}
                    </div>
                    <select v-model="modalState.access_level" :class="inputCss('access_level')" class="styled w-full">
                        <option value="1">
                            {{ $gettext('Level 1: View, Add') }}
                        </option>
                        <option value="2">
                            {{ $gettext('Level 2: View, Add, Edit, Delete') }}
                        </option>
                        <option value="3">
                            {{ $gettext('Level 3: View, Add, Edit, Delete, Admins') }}
                        </option>
                    </select>
                    <div v-if="modalErrors.access_level" class="field-error">
                        {{ modalErrors.access_level }}
                    </div>
                </div>

            </div>

        </template>
        <template #actions>
            <div class="flex w-full justify-end">
                <button @click="modalAdmin.submit()" class="generic h-10 px-3 mr-3">
                    {{ $gettext('Submit') }}
                </button>
                <button @click="modalAdmin.close()" class="plain h-10 px-3">
                    {{ $gettext('Cancel') }}
                </button>
            </div>
        </template>
    </WidgetModal>

    <WidgetModal
        @close="modalDelete.close()"
        :active="modalDelete.active"
        title="Delete Admin Record">
        <template #content>
            {{ $gettext('Are you sure you want to delete this record?') }}
        </template>
        <template #actions>
            <div class="flex w-full justify-end">
                <button @click="modalDelete.submit()" class="generic h-10 px-3 mr-3">
                    {{ $gettext('Yes') }}
                </button>
                <button @click="modalDelete.close()" class="plain h-10 px-3">
                    {{ $gettext('No') }}
                </button>
            </div>
        </template>
    </WidgetModal>

    <LoadingIndicator v-if="modalAdmin.loading || modalDelete.loading" />

    <WidgetError v-model:message="noticeError" />

    <WidgetSuccess v-model:message="noticeSuccess" />

</template>
