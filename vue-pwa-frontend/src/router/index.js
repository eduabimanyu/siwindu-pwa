import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const routes = [
    {
        path: '/login',
        name: 'Login',
        component: () => import('@/views/Login.vue'),
        meta: { requiresAuth: false }
    },
    {
        path: '/',
        redirect: '/dashboard'
    },
    {
        path: '/dashboard',
        name: 'Dashboard',
        component: () => import('@/views/Dashboard.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/pos',
        name: 'POS',
        component: () => import('@/views/POS.vue'),
        meta: {
            requiresAuth: true,
            allowedRoles: ['Kasir']
        }
    },
    {
        path: '/shift',
        name: 'Shift',
        component: () => import('@/views/Shift.vue'),
        meta: {
            requiresAuth: true,
            allowedRoles: ['Kasir']
        }
    },
    {
        path: '/transactions',
        name: 'Transactions',
        component: () => import('@/views/Transactions.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/receipt/:id',
        name: 'ReceiptPrint',
        component: () => import('@/views/ReceiptPrint.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/shift-print/:id',
        name: 'ShiftPrint',
        component: () => import('@/views/ShiftPrint.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/reports',
        name: 'Reports',
        component: () => import('@/views/Reports.vue'),
        meta: {
            requiresAuth: true,
            allowedRoles: ['Admin', 'Manager', 'Keuangan']
        }
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

// Navigation guard
router.beforeEach((to, from, next) => {
    const authStore = useAuthStore();

    if (to.meta.requiresAuth && !authStore.isAuthenticated) {
        // Redirect to login if not authenticated
        next('/login');
    } else if (to.path === '/login' && authStore.isAuthenticated) {
        // Redirect to dashboard if already logged in
        next('/dashboard');
    } else if (to.meta.allowedRoles) {
        // Check if user has required role
        if (authStore.hasRole(to.meta.allowedRoles)) {
            next();
        } else {
            // Redirect to dashboard if user doesn't have required role
            alert('Anda tidak memiliki akses ke halaman ini');
            next('/dashboard');
        }
    } else {
        next();
    }
});

export default router;
