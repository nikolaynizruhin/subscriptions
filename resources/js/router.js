import Vue from 'vue'
import Router from 'vue-router'
import NProgress from 'nprogress'
import Dashboard from './views/Dashboard'
import Login from './views/auth/Login'
import Register from './views/auth/Register'
import NotFound from './views/NotFound'
import Email from "./views/auth/passwords/Email";
import Reset from "./views/auth/passwords/Reset";

Vue.use(Router)

const router = new Router({
    mode: 'history',
    routes: [
        {
            path: '/login',
            name: 'login',
            component: Login,
            meta: {
                requiresGuest: true,
                title: 'Login'
            }
        },
        {
            path: '/register',
            name: 'register',
            component: Register,
            meta: {
                requiresGuest: true,
                title: 'Register'
            }
        },
        {
            path: '/password/reset/:token',
            name: 'password.reset',
            component: Reset,
            meta: {
                requiresGuest: true,
                title: 'Reset Password'
            }
        },
        {
            path: '/password/reset',
            name: 'password.request',
            component: Email,
            meta: {
                requiresGuest: true,
                title: 'Forgot Password'
            }
        },
        {
            path: '/',
            name: 'dashboard',
            component: Dashboard,
            meta: {
                layout: 'main',
                requiresAuth: true,
                title: 'Dashboard'
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
    document.title = to.meta.title ? `${to.meta.title} - ${app.name}` : app.name

    if (to.matched.some(record => record.meta.requiresGuest) && localStorage.token) {
        return next({ name: 'dashboard' })
    }

    if (!to.matched.some(record => record.meta.requiresAuth)) {
        return next()
    }

    if (!localStorage.token) {
        return next({ name: 'login', query: { redirect: to.fullPath }})
    }

    next()
})

// NProgress
router.beforeResolve((to, from, next) => {
    to.name && NProgress.start()

    next()
})

router.afterEach((to, from) => NProgress.done())

export default router
