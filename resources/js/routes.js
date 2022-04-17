import Home from './components/Home.vue'
import Login from './components/auth/Login.vue'

import Registrar from './components/registros/Registros.vue'
export const routes = [
    {
        path: '/',
        component: Home,
        meta: {
            requiresAuth: true
        },
    },
    {
        path: '/login',
        component: Login
    },
    {
        path: '/registrar',
        component:Registrar,
        meta: {
            requiresAuth: true
        },
    }
]