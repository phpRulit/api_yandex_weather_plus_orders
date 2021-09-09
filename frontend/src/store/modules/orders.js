import axios from 'axios'

export default {
    namespaced: true,
    state: {
        orders: null,
        order: null,
    },
    getters: {
        orders: state => state.orders,
        order: state => state.order,
    },
    mutations: {
        setOrders (state, data) {
            state.orders = data
        },
        setOrder (state, data) {
            state.order = data
        },
    },
    actions: {
        getOrdersByParams({commit}, details) {
            return axios
                .get('/orders/get-list', {params: {page: details.page, status: details.status, max: details.max}})
                .then(({data}) => {
                    commit('setOrders', data);
                })
        },
        getOrderById({commit}, order_id) {
            return axios
                .get('/orders/get-order/' + order_id)
                .then(({data}) => {
                    commit('setOrder', data);
                })
        },
        getEditOrder({commit}, [order_id, details]) {
            commit('setError', null, {root: true});
            commit('setSuccess', null, {root: true});
            return axios
                .post('/' + order_id, details)
                .then(({data}) => {
                    if (data.errorMsg) {
                        commit('setError', data.errorMsg, {root: true});
                    } else if (data.successMsg) {
                        commit('setSuccess', data.successMsg, {root: true});
                    }
                })
        }
    }
}