<template>

    <div hidden>
        <div :class="className">
            <i class="fa-solid fa-circle-question"></i>
        </div>
    </div>

</template>

<script>

    import { computed, ref } from 'vue';

    export default {
        props : {
            className : {
                default : 'pr-6 text-xl',
            },
        },
        setup() {
            return {};
        }
    }

</script>
