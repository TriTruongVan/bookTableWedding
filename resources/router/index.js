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
            path: '/profile',
            name: 'Profile',
            component: () => import('../js/pages/Profile.vue'),
            meta: { requiresAuth: true }
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
        {
            path: '/voucher',
            name: 'Voucher',
            component: () => import('../js/pages/voucher/Index.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: '/voucher/create',
            name: 'VoucherCreate',
            component: () => import('../js/pages/voucher/Create.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: '/voucher/:id',
            name: 'VoucherDetail',
            component: () => import('../js/pages/voucher/Detail.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: '/staff',
            name: 'Staff',
            component: () => import('../js/pages/staff/Index.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: '/staff/create',
            name: 'StaffCreate',
            component: () => import('../js/pages/staff/Create.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: '/staff/:id',
            name: 'StaffDetail',
            component: () => import('../js/pages/staff/Detail.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: '/dish',
            name: 'Dish',
            component: () => import('../js/pages/dish/Index.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: '/dish/:id',
            name: 'dishDetail',
            component: () => import('../js/pages/dish/Detail.vue'),
            meta: { requiresAuth: true }
        },
    ]
})
export default router;