<template>

    <input
        ref="inputRef"
        type="text"
    />

</template>

<script>
    import { useCurrencyInput } from 'vue-currency-input';

    export default {
        name  : 'CurrencyInput',
        props : {
            modelValue : Number,
            options    : Object
        },
        setup(props) {
            let options;

            if ( ! props.options) {
                options = {
                    currency                  : 'USD',
                    currencyDisplay           : 'narrowSymbol',
                    locale                    : 'en',
                    precision                 : 2,
                    hideCurrencySymbolOnFocus : false,
                };
            } else {
                options = props.options;
            }

            const { inputRef } = useCurrencyInput(options);

            return {
                inputRef
            };
        }
    }
</script>
