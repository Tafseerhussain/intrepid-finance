<template>

    <div class="w-full">

        <div :class="errors.credit_score ? 'has-error' : ''" class="
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
            return {};
        }
    };

</script>
