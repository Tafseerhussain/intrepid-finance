<script setup>

    import { ref, watch } from 'vue';
    import { useProfileStore } from '../stores/Profile.js';
    import { useGettext } from 'vue3-gettext';

    const { $gettext } = useGettext();

    const props = defineProps(['active']);

    const emit = defineEmits(['update:active']);

    const profile = useProfileStore();

    const active = ref(props.active);

    const noticeError = ref('');

    const noticeSuccess = ref('');

    function closeModal() {
        active.value = false;
        emit('update:active', false);
    };

    async function mxDisconnect() {
        await axios
            .post('/clients/mx/disconnect')
            .then((res) => {
                closeModal();
                noticeSuccess.value = $gettext('Financial institution disconnected successfully!');
            }).catch((error) => {
                closeModal();
                noticeError.value = Object.values(error.response.data.errors)[0];
            });
    };

    watch(() => props.active, (newVal) => {
        active.value = newVal ? true : false;
    });

</script>

<template>

    <WidgetModal
        @close="closeModal()"
        :title="$gettext('Disconnect Financial Institution?')"
        :active="active">
        <template #content>
            {{ $gettext(`Are you sure you want to disconnect your financial institution from your Intrepid account? Not having your financial institution connected may affect Intrepid's ability to process your request for capital.`) }}
        </template>
        <template #actions>
            <div class="flex w-full justify-end">
                <button @click="mxDisconnect()" class="generic h-10 px-3 mr-3">
                    {{ $gettext('Disconnect') }}
                </button>
                <button @click="closeModal()" class="plain h-10 px-3">
                    {{ $gettext('Cancel') }}
                </button>
            </div>
        </template>
    </WidgetModal>

    <WidgetError v-model:message="noticeError" />

    <WidgetSuccess v-model:message="noticeSuccess" />

</template>
