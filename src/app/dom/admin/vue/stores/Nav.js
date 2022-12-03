import { defineStore } from 'pinia';

export const useNavStore = () => {
    const store = defineStore('nav', {
        state : () => {
            return {
                active : 'Dashboard',
                shrunk : localStorage.getItem('navShrunk') === '1',
                list   : [],
            }
        },
        actions : {
            async fetchNav() {
                const list = await axios.get('/admin/fetch/nav');
                this.list  = list.data;
            },
            toggleShrunk() {
                this.shrunk = ! this.shrunk;
                localStorage.setItem('navShrunk', this.shrunk ? '1' : '0');
            },
        },
    })();

    if ( ! Object.keys(store.list).length) {
        store.fetchNav();
    }

    return store;
};
