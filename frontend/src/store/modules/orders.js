import axios from 'axios'

export default {
    namespaced: true,
    state: {
        orders: null,
        order: null,
        order_product: null,
    },
    getters: {
        orders: state => state.orders,
        order: state => state.order,
        order_product: state => state.order_product,
    },
    mutations: {
        setOrders (state, data) {
            state.orders = data
        },
        setOrder (state, data) {
            state.order = data
        },
        setOrderProduct (state, data) {
            state.order_product = data
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
        setNewPartner({commit}, details) {
            commit('setMessageError', null, {root: true});
            commit('setMessageSuccess', null, {root: true});
            const formData = new FormData();
            formData.append('partner_id', details.partner_id);
            return axios
                .post('/orders/set-new-partner/' + details.order_id, formData)
                .then(({data}) => {
                    if (data.messageError) {
                        commit('setMessageError', data.messageError, {root: true});
                    } else if (data.message) {
                        commit('setMessageSuccess', data.message, {root: true});
                        commit("setErrors", [], {root: true});
                    }
                })
        },
        setChangesOrder({commit}, details) {
            commit('setMessageError', null, {root: true});
            commit('setMessageSuccess', null, {root: true});
            const formData = new FormData();
            details.status !== null ? formData.append('status', details.status) : null;
            details.client_email !== null ? formData.append('client_email', details.client_email) : null;
            details.delivery_dt !== null ? formData.append('delivery_dt', details.delivery_dt) : null;
            return axios
                .post('/orders/edit-order/' + details.order_id, formData)
                .then(({data}) => {
                    if (data.messageError) {
                        commit('setMessageError', data.messageError, {root: true});
                    } else if (data.message) {
                        commit('setMessageSuccess', data.message, {root: true});
                        commit("setErrors", [], {root: true});
                    }
                })
        },
        async sentMailOrderCompleted({commit}, order_id) {
            return axios.post('/orders/mails-order-completed/' + order_id)
        },
        setItemInOrder({commit}, details) {
            commit('setMessageError', null, {root: true});
            commit('setMessageSuccess', null, {root: true});
            const formData = new FormData();
            formData.append('quantity', details.quantity);
            return axios
                .post('/orders/add-item-in-order/' + details.order_id + '/' + details.product_id, formData)
                .then(({data}) => {
                    if (data.messageError) {
                        commit('setMessageError', data.messageError, {root: true});
                    } else if (data.message) {
                        commit('setOrderProduct', data.order_product);
                        commit('setMessageSuccess', data.message, {root: true});
                        commit("setErrors", [], {root: true});
                    }
                })
        },
        editQuantityItemInOrder({commit}, details) {
            commit('setMessageError', null, {root: true});
            commit('setMessageSuccess', null, {root: true});
            const formData = new FormData();
            formData.append('quantity', details.quantity);
            return axios
                .post('/orders/edit-quantity-item-order/' + details.order_id + '/' + details.order_product_id, formData)
                .then(({data}) => {
                    if (data.messageError) {
                        commit('setMessageError', data.messageError, {root: true});
                    } else if (data.message) {
                        commit('setMessageSuccess', data.message, {root: true});
                        commit("setErrors", [], {root: true});
                    }
                })
        },
        destroyItemOrder({commit}, details) {
            commit('setMessageError', null, {root: true});
            commit('setMessageSuccess', null, {root: true});
            return axios
                .delete('/orders/destroy-item-order/' + details.order_id + '/' + details.order_product_id)
                .then(({data}) => {
                    if (data.messageError) {
                        commit('setMessageError', data.messageError, {root: true});
                    } else if (data.message) {
                        commit('setMessageSuccess', data.message, {root: true});
                    }
                })
        }
    }
}