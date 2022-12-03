<script setup>

    import { reactive, ref } from 'vue';
    import { useDeviceStore } from '@stores/Device.js';
    import { useClientsNotesStore } from './stores/ClientsNotes.js';
    import { useClientsChangesStore } from './stores/ClientsChanges.js';
    import FormHandler from '@mixins/FormHandler.js';

    class Manager {
        constructor(state, notes, changes, noticeError, noticeSuccess) {
            this.state           = state;
            this.notes           = notes;
            this.changes         = changes;
            this.noticeError     = noticeError;
            this.noticeSuccess   = noticeSuccess;
            this.loading         = false;

            this.fetchAddressBusiness();
            this.fetchAddressHome();
            this.fetchNotes();
            this.fetchChanges();
        }

        async fetchUser(fn = null) {
            await axios
                .get('/admin/fetch/clients/' + this.state.id)
                .then((res) => {
                    Object.keys(res.data).forEach(key => {
                        this.state[key] = res.data[key];
                    });

                    if (typeof fn === 'function') {
                        fn();
                    }
                }).catch((error) => {
                    this.noticeError = 'Failed to load record data.';
                });
        }

        async fetchNotes(fn = null) {
            useClientsNotesStore().fetch(this.state.id);
        }

        async fetchChanges(fn = null) {
            useClientsChangesStore().fetch(this.state.id);
        }

        async fetchAddressBusiness(fn = null) {
            await axios
                .get('/admin/fetch/clients/' + this.state.id + '/address-business')
                .then((res) => {
                    this.state.addressBusiness = res.data;

                    if (typeof fn === 'function') {
                        fn();
                    }
                }).catch((error) => {
                    this.noticeError = 'Failed to load business address.';
                });
        }

        async fetchAddressHome(fn = null) {
            await axios
                .get('/admin/fetch/clients/' + this.state.id + '/address-home')
                .then((res) => {
                    this.state.addressHome = res.data;

                    if (typeof fn === 'function') {
                        fn();
                    }
                }).catch((error) => {
                    this.noticeError = 'Failed to load business address.';
                });
        }
    };

    class FormEdit extends FormHandler {
        constructor(errors, state, manager) {
            super(errors, state);
            this.manager = manager;
        }

        async submit() {
            this.loading = true;

            await axios
                .post('/admin/clients/edit/' + this.state.id, this.state)
                .then((res) => {
                    this.manager.fetchNotes();
                    this.manager.fetchChanges();
                    this.manager.fetchUser(() => {
                        this.loading               = false;
                        this.state.note            = null;
                        this.manager.noticeSuccess = 'Changes saved successfully!';
                    });
                }).catch((error) => {
                    this.loading = false;
                    this.handleErrors(error.response.data.errors);
                });
        }
    };

    class ModalBusinessAddress extends FormHandler {
        constructor(errors, state, manager) {
            super(errors, state);
            this.manager = manager;
            this.active  = false;
        }

        open() {
            this.active = true;
        }

        close() {
            this.active = false;
        }

        async submit() {
            this.manager.loading = true;

            await axios
                .post('/admin/clients/edit/' + this.state.id + '/address-business', this.state)
                .then((res) => {
                    this.close();
                    this.manager.loading       = false;
                    this.manager.noticeError   = null;
                    this.manager.noticeSuccess = 'Address saved successfully.';
                    this.manager.fetchAddressBusiness();
                    this.manager.fetchChanges();
                }).catch((error) => {
                    this.manager.loading = false;
                    this.handleErrors(error.response.data.errors);
                });
        }
    };

    class ModalHomeAddress extends FormHandler {
        constructor(errors, state, manager) {
            super(errors, state);
            this.manager = manager;
            this.active  = false;
        }

        open() {
            this.active = true;
        }

        close() {
            this.active = false;
        }

        async submit() {
            this.manager.loading = true;

            await axios
                .post('/admin/clients/edit/' + this.state.id + '/address-home', this.state)
                .then((res) => {
                    this.close();
                    this.manager.loading       = false;
                    this.manager.noticeError   = null;
                    this.manager.noticeSuccess = 'Address saved successfully.';
                    this.manager.fetchAddressHome();
                    this.manager.fetchChanges();
                }).catch((error) => {
                    this.manager.loading = false;
                    this.handleErrors(error.response.data.errors);
                });
        }
    };

    class ModalNoteEdit extends FormHandler {
        constructor(errors, state, manager) {
            super(errors, state);
            this.manager = manager;
            this.active  = false;
            this.id      = null;
        }

        open() {
            this.active = true;
        }

        close() {
            this.active = false;
            this.id     = null;
        }

        async load(id) {
            this.loading = true;
            this.id      = id;

            await axios
                .get('/admin/fetch/clients/notes/' + id)
                .then((res) => {
                    this.manager.state.note_edit = res.data.note;
                    this.loading                 = false;
                    this.open();
                }).catch((error) => {
                    this.loading = false;
                });
        }

        async submit() {
            this.loading = true;

            await axios
                .post('/admin/clients/notes/edit/' + this.id, this.manager.state)
                .then((res) => {
                    this.close();
                    this.loading                 = false;
                    this.manager.state.note_edit = null;
                    this.manager.noticeSuccess   = 'Note edited successfully.';
                    this.manager.fetchNotes();
                }).catch((error) => {
                    this.loading               = false;
                    this.manager.noticeSuccess = null;
                    this.manager.noticeError   = null;
                    this.handleErrors(error.response.data.errors);
                });
        }
    };

    class ModalNoteDelete {
        constructor(manager) {
            this.manager = manager;
            this.active  = false;
            this.id      = null;
        }

        open(id) {
            this.active = true;
            this.id     = id;
        }

        close() {
            this.active = false;
            this.id     = null;
        }

        async submit() {
            this.loading = true;

            await axios
                .post('/admin/clients/notes/delete/' + this.id, this.manager.state)
                .then((res) => {
                    this.close();
                    this.loading               = false;
                    this.manager.noticeSuccess = 'Note deleted successfully.';
                    this.manager.fetchNotes();
                }).catch((error) => {
                    this.close();
                    this.loading               = false;
                    this.manager.noticeSuccess = null;
                    this.manager.noticeError   = error.response.data.errors.id;
                });
        }
    };

    class History {
        constructor() {
            this.state = 'notes';
        }

        openNotes() {
            this.state = 'notes';
        }

        openChanges() {
            this.state = 'changes';
        }

        isNotes() {
            return 'notes' === this.state;
        }

        isChanges() {
            return 'changes' === this.state;
        }
    };

    const props = defineProps(['data']);

    const state = reactive({
        id               : props.data.id ?? 0,
        form_type        : props.data.form_type ?? 'commercial_capital',
        form_token       : props.data.form_token ?? null,
        reference_number : props.data.reference_number ?? null,
        request_amount   : props.data.request_amount ?? null,
        request_type     : {
            equipment           : props.data.request_type?.equipment ?? 'N',
            invoice_factoring   : props.data.request_type?.invoice_factoring ?? 'N',
            accounts_receivable : props.data.request_type?.accounts_receivable ?? 'N',
            lines_of_credit     : props.data.request_type?.lines_of_credit ?? 'N',
            growth_capital      : props.data.request_type?.growth_capital ?? 'N',
            venture_capital     : props.data.request_type?.venture_capital ?? 'N',
        },
        company_name       : props.data.company_name ?? null,
        first_name         : props.data.first_name ?? null,
        last_name          : props.data.last_name ?? null,
        email              : props.data.email ?? null,
        phone1             : props.data.phone1 ?? null,
        phone2             : props.data.phone2 ?? null,
        dob                : props.data.dob ?? null,
        ssn                : props.data.ssn ?? null,
        years_in_business  : props.data.years_in_business ?? null,
        tax_id             : props.data.tax_id ?? null,
        revenue_annually   : props.data.revenue_annually ?? null,
        revenue_monthly    : props.data.revenue_monthly ?? null,
        churn_rate         : props.data.churn_rate ?? null,
        previous_financier : props.data.previous_financier ?? null,
        money_raised       : props.data.money_raised ?? null,
        corp_type          : props.data.corp_type ?? null,
        credit_score       : props.data.credit_score ?? null,
        business_address1  : props.data.business_address1 ?? null,
        business_address2  : props.data.business_address2 ?? null,
        business_city      : props.data.business_city ?? null,
        business_province  : props.data.business_province ?? null,
        business_postal    : props.data.business_postal ?? null,
        business_country   : props.data.business_country ?? 'US',
        home_address1      : props.data.home_address1 ?? null,
        home_address2      : props.data.home_address2 ?? null,
        home_city          : props.data.home_city ?? null,
        home_province      : props.data.home_province ?? null,
        home_postal        : props.data.home_postal ?? null,
        home_country       : props.data.home_country ?? 'US',
        ref_a_name         : props.data.ref_a_name ?? null,
        ref_a_phone        : props.data.ref_a_phone ?? null,
        ref_a_payment      : props.data.ref_a_payment ?? null,
        ref_b_name         : props.data.ref_b_name ?? null,
        ref_b_phone        : props.data.ref_b_phone ?? null,
        ref_b_payment      : props.data.ref_b_payment ?? null,
        status             : props.data.status ?? 'Abandoned',
        needs_account      : props.data.needs_account ?? true,
        mx_needs_widget    : props.data.mx_needs_widget ?? true,
        password           : null,
        password_again     : null,
        addressBusiness    : null,
        addressHome        : null,
        note               : null,
        note_edit          : null,
    });

    function inputCss(field) {
        return errors[field] ? 'has-error' : '';
    };

    function isCommercial() {
        return 'commercial_capital' === state.form_type;
    };

    function isVenture() {
        return 'venture_capital' === state.form_type;
    };

    function showAccountAccess() {
        return ! state.needs_account || state.status !== 'Abandoned';
    };

    const errors = reactive({});

    const notes = useClientsNotesStore();

    const changes = useClientsChangesStore();

    const noticeError = ref('');

    const noticeSuccess = ref('');

    const manager = ref(new Manager(state, notes, changes, noticeError, noticeSuccess));

    const formEdit = ref(new FormEdit(errors, state, manager));

    const device = useDeviceStore();

    const
        modalBusinessAddress = ref(new ModalBusinessAddress(errors, state, manager)),
        modalHomeAddress     = ref(new ModalHomeAddress(errors, state, manager)),
        modalNoteEdit        = ref(new ModalNoteEdit(errors, state, manager)),
        modalNoteDelete      = ref(new ModalNoteDelete(manager)),
        history              = ref(new History());

</script>

<style scoped="scoped">

    .has-error {
        @apply
            border-red-600
            border-2;
    }

</style>

<template>

    <LoadingIndicator v-if="formEdit.loading" />

    <WidgetError v-model:message="noticeError" />

    <WidgetSuccess v-model:message="noticeSuccess" />

    <div class="sm:flex">

        <div class="
            w-full sm:w-1/2 p-5 sm:mr-1 md:!mr-2.5
            bg-white rounded shadow-sm cursor-not-allowed">
            <i v-if="isCommercial()" class="fa-sharp fa-solid fa-circle-dot"></i>
            <i v-else class="fa-duotone fa-circle"></i>
            <span class="ml-3 text-sm lg:text-base">
                Commercial Capital
            </span>
        </div>

        <div class="
            w-full sm:w-1/2 p-5 mt-2 sm:mt-0 sm:ml-1 md:!ml-2.5
           bg-white rounded shadow-sm cursor-not-allowed">
            <i v-if="isVenture()" class="fa-sharp fa-solid fa-circle-dot"></i>
            <i v-else class="fa-duotone fa-circle"></i>
            <span class="ml-3 text-sm lg:text-base">
                Growth &amp; Venture Capital
            </span>
        </div>

    </div>

    <div class="mt-4 md:mt-5 shadow-sm">

        <div class="p-3 bg-if-shark-700 text-white/90 font-bold rounded-t">
            Client Financials
        </div>

        <div class="p-2 md:px-5 md:pt-4 md:pb-5 bg-white rounded-b">

            <b>Developer Note:</b> This box is a placeholder until we begin the third-party API
            integrations. At this time it's unclear what data we can acquire from the APIs and what
            data is actually needed by Intrepid.

        </div>

    </div>

    <div class="mt-4 md:mt-5 shadow-sm">

        <div class="p-3 bg-if-shark-700 text-white/90 font-bold rounded-t">
            Capital Request
        </div>

        <div class="p-2 md:px-5 md:pt-4 md:pb-5 bg-white rounded-b">

            <div class="flex flex-col xl:flex-row">

                <div class="md:w-1/2 lg:w-1/3 mt-3 md:mt-0 xl:mr-2.5">
                    <div class="pb-1">
                        Amount Requested
                    </div>
                    <CurrencyInput
                        v-model="state.request_amount"
                        :class="inputCss('request_amount')"
                        class="styled w-full"
                    />
                    <div v-if="errors.request_amount" class="field-error">
                        {{ errors.request_amount }}
                    </div>
                </div>

                <div class="w-full mt-5 xl:mt-7 xl:ml-2.5">

                    <div :class="inputCss('request_type')" class="
                        flex flex-col xl:flex-row w-full py-3 xl:py-0 xl:h-10 xl:items-center
                        text-sm xs:text-base bg-if-silver-100 rounded-md">

                        <label v-if="isCommercial()" class="px-3 flex">
                            <input
                                v-model="state.request_type.equipment"
                                name="request_type[equipment]"
                                true-value="Y"
                                false-value="N"
                                class="mr-2 styled"
                                type="checkbox"
                            /> Equipment Financing
                        </label>

                        <label v-if="isCommercial()" class="px-3 flex">
                            <input
                                v-model="state.request_type.invoice_factoring"
                                name="request_type[invoice_factoring]"
                                true-value="Y"
                                false-value="N"
                                class="mr-2 styled"
                                type="checkbox"
                            /> Invoice Factoring
                        </label>

                        <label v-if="isCommercial()" class="px-3 flex">
                            <input
                                v-model="state.request_type.accounts_receivable"
                                name="request_type[accounts_receivable]"
                                true-value="Y"
                                false-value="N"
                                class="mr-2 styled"
                                type="checkbox"
                            /> Accounts Receivable Financing
                        </label>

                        <label v-if="isCommercial()" class="px-3 flex">
                            <input
                                v-model="state.request_type.lines_of_credit"
                                name="request_type[lines_of_credit]"
                                true-value="Y"
                                false-value="N"
                                class="mr-2 styled"
                                type="checkbox"
                            /> Lines of Credit
                        </label>

                        <label v-if="isVenture()" class="px-3 flex">
                            <input
                                v-model="state.request_type.growth_capital"
                                name="request_type[growth_capital]"
                                true-value="Y"
                                false-value="N"
                                class="mr-2 styled"
                                type="checkbox"
                            /> Growth Capital
                        </label>

                        <label v-if="isVenture()" class="px-3 flex">
                            <input
                                v-model="state.request_type.venture_capital"
                                name="request_type[venture_capital]"
                                true-value="Y"
                                false-value="N"
                                class="mr-2 styled"
                                type="checkbox"
                            /> Venture Capital
                        </label>

                    </div>

                    <div v-if="errors.request_type" class="field-error">
                        {{ errors.request_type }}
                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="mt-4 md:mt-5 shadow-sm">

        <div class="p-3 bg-if-shark-700 text-white/90 font-bold rounded-t">
            Applicant Information
        </div>

        <div class="
            flex flex-col-reverse lg:flex-row p-2 md:px-5 md:pt-4 md:pb-5
            bg-white rounded-b">

            <div class="lg:w-2/3 mt-3 lg:mt-0 lg:mr-2.5">

                <div>
                    <div class="pb-1">
                        Reference Number
                    </div>
                    <input
                        :value="state.reference_number"
                        class="styled disabled w-full"
                        type="text"
                    />
                </div>

                <div class="mt-3">
                    <div class="pb-1">
                        Status
                    </div>
                    <select
                        v-model="state.status"
                        :class="inputCss('status')"
                        class="styled w-full">
                        <option value="Abandoned">Abandoned</option>
                        <option value="Prospect">Prospect</option>
                        <option value="Started">Started</option>
                        <option value="Completed">Completed</option>
                        <option value="Working">Working</option>
                        <option value="Outsourced">Outsourced</option>
                        <option value="Funded">Funded</option>
                        <option value="Declined">Declined</option>
                        <option value="Archive">Archive</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                    <div v-if="errors.status" class="field-error">
                        {{ errors.status }}
                    </div>
                </div>

                <div class="mt-3">
                    <div class="pb-1">
                        Company Name
                    </div>
                    <input
                        v-model="state.company_name"
                        :class="inputCss('company_name')"
                        class="styled w-full"
                        type="text"
                        placeholder="Company Name"
                    />
                    <div v-if="errors.company_name" class="field-error">
                        {{ errors.company_name }}
                    </div>
                </div>

                <div class="md:flex">

                    <div class="mt-3 md:w-1/2 md:mr-2.5">
                        <div class="pb-1">
                            Principal Owner's Name
                        </div>
                        <input
                            v-model="state.first_name"
                            :class="inputCss('first_name')"
                            class="styled w-full"
                            type="text"
                            placeholder="Last Name"
                        />
                        <div v-if="errors.first_name" class="field-error">
                            {{ errors.first_name }}
                        </div>
                    </div>

                    <div class="mt-3 md:w-1/2 md:ml-2.5">
                        <div class="hidden pb-1 md:block">
                            &nbsp;
                        </div>
                        <input
                            v-model="state.last_name"
                            :class="inputCss('last_name')"
                            class="styled w-full"
                            type="text"
                            placeholder="Last Name"
                        />
                        <div v-if="errors.last_name" class="field-error">
                            {{ errors.last_name }}
                        </div>
                    </div>

                </div>

                <div class="mt-3">
                    <div class="pb-1">
                        Email Address
                    </div>
                    <input
                        v-model="state.email"
                        :class="inputCss('email')"
                        class="styled w-full"
                        type="text"
                        placeholder="example@domain.com"
                    />
                    <div v-if="errors.email" class="field-error">
                        {{ errors.email }}
                    </div>
                </div>

                <div class="md:flex">

                    <div class="mt-3 md:w-1/2 md:mr-2.5">
                        <div class="pb-1">
                            Phone Number
                        </div>
                        <input
                            v-model="state.phone1"
                            :class="inputCss('phone1')"
                            class="styled w-full"
                            type="text"
                            v-maska="['(###) ###-####', '(###) ###-#### x #####']"
                            placeholder="(###) ###-#### x ###"
                        />
                        <div v-if="errors.phone1" class="field-error">
                            {{ errors.phone1 }}
                        </div>
                    </div>

                    <div class="mt-3 md:w-1/2 md:ml-2.5">
                        <div class="pb-1">
                            Cell Number
                        </div>
                        <input
                            v-model="state.phone2"
                            :class="inputCss('phone2')"
                            class="styled w-full"
                            type="text"
                            v-maska="['(###) ###-####', '(###) ###-#### x #####']"
                            placeholder="(###) ###-####"
                        />
                        <div v-if="errors.phone2" class="field-error">
                            {{ errors.phone2 }}
                        </div>
                    </div>

                </div>

                <div class="md:flex">

                    <div class="mt-3 md:w-1/2 md:mr-2.5">
                        <div class="pb-1">
                            Date of Birth
                        </div>
                        <input
                            v-model="state.dob"
                            :class="inputCss('dob')"
                            class="styled w-full"
                            type="date"
                        />
                        <div v-if="errors.dob" class="field-error">
                            {{ errors.dob }}
                        </div>
                    </div>

                    <div class="mt-3 md:w-1/2 md:ml-2.5">
                        <div class="pb-1">
                            Social Security # (Last 4)
                        </div>
                        <input
                            v-model="state.ssn"
                            :class="inputCss('ssn')"
                            class="styled w-full"
                            type="text"
                            v-maska="'####'"
                            placeholder="####"
                        />
                        <div v-if="errors.ssn" class="field-error">
                            {{ errors.ssn }}
                        </div>
                    </div>

                </div>

                <div class="md:flex">

                    <div class="mt-3 md:w-1/2 md:mr-2.5">
                        <div class="pb-1">
                            Years in Business
                        </div>
                        <input
                            v-model="state.years_in_business"
                            :class="inputCss('years_in_business')"
                            class="styled w-full"
                            type="text"
                            v-maska="'#*'"
                        />
                        <div v-if="errors.years_in_business" class="field-error">
                            {{ errors.years_in_business }}
                        </div>
                    </div>

                    <div class="mt-3 md:w-1/2 md:ml-2.5">
                        <div class="pb-1">
                            Tax ID #
                        </div>
                        <input
                            v-model="state.tax_id"
                            :class="inputCss('tax_id')"
                            class="styled w-full"
                            type="text"
                            v-maska="'##-######'"
                            placeholder="##-######"
                        />
                        <div v-if="errors.tax_id" class="field-error">
                            {{ errors.tax_id }}
                        </div>
                    </div>

                </div>

                <div v-if="isCommercial()" class="md:flex">

                    <div class="mt-3 md:w-1/2 md:mr-2.5">
                        <div class="pb-1">
                            Annual Revenue
                        </div>
                        <CurrencyInput
                            v-model="state.revenue_annually"
                            :class="inputCss('revenue_annually')"
                            class="styled w-full"
                        />
                        <div v-if="errors.revenue_annually" class="field-error">
                            {{ errors.revenue_annually }}
                        </div>
                    </div>

                    <div class="mt-3 md:w-1/2 md:ml-2.5">
                        <div class="pb-1">
                            Previous Financier
                        </div>
                        <input
                            v-model="state.previous_financier"
                            :class="inputCss('previous_financier')"
                            class="styled w-full"
                            type="text"
                        />
                        <div v-if="errors.previous_financier" class="field-error">
                            {{ errors.previous_financier }}
                        </div>
                    </div>

                </div>

                <div v-if="isVenture()" class="md:flex">

                    <div class="mt-3 md:w-1/2 md:mr-2.5">
                        <div class="pb-1">
                            Churn Rate
                        </div>
                        <input
                            v-model="state.churn_rate"
                            :class="inputCss('churn_rate')"
                            class="styled w-full"
                            type="text"
                        />
                        <div v-if="errors.churn_rate" class="field-error">
                            {{ errors.churn_rate }}
                        </div>
                    </div>

                    <div class="mt-3 md:w-1/2 md:ml-2.5">
                        <div class="pb-1">
                            Previous Financier
                        </div>
                        <input
                            v-model="state.previous_financier"
                            :class="inputCss('previous_financier')"
                            class="styled w-full"
                            type="text"
                        />
                        <div v-if="errors.previous_financier" class="field-error">
                            {{ errors.previous_financier }}
                        </div>
                    </div>

                </div>

                <div v-if="isVenture()" class="md:flex">

                    <div class="mt-3 md:w-1/2 md:mr-2.5">
                        <div class="pb-1">
                            Monthly Recurring Revenue (MRR)
                        </div>
                        <CurrencyInput
                            v-model="state.revenue_monthly"
                            :class="inputCss('revenue_monthly')"
                            class="styled w-full"
                        />
                        <div v-if="errors.revenue_monthly" class="field-error">
                            {{ errors.revenue_monthly }}
                        </div>
                    </div>

                    <div class="mt-3 md:w-1/2 md:ml-2.5">
                        <div class="pb-1">
                            Money Raised
                        </div>
                        <CurrencyInput
                            v-model="state.money_raised"
                            :class="inputCss('money_raised')"
                            class="styled w-full"
                        />
                        <div v-if="errors.money_raised" class="field-error">
                            {{ errors.money_raised }}
                        </div>
                    </div>

                </div>

                <div class="mt-3">

                    <div class="pb-1">
                        Corporation Type
                    </div>

                    <div class="w-full">

                        <div :class="inputCss('corp_type')" class="
                            flex flex-col xl:flex-row w-full py-3 xl:py-0 xl:h-10 xl:items-center
                            text-sm xs:text-base bg-if-silver-100 rounded-md">

                            <label class="px-3 flex">
                                <input
                                    v-model="state.corp_type"
                                    name="corp_type"
                                    class="mr-2 styled"
                                    type="radio"
                                    value="llc"
                                /> LLC
                            </label>

                            <label class="px-3 flex">
                                <input
                                    v-model="state.corp_type"
                                    name="corp_type"
                                    class="mr-2 styled"
                                    type="radio"
                                    value="s_corp"
                                /> S Corp
                            </label>

                            <label class="px-3 flex">
                                <input
                                    v-model="state.corp_type"
                                    name="corp_type"
                                    class="mr-2 styled"
                                    type="radio"
                                    value="c_corp"
                                /> C Corp
                            </label>

                        </div>

                        <div v-if="errors.corp_type" class="field-error">
                            {{ errors.corp_type }}
                        </div>

                    </div>

                </div>

                <div class="mt-3">

                    <div class="pb-1">
                        Credit Score
                    </div>

                    <div class="w-full">

                        <div :class="inputCss('credit_score')" class="
                            flex flex-col xl:flex-row w-full py-3 xl:py-0 xl:h-10 xl:items-center
                            text-sm xs:text-base bg-if-silver-100 rounded-md">

                            <label class="px-3 flex">
                                <input
                                    v-model="state.credit_score"
                                    name="credit_score"
                                    class="mr-2 styled"
                                    type="radio"
                                    value="720+"
                                /> 720+
                            </label>

                            <label class="px-3 flex">
                                <input
                                    v-model="state.credit_score"
                                    name="credit_score"
                                    class="mr-2 styled"
                                    type="radio"
                                    value="720-625"
                                /> 720-625
                            </label>

                            <label class="px-3 flex">
                                <input
                                    v-model="state.credit_score"
                                    name="credit_score"
                                    class="mr-2 styled"
                                    type="radio"
                                    value="625-550"
                                /> 625-550
                            </label>

                            <label class="px-3 flex">
                                <input
                                    v-model="state.credit_score"
                                    name="credit_score"
                                    class="mr-2 styled"
                                    type="radio"
                                    value="550-"
                                /> 550 and below
                            </label>

                            <label class="px-3 flex">
                                <input
                                    v-model="state.credit_score"
                                    name="credit_score"
                                    class="mr-2 styled"
                                    type="radio"
                                    value="unknown"
                                /> Unknown
                            </label>

                        </div>

                        <div v-if="errors.credit_score" class="field-error">
                            {{ errors.credit_score }}
                        </div>

                    </div>

                </div>

            </div>

            <div class="lg:w-1/3 mt-1 lg:ml-2.5">

                <div class="border border-if-silver-300 rounded">
                    <div class="flex items-center px-3 py-2 bg-if-silver-100 rounded-t">
                        <div>
                            Business Address
                        </div>
                        <i
                            @click="modalBusinessAddress.open()"
                            class="
                                ml-auto cursor-pointer
                                text-if-shark-500 hover:text-if-shark-900
                                fa-sharp fa-solid fa-pen">
                        </i>
                    </div>
                    <div class="px-3 py-2 border-t border-if-silver-500 whitespace-pre">
                        {{ state.addressBusiness }}
                    </div>
                </div>

                <div class="mt-5 border border-if-silver-300 rounded">
                    <div class="flex items-center px-3 py-2 bg-if-silver-100 rounded-t">
                        <div>
                            Home Address
                        </div>
                        <i
                            @click="modalHomeAddress.open()"
                            class="
                                ml-auto cursor-pointer
                                text-if-shark-500 hover:text-if-shark-900
                                fa-sharp fa-solid fa-pen">
                        </i>
                    </div>
                    <div class="px-3 py-2 border-t border-if-silver-500 whitespace-pre">
                        {{ state.addressHome }}
                    </div>
                </div>

                <div v-if="showAccountAccess()" class="mt-5 border border-if-silver-300 rounded">
                    <div class="flex items-center px-3 py-2 bg-if-silver-100 rounded-t">
                        <div>
                            Account Access
                        </div>
                    </div>
                    <div class="border-t border-if-silver-500">

                        <div v-if="state.needs_account" class="p-3 border-b border-if-silver-300">

                            <em class="text-sm">
                                A password hasn't yet been created for this account. You can create
                                one now to give to the client (they can change it in their dashboard
                                later).
                            </em>

                        </div>

                        <div v-else class="flex items-center p-3 border-b border-if-silver-300">

                            <div class="w-7/12">
                                <span class="hidden xl:inline">Client</span> Dashboard:
                            </div>

                            <a
                                :href="'/admin/clients/login/' + state.id"
                                target="_blank"
                                class="button generic w-5/12 h-10 leading-10 px-3 text-center">
                                <span class="hidden xl:inline">Direct</span> Login
                            </a>

                        </div>

                        <div class="p-3">

                            <div>
                                <div v-if="state.needs_account" class="pb-1">
                                    Password
                                </div>
                                <div v-else class="pb-1">
                                    New Password (Optional)
                                </div>
                                <input
                                    v-model="state.password"
                                    :class="inputCss('password')"
                                    class="styled w-full"
                                    type="password"
                                />
                                <div v-if="errors.password" class="field-error">
                                    {{ errors.password }}
                                </div>
                            </div>

                            <div v-if="state.password" class="mt-3">
                                <div v-if="state.needs_account" class="pb-1">
                                    Password Again
                                </div>
                                <div v-else class="pb-1">
                                    New Password Again
                                </div>
                                <input
                                    v-model="state.password_again"
                                    class="styled w-full"
                                    :class="inputCss('password_again')"
                                    type="password"
                                />
                                <div v-if="errors.password_again" class="field-error">
                                    {{ errors.password_again }}
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>

    <div class="mt-4 md:mt-5 shadow-sm">

        <div class="p-3 bg-if-shark-700 text-white/90 font-bold rounded-t">
            Credit References
        </div>

        <div class="bg-white rounded-b">

            <div v-if="isCommercial()" class="
                p-2
                md:px-5 md:pt-4 md:pb-0
                lg:flex">
                <div class="lg:w-1/2 lg:pr-2.5">
                    <div class="pb-1">
                        Reference 1
                    </div>
                    <input
                        v-model="state.ref_a_name"
                        :class="inputCss('ref_a_name')"
                        class="styled w-full"
                        type="text"
                        placeholder="Reference 1 Name"
                    />
                    <div v-if="errors.ref_a_name" class="field-error">
                        {{ errors.ref_a_name }}
                    </div>
                </div>
                <div class="lg:w-1/4 mt-3 lg:mt-0 lg:px-2.5">
                    <div class="pb-1">
                        Phone Number
                    </div>
                    <input
                        v-model="state.ref_a_phone"
                        :class="inputCss('ref_a_phone')"
                        class="styled w-full"
                        type="text"
                        v-maska="['(###) ###-####', '(###) ###-#### x #####']"
                        placeholder="(###) ###-#### x ###"
                    />
                    <div v-if="errors.ref_a_phone" class="field-error">
                        {{ errors.ref_a_phone }}
                    </div>
                </div>
                <div class="lg:w-1/4 mt-3 lg:mt-0 lg:pl-2.5">
                    <div class="pb-1">
                        Monthly Payment
                    </div>
                    <CurrencyInput
                        v-model="state.ref_a_payment"
                        :class="inputCss('ref_a_payment')"
                        class="styled w-full"
                    />
                    <div v-if="errors.ref_a_payment" class="field-error">
                        {{ errors.ref_a_payment }}
                    </div>
                </div>
            </div>

            <div v-if="isCommercial()" class="
                p-2 border-t border-if-silver-500
                md:mt-5 md:px-5 md:pt-4 md:pb-5
                lg:flex lg:mt-0 lg:border-none">
                <div class="lg:w-1/2 lg:pr-2.5">
                    <div class="pb-1">
                        Reference 2
                    </div>
                    <input
                        v-model="state.ref_b_name"
                        :class="inputCss('ref_b_name')"
                        class="styled w-full"
                        type="text"
                        placeholder="Reference 2 Name"
                    />
                    <div v-if="errors.ref_b_name" class="field-error">
                        {{ errors.ref_b_name }}
                    </div>
                </div>
                <div class="lg:w-1/4 mt-3 lg:mt-0 lg:px-2.5">
                    <div class="pb-1">
                        Phone Number
                    </div>
                    <input
                        v-model="state.ref_b_phone"
                        :class="inputCss('ref_b_phone')"
                        class="styled w-full"
                        type="text"
                        v-maska="['(###) ###-####', '(###) ###-#### x #####']"
                        placeholder="(###) ###-#### x ###"
                    />
                    <div v-if="errors.ref_b_phone" class="field-error">
                        {{ errors.ref_b_phone }}
                    </div>
                </div>
                <div class="lg:w-1/4 mt-3 lg:mt-0 lg:pl-2.5">
                    <div class="pb-1">
                        Monthly Payment
                    </div>
                    <CurrencyInput
                        v-model="state.ref_b_payment"
                        :class="inputCss('ref_b_payment')"
                        class="styled w-full"
                    />
                    <div v-if="errors.ref_b_payment" class="field-error">
                        {{ errors.ref_b_payment }}
                    </div>
                </div>
            </div>

            <div v-if="isVenture()" class="
                p-2
                md:px-5 md:pt-4 md:pb-0
                lg:flex">
                <div class="lg:w-2/3 lg:pr-2.5">
                    <div class="pb-1">
                        Reference 1
                    </div>
                    <input
                        v-model="state.ref_a_name"
                        :class="inputCss('ref_a_name')"
                        class="styled w-full"
                        type="text"
                        placeholder="Reference 1 Name"
                    />
                    <div v-if="errors.ref_a_name" class="field-error">
                        {{ errors.ref_a_name }}
                    </div>
                </div>
                <div class="lg:w-1/3 mt-3 lg:mt-0 lg:pl-2.5">
                    <div class="pb-1">
                        Phone Number
                    </div>
                    <input
                        v-model="state.ref_a_phone"
                        :class="inputCss('ref_a_phone')"
                        class="styled w-full"
                        type="text"
                        v-maska="['(###) ###-####', '(###) ###-#### x #####']"
                        placeholder="(###) ###-#### x ###"
                    />
                    <div v-if="errors.ref_a_phone" class="field-error">
                        {{ errors.ref_a_phone }}
                    </div>
                </div>
            </div>

            <div v-if="isVenture()" class="
                p-2 border-t border-if-silver-500
                md:mt-5 md:px-5 md:pt-4 md:pb-5
                lg:flex lg:mt-0 lg:border-none">
                <div class="lg:w-2/3 lg:pr-2.5">
                    <div class="pb-1">
                        Reference 2
                    </div>
                    <input
                        v-model="state.ref_b_name"
                        :class="inputCss('ref_b_name')"
                        class="styled w-full"
                        type="text"
                        placeholder="Reference 2 Name"
                    />
                    <div v-if="errors.ref_b_name" class="field-error">
                        {{ errors.ref_b_name }}
                    </div>
                </div>
                <div class="lg:w-1/3 mt-3 lg:mt-0 lg:pl-2.5">
                    <div class="pb-1">
                        Phone Number
                    </div>
                    <input
                        v-model="state.ref_b_phone"
                        :class="inputCss('ref_b_phone')"
                        class="styled w-full"
                        type="text"
                        v-maska="['(###) ###-####', '(###) ###-#### x #####']"
                        placeholder="(###) ###-#### x ###"
                    />
                    <div v-if="errors.ref_b_phone" class="field-error">
                        {{ errors.ref_b_phone }}
                    </div>
                </div>
            </div>

        </div>

    </div>

    <div class="mt-4 md:mt-5 shadow-sm">

        <div class="flex flex-col xs:flex-row bg-if-shark-700 font-bold rounded-t">
            <div @click="history.openNotes()"
                :class="history.isNotes() ? '!bg-if-shark-500 !text-white/100' : ''"
                class="mt-2 mr-2 ml-2 xs:mr-0 px-3 py-2
                    rounded xs:rounded-b-none cursor-pointer
                    bg-if-shark-900 text-white/90
                    hover:bg-if-shark-500 hover:text-white/100">
                    Client Notes
            </div>
            <div @click="history.openChanges()"
                :class="history.isChanges() ? '!bg-if-shark-500 !text-white/100' : ''"
                class="mt-2 mr-2 ml-2 mb-2 xs:mr-0 xs:mb-0 px-3 py-2
                    rounded xs:rounded-b-none cursor-pointer
                    bg-if-shark-900 text-white/90
                    hover:bg-if-shark-500 hover:text-white/100">
                    Client History
            </div>
        </div>

        <div v-if="history.isNotes()"
            class="flex flex-col px-2 pb-2 md:px-3 md:pb-3 bg-white rounded-b">

            <div v-if="notes.list.length > 0">

                <div v-for="(item, i) in notes.list" class="mt-2 md:mt-3 bg-if-silver-100 rounded">
                    <div class="
                        flex flex-col xs:flex-row justify-between p-3
                        border-b border-if-silver-500">
                        <div class="text-sm lg:text-base font-bold">
                            {{ item.author_name }}
                        </div>
                        <div class="flex justify-between items-center mt-1 xs:mt-0">
                            <div class="text-sm lg:text-base">
                                {{ item.created }}
                            </div>
                            <div class="flex items-center ml-3">
                                <i @click="modalNoteEdit.load(item.id)" class="
                                    fa-light fa-pen-to-square
                                    mr-2 text-xl align-middle cursor-pointer
                                    opacity-70 hover:opacity-100">
                                </i>
                                <i @click="modalNoteDelete.open(item.id)" class="
                                    fa-light fa-trash-can
                                    text-xl align-middle cursor-pointer
                                    opacity-70 hover:opacity-100">
                                </i>
                            </div>
                        </div>
                    </div>
                    <div class="p-2 md:p-3 text-sm lg:text-base whitespace-pre-wrap">
                        {{ item.note }}
                    </div>
                </div>

            </div>

            <div v-else class="mt-2 md:mt-3">
                No notes for this client could be found.
            </div>

        </div>

        <div v-if="history.isChanges()"
            class="flex flex-col px-2 pb-2 md:px-3 md:pb-3 bg-white rounded-b">

            <div v-if="changes.list.length > 0">

                <div v-for="(item, i) in changes.list" class="mt-2 md:mt-3 bg-if-silver-100 rounded">
                    <div class="
                        flex flex-col xs:flex-row justify-between p-3
                        border-b border-if-silver-500">
                        <div class="text-sm lg:text-base font-bold">
                            {{ item.author_name }}
                        </div>
                        <div class="flex justify-between items-center mt-1 xs:mt-0">
                            <div class="text-sm lg:text-base">
                                {{ item.created }}
                            </div>
                        </div>
                    </div>
                    <div class="p-2 md:p-3 text-sm lg:text-base">
                        <div v-for="(action, k) in item.actions">
                            {{ action }}
                        </div>
                        <div v-for="(change, k) in item.changes">
                            <u>{{ change.label }}</u> changed from
                            <u>{{ change.before }}</u> to
                            <u>{{ change.after }}</u>.
                        </div>
                    </div>
                </div>

            </div>

            <div v-else class="mt-2 md:mt-3">
                No history for this client could be found.
            </div>

        </div>

    </div>

    <div class="mt-4 md:mt-5">

        <div class="p-3 bg-if-shark-700 text-white/90 font-bold rounded-t">
            Add Note
        </div>

        <textarea
            v-model="state.note"
            :class="inputCss('note')"
            class="styled w-full h-[150px] bg-white rounded-b shadow-sm"
            placeholder="Optional note..."></textarea>

        <div v-if="errors.note" class="field-error">
            {{ errors.note }}
        </div>

    </div>

    <div class="flex mt-4 md:mt-5">

        <button @click="formEdit.submit()" class="generic h-10 px-3">
            Save Changes
        </button>

    </div>

    <WidgetModal
        @close="modalBusinessAddress.close()"
        :active="modalBusinessAddress.active"
        title="Edit Business Address"
        className="md:!max-w-[500px]">
        <template #content>
            <div class="flex flex-col">
                <div class="pb-1">
                    Address
                </div>
                <input
                    v-model="state.business_address1"
                    :class="inputCss('business_address1')"
                    class="styled"
                    type="text"
                    placeholder="Street address or P.O. box"
                />
                <div v-if="errors.business_address1" class="field-error">
                    {{ errors.business_address1 }}
                </div>
                <input
                    v-model="state.business_address2"
                    :class="inputCss('business_address2')"
                    class="styled mt-2 md:mt-5"
                    type="text"
                    placeholder="Apt, suite, unit, building, floor, etc."
                />
                <div v-if="errors.business_address2" class="field-error">
                    {{ errors.business_address2 }}
                </div>
            </div>
            <div class="flex mt-3">
                <div class="w-1/2 pr-1.5">
                    <div class="pb-1">
                        Country
                    </div>
                    <country-select
                        v-model="state.business_country"
                        :country="state.business_country"
                        :whiteList="['US']"
                        :removePlaceholder="true"
                        className="styled w-full"
                    />
                    <div v-if="errors.business_country" class="field-error">
                        {{ errors.business_country }}
                    </div>
                </div>
                <div class="w-1/2 pl-1.5">
                    <div class="pb-1">
                        State / Province
                    </div>
                    <region-select
                        v-model="state.business_province"
                        :className="inputCss('business_province') + ' styled w-full'"
                        :country="state.business_country"
                        :region="state.business_province"
                        placeholder="Please Select"
                    />
                    <div v-if="errors.business_province" class="field-error">
                        {{ errors.business_province }}
                    </div>
                </div>
            </div>
            <div class="flex mt-3 mb-1">
                <div class="w-1/2 pr-1.5">
                    <div class="pb-1">
                        City
                    </div>
                    <input
                        v-model="state.business_city"
                        :class="inputCss('business_city')"
                        class="styled w-full"
                        type="text"
                        placeholder="City"
                    />
                    <div v-if="errors.business_city" class="field-error">
                        {{ errors.business_city }}
                    </div>
                </div>
                <div class="w-1/2 pl-1.5">
                    <div class="pb-1">
                        Postal
                    </div>
                    <input
                        v-model="state.business_postal"
                        :class="inputCss('business_postal')"
                        class="styled w-full"
                        type="text"
                        placeholder="Zip / Postal"
                    />
                    <div v-if="errors.business_postal" class="field-error">
                        {{ errors.business_postal }}
                    </div>
                </div>
            </div>
        </template>
        <template #actions>
            <div class="flex w-full justify-end">
                <button @click="modalBusinessAddress.submit()" class="generic h-10 px-3 mr-3">
                    Save Changes
                </button>
                <button @click="modalBusinessAddress.close()" class="plain h-10 px-3">
                    Cancel
                </button>
            </div>
        </template>
    </WidgetModal>

    <WidgetModal
        @close="modalHomeAddress.close()"
        :active="modalHomeAddress.active"
        title="Edit Home Address"
        className="md:!max-w-[500px]">
        <template #content>
            <div class="flex flex-col">
                <div class="pb-1">
                    Address
                </div>
                <input
                    v-model="state.home_address1"
                    :class="inputCss('home_address1')"
                    class="styled"
                    type="text"
                    placeholder="Street address or P.O. box"
                />
                <div v-if="errors.home_address1" class="field-error">
                    {{ errors.home_address1 }}
                </div>
                <input
                    v-model="state.home_address2"
                    :class="inputCss('home_address2')"
                    class="styled mt-2 md:mt-5"
                    type="text"
                    placeholder="Apt, suite, unit, building, floor, etc."
                />
                <div v-if="errors.home_address2" class="field-error">
                    {{ errors.home_address2 }}
                </div>
            </div>
            <div class="flex mt-3">
                <div class="w-1/2 pr-1.5">
                    <div class="pb-1">
                        Country
                    </div>
                    <country-select
                        v-model="state.home_country"
                        :country="state.home_country"
                        :whiteList="['US']"
                        :removePlaceholder="true"
                        className="styled w-full"
                    />
                    <div v-if="errors.home_country" class="field-error">
                        {{ errors.home_country }}
                    </div>
                </div>
                <div class="w-1/2 pl-1.5">
                    <div class="pb-1">
                        State
                    </div>
                    <region-select
                        v-model="state.home_province"
                        :className="inputCss('home_province') + ' styled w-full'"
                        :country="state.home_country"
                        :region="state.home_province"
                        placeholder="Please Select"
                    />
                    <div v-if="errors.home_province" class="field-error">
                        {{ errors.home_province }}
                    </div>
                </div>
            </div>
            <div class="flex mt-3 mb-1">
                <div class="w-1/2 pr-1.5">
                    <div class="pb-1">
                        City
                    </div>
                    <input
                        v-model="state.home_city"
                        :class="inputCss('home_city')"
                        class="styled w-full"
                        type="text"
                        placeholder="City"
                    />
                    <div v-if="errors.home_city" class="field-error">
                        {{ errors.home_city }}
                    </div>
                </div>
                <div class="w-1/2 pl-1.5">
                    <div class="pb-1">
                        Postal
                    </div>
                    <input
                        v-model="state.home_postal"
                        :class="inputCss('home_postal')"
                        class="styled w-full"
                        type="text"
                        placeholder="Zip / Postal"
                    />
                    <div v-if="errors.home_postal" class="field-error">
                        {{ errors.home_postal }}
                    </div>
                </div>
            </div>
        </template>
        <template #actions>
            <div class="flex w-full justify-end">
                <button @click="modalHomeAddress.submit()" class="generic h-10 px-3 mr-3">
                    Save Changes
                </button>
                <button @click="modalHomeAddress.close()" class="plain h-10 px-3">
                    Cancel
                </button>
            </div>
        </template>
    </WidgetModal>

    <WidgetModal
        @close="modalNoteEdit.close()"
        :active="modalNoteEdit.active"
        title="Edit Client Note">
        <template #content>
            <textarea
                v-model="state.note_edit"
                :class="inputCss('note_edit')"
                class="
                    styled w-full h-[150px]
                    rounded-md border border-gray-300 bg-slate-50"></textarea>
                <div v-if="errors.note_edit" class="field-error">
                    {{ errors.note_edit }}
                </div>
        </template>
        <template #actions>
            <div class="flex w-full justify-end">
                <button @click="modalNoteEdit.submit()" class="generic h-10 px-3 mr-3">
                    Save Changes
                </button>
                <button @click="modalNoteEdit.close()" class="plain h-10 px-3">
                    Cancel
                </button>
            </div>
        </template>
    </WidgetModal>

    <WidgetModal
        @close="modalNoteDelete.close()"
        :active="modalNoteDelete.active"
        title="Delete Client Note">
        <template #content>
            Are you sure you want to delete this client note?
        </template>
        <template #actions>
            <div class="flex w-full justify-end">
                <button @click="modalNoteDelete.submit()" class="generic h-10 px-3 mr-3">
                    Yes
                </button>
                <button @click="modalNoteDelete.close()" class="plain h-10 px-3">
                    No
                </button>
            </div>
        </template>
    </WidgetModal>

</template>
