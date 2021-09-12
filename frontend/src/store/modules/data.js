import axios from 'axios'

export default {
    namespaced: true,
    state: {
        partners: null,
        products: null,
    },
    getters: {
        partners: state => state.partners,
        products: state => state.products,
    },
    mutations: {
        setPartners (state, data) {
            state.partners = data
        },
        setProducts (state, data) {
            state.products = data
        },
    },
    actions: {
        getPartnersList({commit}) {
            return axios
                .get('/data/get-partners-list')
                .then(({data}) => {
                    commit('setPartners', data);
                })
        },
        getProductsList({commit}) {
            return axios
                .get('/data/get-products-list')
                .then(({data}) => {
                    commit('setProducts', data);
                })
        },
    }
}