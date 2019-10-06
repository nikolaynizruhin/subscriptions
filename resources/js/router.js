import Vue from 'vue'
import Router from 'vue-router'
import NProgress from 'nprogress'
import Index from './views/invoices/Index'
import Login from './views/Login.vue'
import NotFound from './views/NotFound.vue'

Vue.use(Router)

const router = new Router({
    mode: 'history',
    routes: [
        {
            path: '/login',
            name: 'login',
            component: Login,
            meta: {
                guestOnly: true
            }
        },
        {
            path: '/invoices',
            name: 'invoices.index',
            component: Index,
            meta: {
                layout: 'main',
                requiresAuth: true
            }
        },
        {
            path: '*',
            name: 'not-found',
            component: NotFound
        }
    ]
})

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.guestOnly) && localStorage.getItem('token')) {
        return next({ name: 'invoices.index' })
    }

    if (!to.matched.some(record => record.meta.requiresAuth)) {
        return next()
    }

    if (!localStorage.getItem('token')) {
        return next({ name: 'login', query: { redirect: to.fullPath }})
    }

    next()
})

// NProgress
router.beforeResolve((to, from, next) => {
    if (to.name) {
        NProgress.start()
    }

    next()
})

router.afterEach((to, from) => NProgress.done())

export default router
