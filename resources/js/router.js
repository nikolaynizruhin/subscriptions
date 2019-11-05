import Vue from 'vue'
import Router from 'vue-router'
import NProgress from 'nprogress'
import store from './store'
import Dashboard from './views/Dashboard'
import Settings from './views/Settings'
import Login from './views/auth/Login'
import Register from './views/auth/Register'
import NotFound from './views/NotFound'
import Email from "./views/auth/passwords/Email";
import Reset from "./views/auth/passwords/Reset";
import Verify from "./components/Verification";

Vue.use(Router)

const router = new Router({
    mode: 'history',
    routes: [
        {
            path: '/login',
            name: 'login',
            component: Login,
            meta: {
                title: 'Login',
                requiresGuest: true
            }
        },
        {
            path: '/register',
            name: 'register',
            component: Register,
            meta: {
                title: 'Register',
                requiresGuest: true
            }
        },
        {
            path: '/password/reset/:token',
            name: 'password.reset',
            component: Reset,
            meta: {
                title: 'Reset Password',
                requiresGuest: true
            }
        },
        {
            path: '/password/reset',
            name: 'password.request',
            component: Email,
            meta: {
                title: 'Forgot Password',
                requiresGuest: true
            }
        },
        {
            path: '/',
            name: 'dashboard',
            component: Dashboard,
            meta: {
                title: 'Dashboard',
                layout: 'main',
                requiresAuth: true,
            }
        },
        {
            path: '/settings',
            name: 'settings',
            component: Settings,
            meta: {
                title: 'Settings',
                layout: 'main',
                requiresAuth: true,
                requiresVerification: true
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
    if (to.matched.some(record => record.meta.requiresGuest) && localStorage.token) {
        return next({ name: 'dashboard' })
    }

    if (!to.matched.some(record => record.meta.requiresAuth)) {
        return next()
    }

    if (to.matched.some(record => record.meta.requiresVerification) && !store.state.user.email_verified_at) {
        return next({ name: 'dashboard' })
    }

    if (!localStorage.token) {
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
