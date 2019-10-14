import axios from 'axios'
import router from "../router"
import store from "../store"

window.axios = axios

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

// Request interceptor
axios.interceptors.request.use(config => {
    const token = localStorage.token

    if (token) {
        config.headers['Authorization'] = `Bearer ${token}`
    }

    return config;
}, error => Promise.reject(error));

// Response interceptor
axios.interceptors.response.use(response => response, error => {
    if (error.response.status === 401) {
        delete localStorage.token
        return router.push('/login')
    }

    if (error.response.status >= 500) {
        const message = error.response ? error.response.data.message : error.message
        store.commit('setFlash',  { message, type: 'error' })
    }

    return Promise.reject(error)
})
