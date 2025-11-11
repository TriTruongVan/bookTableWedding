import { createRouter, createWebHistory } from "vue-router";

const router = createRouter({
    history:createWebHistory(),
    routes : [
        {
            path: '/',
            redirect: '/dashboard'
        },
        {
            path: '/login',
            name: 'Login',
            component: () => import('../js/pages/Login.vue')
        },
        {
            path: '/dashboard',
            name: 'Dashboard',
            component: () => import('../js/pages/dashboard/Index.vue')
        },

    ]
})
export default router;