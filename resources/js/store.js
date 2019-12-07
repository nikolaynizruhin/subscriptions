import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        user: {},
        flash: { message: null, type: null },
        paymentMethods: []
    },
    mutations: {
        setUser (state, user) {
            state.user = user
        },
        setFlash (state, { message, type = 'success' }) {
            state.flash.message = message
            state.flash.type = type
        },
        clearFlash (state) {
            state.flash.message = null
            state.flash.type = null
        },
        login (state, { token, user }) {
            localStorage.token = token

            state.user = user
        },
        logout (state) {
            delete localStorage.token

            state.user = {}
        },
        setPaymentMethods (state, paymentMethods) {
            state.paymentMethods = paymentMethods
        },
        removePaymentMethod (state, paymentMethod) {
            state.paymentMethods = state.paymentMethods.filter(payment => payment.id !== paymentMethod.id)
        },
        addPaymentMethod (state, paymentMethod) {
            state.paymentMethods.push(paymentMethod)
        }
    },
    actions: {
        async getUser ({ commit }) {
            const { data } = await axios.get('/api/user')

            commit('setUser', data)
        },
        async addPaymentMethod ({ commit }, paymentMethod) {
            const { data } = await axios.post('/api/customer-payment-method', { payment_method: paymentMethod })

            commit('addPaymentMethod', data)
        },
        async getPaymentMethods ({ commit }) {
            const { data } = await axios.get('/api/payment-methods')

            commit('setPaymentMethods', data)
        },
        async removePaymentMethod ({ commit }, paymentMethod) {
            await axios.delete(`/api/payment-methods/${paymentMethod.id}`)

            commit('removePaymentMethod', paymentMethod)
        },
    },
    getters: {
        isAuth: state => !!Object.entries(state.user).length
    }
})
