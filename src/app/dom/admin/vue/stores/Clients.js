import { defineStore } from 'pinia';

export const useClientsStore = () => {
    const store = defineStore('clients', {
        state : () => {
            return {
                list    : [],
                pages   : [1],
                loading : true,
                text    : 'Page 1 of 1',
                query   : {
                    page      : 1,
                    per_page  : 50,
                    sort_by   : 'created',
                    sort_type : 'desc',
                    form_type : '',
                    status    : '',
                    statuses  : {
                        'Abandoned'  : true,
                        'Prospect'   : true,
                        'Started'    : true,
                        'Completed'  : true,
                        'Working'    : true,
                        'Outsourced' : true,
                        'Funded'     : true,
                        'Declined'   : true,
                        'Archive'    : true,
                        'Inactive'   : true,
                    },
                    search    : null,
                },
            };
        },
        actions : {
            async fetch() {
                this.loading = true;

                await axios
                    .get('/admin/fetch/clients', { params : this.query })
                    .then((res) => {
                        this.list    = res.data.list;
                        this.pages   = res.data.pages;
                        this.text    = res.data.text;
                        this.loading = false;
                    });
            },
            prefetch() {
                if (this.timeout) {
                    clearTimeout(this.timeout);
                }

                this.timeout = setTimeout(() => {
                    this.fetch();
                }, 500);
            },
            setPage(page) {
                this.query.page = parseInt(page);
                this.fetch();
            },
            sortBy(field) {
                if (field === this.query.sort_by) {
                    this.query.sort_type = 'asc' === this.query.sort_type ? 'desc' : 'asc';
                } else {
                    this.query.sort_by   = field;
                    this.query.sort_type = 'asc';
                }

                this.fetch();
            },
            sortIcon(field) {
                if (field !== this.query.sort_by) {
                    return 'fa-solid fa-sort';
                }

                if ('asc' === this.query.sort_type) {
                    return 'fa-solid fa-sort-down';
                }

                return 'fa-solid fa-sort-up';
            },
        },
    })();

    if ( ! Object.keys(store.list).length) {
        store.fetch();
    }

    return store;
};
