<template>

    <div class="w-full mt-5">

        <div :class="errors.request_type ? 'has-error' : ''" class="
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

</template>

<style scoped="scoped">
    .has-error {
        @apply
            border-red-600
            border-2;
    }
</style>

<script>

    export default {
        props : [
            'state',
            'errors',
        ],
        setup(props, { emit }) {
            function isCommercial() {
                return 'commercial_capital' === props.state.form_type;
            };

            function isVenture() {
                return 'venture_capital' === props.state.form_type;
            };

            return {
                isCommercial,
                isVenture,
            };
        }
    };

</script>
