import Vue from 'vue'
import NProgress from 'nprogress'
import App from './App.vue'
import router from './router'
import store from './store'
import Default from "./layouts/Default.vue"
import Main from "./layouts/Main.vue"

import './utils/http'

Vue.component('default-layout', Default)
Vue.component('main-layout', Main)

NProgress.configure({ showSpinner: false })

new Vue({
    router,
    store,
    render: h => h(App)
}).$mount('#app')
