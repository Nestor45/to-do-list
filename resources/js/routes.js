import Home from './components/Home.vue'
import Login from './components/auth/Login.vue'

export const routes = [
    {
        path: '/login',
        component: Login
    },
    {
        path: '/',
        component: Home,
        meta: {
            requiresAuth: true
        },
    }
]