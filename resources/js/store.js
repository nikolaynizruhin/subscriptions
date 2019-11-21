import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        user: {},
        flash: {
            message: null,
            type: null
        }
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
        }
    },
    actions: {
        async getUser ({ commit }) {
            const { data } = await axios.get('/api/user')

            commit('setUser', data)
        }
    },
    getters: {
        isAuth: state => Object.entries(state.user).length !== 0
    }
})
