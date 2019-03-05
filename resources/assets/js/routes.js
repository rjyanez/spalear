import Home from './views/home.vue';
import Login from './views/auth/login.vue';
import Signup from './views/auth/signup.vue';
import Dashboard from './views/dashboard';
import UserList from './views/user/list';
import User from './views/user/user';


export const routes = [
    {
        path: '/',
        name: 'home',
        component: Home
    },
    {
        path: '/login',
        name: 'login',
        component: Login
    },
    {
        path: '/signup',
        name: 'signup',
        component: Signup
    },
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/porfile',
        name: 'porfile',
        component: User,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/user',
        component: {
            template: '<router-view/>',
        },
        meta: {
            requiresAuth: true,
            deniedRoles: ['TE','ST']
        },
        children: [
            {
                path: '/',
                name: 'user.list',
                component: UserList
            },
            {
                path: 'create',
                name: 'user.new',
                component: User
            },
            {
                path: ':id',
                component: User
            }
        ]
    },
    {
        path: '/account',
        name: 'account',
        redirect: '/porfile',
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/teachers',
        name: 'teachers',
        component: {
            template: '<router-view/>',
        },
    },
    {
        path: '/lessons',
        name: 'lessons',
        component: {
            template: '<router-view/>',
        },
        meta: {
            requiresAuth: true
        }
    }
];