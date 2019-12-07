import Vue from 'vue'
import NProgress from 'nprogress'
import PortalVue from 'portal-vue'
import App from './App'
import router from './router'
import store from './store'
import Auth from "./layouts/Auth"
import Default from "./layouts/Default"
import Main from "./layouts/Main"
import Icon from "./components/Icon";
import './utils/http'

Vue.use(PortalVue)

Vue.component('icon', Icon)
Vue.component('auth-layout', Auth)
Vue.component('default-layout', Default)
Vue.component('main-layout', Main)

NProgress.configure({ showSpinner: false })

new Vue({
    router,
    store,
    render: h => h(App)
}).$mount('#app')
