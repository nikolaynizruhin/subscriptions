import axios from 'axios'
import router from "../router"

window.axios = axios

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

// Request interceptor
axios.interceptors.request.use(config => {
    const token = localStorage.getItem('token')

    if (token) {
        config.headers['Authorization'] = `Bearer ${token}`
    }

    return config;
}, error => Promise.reject(error));

// Response interceptor
axios.interceptors.response.use(response => response, error => {
    if (error.response.status === 401) {
        localStorage.removeItem('token')
        return router.push('/login')
    }

    return Promise.reject(error)
})
