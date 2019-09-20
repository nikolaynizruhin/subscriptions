import Vue from 'vue'
import router from './router'
import store from './store'

require('./bootstrap')

new Vue({
    router,
    store,
    el: '#app',
})
