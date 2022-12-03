<script setup>

    import { onMounted, ref } from 'vue';
    import axios from 'axios';
    import * as widgetSdk from '@mxenabled/web-widget-sdk';

    const noticeError = ref('');

    const noticeSuccess = ref('');

    const widgetNeeded = ref(false);

    function initConnectWidget(widgetUrl) {
        new widgetSdk.ConnectWidget({
            container         : '#mx-connect',
            url               : widgetUrl,
            onMemberConnected : (e) => {
                const params = {
                    user_guid    : e.user_guid,
                    session_guid : e.session_guid,
                    member_guid  : e.member_guid,
                };

                axios
                    .post('/clients/mx/member-connected', params)
                    .then((res) => {
                        console.log('successfully connected and saved', res);
                    }).catch((error) => {
                        console.log('connected but failed to save', error);
                    });
            },
        });
    };

    onMounted(() => {

        axios.get('/clients/fetch/mx/widget-url').then(res => {
            if (res.data.error) {
                noticeError.value = error;
            } else if (res.data.widget_needed && res.data.widget_url) {
                initConnectWidget(res.data.widget_url);
            }
        });

    });

</script>

<template>

    <WidgetError v-model:message="noticeError" />

    <WidgetSuccess v-model:message="noticeSuccess" />

    <div id="mx-connect"></div>

</template>
