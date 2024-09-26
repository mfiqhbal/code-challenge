import { createRouter, createWebHistory } from 'vue-router';
import Login from '../views/Login.vue';
import Dashboard from '../views/Dashboard.vue';
import auth from "../middleware/auth";
import guest from "../middleware/guest";

const routes = [
    { 
        path: '/login', 
        name: 'Login', 
        component: Login, 
        meta: {
            middleware: [guest],
        }
    },
    { 
        path: '/dashboard', 
        name: 'Dashboard', 
        component: Dashboard, 
        meta: {
            middleware: [auth],
        }
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const middleware = to.meta.middleware;

    if (middleware) {
        // Execute each middleware in sequence
        const context = { next }; // Pass the context to middleware
        return middleware[0]({ ...context, next: (route) => {
            if (route) {
                return next(route);
            }
            return next();
        }});
    }
    next();
});

export default router;