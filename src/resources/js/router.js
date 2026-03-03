import { createRouter, createWebHistory } from 'vue-router'

import ProxiesPage from './pages/ProxiesPage.vue'
import BotsPage from './pages/BotsPage.vue'
import HomePage from './pages/HomePage.vue'
import ToolsPage from './pages/ToolsPage.vue'
import LoginPage from './pages/LoginPage.vue'
import RegistrationPage from './pages/RegistrationPage.vue'

import { user, fetchUser } from './auth.js'


const routes = [
    { path: '/', component: HomePage, meta: { requiresAuth: true } },
    { path: '/proxies', component: ProxiesPage, meta: { requiresAuth: true } },
    { path: '/bots', component: BotsPage, meta: { requiresAuth: true } },
    { path: '/tools', component: ToolsPage, meta: { requiresAuth: true } },
    { path: '/login', component: LoginPage, meta: { guest: true } },
    { path: '/registration', component: RegistrationPage, meta: { guest: true } },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

router.beforeEach(async (to, from, next) => {
    if (user.value === null) {
        try {
            await fetchUser()
        } catch { }
    }

    if (to.meta.requiresAuth && !user.value) {
        return next('/login')
    }

    if (to.meta.guest && user.value) {
        return next('/')
    }

    next()
})

export default router