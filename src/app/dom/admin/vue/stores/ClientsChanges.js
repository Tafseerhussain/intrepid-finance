import { defineStore } from 'pinia';

export const useClientsChangesStore = () => {
    const store = defineStore('clients_changes', {
        state : () => {
            return {
                list    : [],
                loading : true,
            };
        },
        actions : {
            async fetch(id) {
                this.loading = true;

                await axios
                    .get('/admin/fetch/clients/' + id + '/changes?ajax=1')
                    .then((res) => {
                        this.list    = '[]' === res.data ? [] : res.data;
                        this.loading = false;
                    });
            },
        },
    })();

    return store;
};
