import { defineStore } from 'pinia';

export const useProfileStore = () => {
    const store = defineStore('profile', {
        state : () => {
            return {
                reference_number : null,
                company_name     : null,
                first_name       : null,
                last_name        : null,
                email            : null,
                status           : null,
                gravatar         : null,
                mx_needs_widget  : false,
                loading          : false,
            };
        },
        actions : {
            async fetch() {
                this.loading = true;

                await axios.get('/clients/fetch/profile').then((res) => {
                    this.reference_number = res.data.reference_number;
                    this.company_name     = res.data.company_name;
                    this.first_name       = res.data.first_name;
                    this.last_name        = res.data.last_name;
                    this.email            = res.data.email;
                    this.status           = res.data.status;
                    this.gravatar         = res.data.gravatar;
                    this.mx_needs_widget  = 'Y' === res.data.mx_needs_widget;
                    this.loading          = false;
                });
            },
        },
    })();

    if ( ! store.reference_number && ! store.loading) {
        store.fetch();
    }

    return store;
};
