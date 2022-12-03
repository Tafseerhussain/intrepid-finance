<script setup>

    import { reactive, ref } from 'vue';
    import { useDeviceStore } from '@stores/Device.js';
    import { useClientsStore } from './stores/Clients.js';

    class ModalDelete {
        constructor(state, clients, noticeError, noticeSuccess) {
            this.state         = state;
            this.clients       = clients;
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
                .post('/admin/clients/delete/' + this.id + '?ajax=1', this.state)
                .then((res) => {
                    this.close();
                    this.loading       = false;
                    this.noticeSuccess = 'Client record deleted successfully.';
                    this.clients.fetch();
                }).catch((error) => {
                    this.close();
                    this.loading       = false;
                    this.noticeSuccess = null;
                    this.noticeError   = error.response.data.errors.id;
                });
        }
    };

    const device = useDeviceStore();

    const clients = useClientsStore();

    const noticeError = ref('');

    const noticeSuccess = ref('');

    const modalDelete = ref(new ModalDelete(
        { },
        clients,
        noticeError,
        noticeSuccess
    ));

</script>

<template>

    <div>

        <div v-if="!device.isMobile" class="pt-5 px-5">

            <div class="bg-white">

                <div class="flex w-full">

                    <div class="w-1/3 py-3 pl-3 pr-1.5">
                        <input
                            @keyup="clients.prefetch()"
                            v-model="clients.query.search"
                            :placeholder="$gettext('Search') + '...'"
                            type="text" class="styled w-full"
                            />
                    </div>

                    <div class="w-1/3 py-3 px-1.5">
                        <select
                            @change="clients.fetch()"
                            v-model="clients.query.form_type"
                            class="styled w-full">
                            <option value="">{{ $gettext('All Forms') }}</option>
                            <option disabled="disabled">-</option>
                            <option value="commercial_capital">{{ $gettext('Commercial Capital') }}</option>
                            <option value="venture_capital">{{ $gettext('Growth & Venture Capital') }}</option>
                        </select>
                    </div>

                    <div class="w-1/3 py-3 pl-1.5 pr-3">
                        <MultiSelect
                            @change="clients.fetch()"
                            v-model:state="clients.query.statuses"
                            :initialText="$gettext('Select Status')"
                            :options="{
                                'Abandoned'  : 'Abandoned',
                                'Prospect'   : 'Prospect',
                                'Started'    : 'Started',
                                'Completed'  : 'Completed',
                                'Working'    : 'Working',
                                'Outsourced' : 'Outsourced',
                                'Funded'     : 'Funded',
                                'Declined'   : 'Declined',
                                'Archive'    : 'Archive',
                                'Inactive'   : 'Inactive'
                            }"
                        />
                    </div>

                </div>

                <table class="styled w-full text-sm xl:text-base">

                    <thead>

                        <tr class="bg-if-silver-100 border-t border-gray-200">
                            <th scope="col">
                                <div @click="clients.sortBy('created')" class="flex sortable white">
                                    <div>
                                        {{ $gettext('Date') }}
                                    </div>
                                    <div class="ml-auto pl-2">
                                        <i :class="clients.sortIcon('created')"></i>
                                    </div>
                                </div>
                            </th>
                            <th scope="col">
                                <div @click="clients.sortBy('company_name')" class="flex sortable">
                                    <div>
                                        {{ $gettext('Company') }}
                                    </div>
                                    <div class="ml-auto pl-2">
                                        <i :class="clients.sortIcon('company_name')"></i>
                                    </div>
                                </div>
                            </th>
                            <th scope="col">
                                <div @click="clients.sortBy('email')" class="flex sortable">
                                    <div>
                                        {{ $gettext('Email Address') }}
                                    </div>
                                    <div class="ml-auto pl-2">
                                        <i :class="clients.sortIcon('email')"></i>
                                    </div>
                                </div>
                            </th>
                            <th scope="col">
                                <div @click="clients.sortBy('request_amount')" class="flex sortable">
                                    <div>
                                        {{ $gettext('Amount') }}
                                    </div>
                                    <div class="ml-auto pl-2">
                                        <i :class="clients.sortIcon('request_amount')"></i>
                                    </div>
                                </div>
                            </th>
                            <th scope="col">
                                <div @click="clients.sortBy('form_type')" class="flex sortable">
                                    <div>
                                        {{ $gettext('Form') }}
                                    </div>
                                    <div class="ml-auto pl-2">
                                        <i :class="clients.sortIcon('form_type')"></i>
                                    </div>
                                </div>
                            </th>
                            <th scope="col">
                                <div @click="clients.sortBy('status')" class="flex sortable">
                                    <div>
                                        {{ $gettext('Status') }}
                                    </div>
                                    <div class="ml-auto pl-2">
                                        <i :class="clients.sortIcon('status')"></i>
                                    </div>
                                </div>
                            </th>
                            <th class="icons px-4 py-2">
                                &nbsp;
                            </th>
                        </tr>

                    </thead>

                    <tbody class="relative overflow-y-auto">

                        <tr v-for="(item, i) in clients.list" class="border-t border-gray-200">
                            <td class="px-4 h-16">
                                <a :href="'/admin/clients/edit/' + item.id" class="whitespace-nowrap">
                                    {{ item.created }}
                                </a>
                            </td>
                            <td class="px-4 h-16">
                                <a :href="'/admin/clients/edit/' + item.id">
                                    {{ item.company_name }}
                                </a>
                            </td>
                            <td class="px-4 h-16 max-w-[200px] xl:max-w-[300px] overflow-hidden truncate">
                                <a :href="'mailto:' + item.email" class="text-if-denim">
                                    {{ item.email }}
                                </a>
                            </td>
                            <td class="px-4 h-16">
                                <a :href="'/admin/clients/edit/' + item.id">
                                    {{ item.request_amount ?? 'n/a' }}
                                </a>
                            </td>
                            <td class="px-4 h-16">
                                <a :href="'/admin/clients/edit/' + item.id">
                                    {{ item.form_type }}
                                </a>
                            </td>
                            <td class="px-4 h-16">
                                <a :href="'/admin/clients/edit/' + item.id">
                                    {{ item.status }}
                                </a>
                            </td>
                            <td class="px-4 h-16 icons">
                                <a :href="'/admin/clients/edit/' + item.id">
                                    <i class="
                                        fa-light fa-pen-to-square
                                        mr-3.5 text-xl align-middle cursor-pointer
                                        opacity-70 hover:opacity-100">
                                    </i>
                                </a>
                                <i @click="modalDelete.open(item.id)" class="
                                    fa-light fa-trash-can
                                    text-xl align-middle cursor-pointer
                                    opacity-70 hover:opacity-100">
                                </i>
                            </td>
                        </tr>

                        <tr
                            v-if="!clients.loading && !clients.list.length"
                            class="border-t border-gray-200">
                            <td colspan="7" class="text-center p-3">
                                No results matched the query criteria.
                            </td>
                        </tr>

                        <tr v-if="clients.loading">
                            <td colspan="7">
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

                    <div class="flex">

                        <div class="flex flex-col whitespace-nowrap pr-4 pl-3">
                            <div class="h-10 leading-10">
                                {{ $gettext('Search') }}:
                            </div>
                            <div class="h-10 leading-10 mt-2 text-right">
                                {{ $gettext('Sort By') }}:
                            </div>
                            <div class="h-10 leading-10 mt-2 text-right">
                                {{ $gettext('Form') }}:
                            </div>
                            <div class="h-10 leading-10 mt-2 text-right">
                                {{ $gettext('Status') }}:
                            </div>
                        </div>

                        <div class="flex-grow min-w-0">
                            <input
                                @keyup="clients.prefetch()"
                                v-model="clients.query.search"
                                type="text"
                                class="styled w-full"
                                :placeholder="$gettext('Search') + '...'"
                            />
                            <select
                                @change="clients.fetch()"
                                v-model="clients.query.sort_by"
                                class="styled w-full mt-2">
                                <option value="company_name">{{ $gettext('Company') }}</option>
                                <option value="email">{{ $gettext('Email') }}</option>
                                <option value="request_amount">{{ $gettext('Amount') }}</option>
                                <option value="form_type">{{ $gettext('Form') }}</option>
                                <option value="status">{{ $gettext('Status') }}</option>
                            </select>
                            <select
                                @change="clients.fetch()"
                                v-model="clients.query.form_type"
                                class="styled w-full mt-2">
                                <option value="">{{ $gettext('All Forms') }}</option>
                                <option disabled="disabled">-</option>
                                <option value="commercial_capital">{{ $gettext('Commercial Capital') }}</option>
                                <option value="venture_capital">{{ $gettext('Growth & Venture Capital') }}</option>
                            </select>
                            <div class="w-full mt-2">
                                <MultiSelect
                                    @change="clients.fetch()"
                                    v-model:state="clients.query.statuses"
                                    :initialText="$gettext('Select Status')"
                                    :options="{
                                        'Abandoned'  : 'Abandoned',
                                        'Prospect'   : 'Prospect',
                                        'Started'    : 'Started',
                                        'Completed'  : 'Completed',
                                        'Working'    : 'Working',
                                        'Outsourced' : 'Outsourced',
                                        'Funded'     : 'Funded',
                                        'Declined'   : 'Declined',
                                        'Archive'    : 'Archive',
                                        'Inactive'   : 'Inactive'
                                    }"
                                />
                            </div>
                        </div>

                    </div>

                </div>

            </div>

            <div v-for="(item, i) in clients.list" class="px-2 pt-2">

                <div class="relative p-2 text-sm bg-white rounded">
                    <div class="absolute top-2 right-2">
                        <a :href="'/admin/clients/edit/' + item.id">
                            <i class="
                                fa-light fa-pen-to-square
                                mr-3.5 text-xl align-middle cursor-pointer
                                opacity-70 hover:opacity-100">
                            </i>
                        </a>
                        <i @click="modalDelete.open()" class="
                            fa-light fa-trash-can
                            text-xl align-middle cursor-pointer
                            opacity-70 hover:opacity-100">
                        </i>
                    </div>
                    <div>
                        {{ item.company_name }}
                    </div>
                    <div class="max-w-[300px] overflow-hidden truncate">
                        <a :href="'mailto:' + item.email" class="text-if-denim">
                            {{ item.email }}
                        </a>
                    </div>
                    <div class="flex items-center">
                        <div>
                            {{ item.created }}
                        </div>
                        <div class="ml-auto italic text-xs">
                            {{ item.form_type }} | {{ item.status }}
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
                    {{ clients.text }}
                </div>
                <ul class="flex justify-end">
                    <li v-for="page in clients.pages">
                        <div v-if="'...' === page"
                            class="pl-2 py-1">
                            ...
                        </div>
                        <div v-else-if="page === clients.query.page"
                            @click="clients.setPage(page)"
                            class="
                                ml-2 px-2.5 py-1 cursor-pointer rounded
                                text-white bg-if-hippie-blue hover:bg-if-hippie-blue-dark">
                            {{ page }}
                        </div>
                        <div v-else
                            @click="clients.setPage(page)"
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

    <WidgetModal
        @close="modalDelete.close()"
        :active="modalDelete.active"
        title="Delete Client Record">
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

    <LoadingIndicator v-if="modalDelete.loading" />

    <WidgetError v-model:message="noticeError" />

    <WidgetSuccess v-model:message="noticeSuccess" />

</template>
