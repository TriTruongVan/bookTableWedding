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
            component: () => import('../js/pages/Login.vue'),
            meta: { requiresGuest: true }
        },
        {
            path: '/dashboard',
            name: 'Dashboard',
            component: () => import('../js/pages/dashboard/Index.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: '/customer',
            name: 'Customer',
            component: () => import('../js/pages/customer/Index.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: '/customer/create',
            name: 'CustomerCreate',
            component: () => import('../js/pages/customer/Create.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: '/customer/:id',
            name: 'CustomerDetail',
            component: () => import('../js/pages/customer/Detail.vue'),
            meta: { requiresAuth: true }
        },
    ]
})
export default router;