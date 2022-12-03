import { defineStore } from 'pinia';

export const useClientsNotesStore = () => {
    const store = defineStore('clients_notes', {
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
                    .get('/admin/fetch/clients/' + id + '/notes?ajax=1')
                    .then((res) => {
                        this.list    = '[]' === res.data ? [] : res.data;
                        this.loading = false;
                    });
            },
        },
    })();

    return store;
};
