import { defineStore } from 'pinia';

export const useAdminStore = () => {
    const store = defineStore('admin', {
        state : () => {
            return {
                id           : null,
                first_name   : null,
                last_name    : null,
                email        : null,
                access_level : null,
                status       : null,
                gravatar     : null,
                loading      : true,
            };
        },
        actions : {
            async fetch() {
                this.loading = true;

                await axios.get('/admin/fetch/admin').then((res) => {
                    this.first_name   = res.data.first_name;
                    this.last_name    = res.data.last_name;
                    this.email        = res.data.email;
                    this.access_level = parseInt(res.data.access_level);
                    this.status       = res.data.status;
                    this.gravatar     = res.data.gravatar;
                    this.loading      = false;
                });
            },
        },
    })();

    if ( ! store.id) {
        store.fetch();
    }

    return store;
};
