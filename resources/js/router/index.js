import Vue from 'vue'
import Router from 'vue-router'
import NProgress from 'nprogress'
import store from '../store'
import routes from "../router/routes"

Vue.use(Router)

const router = new Router({ mode: 'history', routes })

router.beforeEach(async (to, from, next) => {
    if (localStorage.token && !store.getters.isAuth) {
        await store.dispatch('getUser')
    }

    if (to.matched.some(record => record.meta.requiresGuest) && store.getters.isAuth) {
        return next({ name: 'dashboard' })
    }

    if (!to.matched.some(record => record.meta.requiresAuth)) {
        return next()
    }

    if (to.matched.some(record => record.meta.requiresVerify) && !store.state.user.email_verified_at) {
        return next({ name: 'dashboard' })
    }

    if (to.matched.some(record => record.meta.requiresPasswordConfirm) && store.state.user.should_confirm_password) {
        return next({ name: 'password.confirm', query: { redirect: to.fullPath } })
    }

    if (!store.getters.isAuth) {
        return next({ name: 'login', query: { redirect: to.fullPath }})
    }

    next()
})

router.afterEach((to, from) => document.title = to.meta.title ? `${to.meta.title} - ${app.name}` : app.name)

// NProgress
router.beforeResolve((to, from, next) => {
    to.name && NProgress.start()

    next()
})

router.afterEach((to, from) => NProgress.done())

export default router
