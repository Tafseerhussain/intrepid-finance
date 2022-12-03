<script setup>

    import { ref } from 'vue';
    import { onClickOutside } from '@vueuse/core';

    const props = defineProps({
        state : {
            default : {},
        },
        options : {
            default : {},
        },
        initialText : {
            default : null,
        },
    });

    const emit = defineEmits(['change', 'close', 'open']);

    const el = ref(),
        active = ref(false),
        selection = ref(props.initialText),
        someSelected = ref(false),
        allSelected = ref(false);

    function selectAll() {
        for (let k in props.options) {
            if (undefined !== props.state[k]) {
                props.state[k] = true;
            }
        }

        change();
    };

    function selectNone() {
        for (let k in props.options) {
            if (undefined !== props.state[k]) {
                props.state[k] = false;
            }
        }

        change();
    };

    function open() {
        active.value = true;
        emit('open');
    };

    function close() {
        active.value = false;
        emit('close');
    };

    function toggle() {
        if (active.value) {
            close();
        } else {
            open();
        }
    };

    function parse() {
        let some = false,
            all  = true;

        for (let k in props.state) {
            let checked = props.state[k];

            if (props.state[k]) {
                some = true;
            } else {
                all = false;
            }
        }

        someSelected.value = some;
        allSelected.value  = all;
    };

    function describe() {
        let text = [];

        for (let k in props.state) {
            let checked = props.state[k];

            if (props.state[k]) {
                text.push(props.options[k]);
            }
        }

        selection.value = text.join(', ');

        if ( ! selection.value) {
            selection.value = 'No Selection';
        }

        return text;
    };

    function change() {
        let text = describe();

        parse();

        emit('change', props.state, text);
    };

    if ( ! props.initialText) {
        describe();
    }

    parse();

    onClickOutside(el, close);

</script>

<template>

    <div ref="el" class="
        relative w-full h-10 leading-10 rounded-md
        border border-gray-300 bg-slate-50 text-if-shark-900">

        <div @click="toggle()" class="w-full overflow-hidden truncate cursor-pointer px-2">
            {{ selection }}
        </div>

        <div v-if="active" class="
            absolute top-10 left-0 z-30 w-full
            rounded-md border border-gray-300 bg-slate-50">

            <div class="w-full">

                <div class="flex justify-between px-2">
                    <div @click="selectAll()" v-if="!allSelected" class="cursor-pointer">
                        {{ $gettext('Select All') }}
                    </div>
                    <div @click="selectNone()" v-if="someSelected" class="cursor-pointer">
                        {{ $gettext('Clear All') }}
                    </div>
                </div>

                <div class="px-2">

                    <div v-for="(v, i) in options" class="flex flex-col">

                        <label class="w-full flex items-center">

                            <input
                                @change="change()"
                                v-model="state[i]"
                                :value="v"
                                class="mr-2 styled"
                                type="checkbox"
                            /> {{ v }}

                        </label>

                    </div>

                </div>

            </div>

        </div>

    </div>

</template>
