<template>

    <div class="w-full">

        <div :class="errors.corp_type ? 'has-error' : ''" class="
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
