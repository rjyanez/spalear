import Home from './views/home.vue';
import Login from './views/auth/login.vue';
import Signup from './views/auth/signup.vue';
import Dashboard from './views/dashboard';
import UserList from './views/user/list';
import User from './views/user/user';
import Porfile from './views/user/porfile';
import TeacherList from './views/teacher/list'
import Teacher from './views/teacher/teacher'

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
        path: '/user',
        component: {
            template: '<router-view/>',
        },
        meta: {
            requiresAuth: true,
            // deniedRoles: ['TE','ST']
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
                path: 'setting',
                name: 'user.setting',
                component: User
            },
            {
                path: 'porfile',
                component: {
                    template: '<router-view/>',
                },                
                children: [
                    {
                        path: '/',
                        name: 'user.porfile',
                        component: Porfile
                    },                
                    {
                        path: ':id',
                        component: Porfile
                    },
                ]
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
        redirect: '/user/setting',
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/teachers',
        name: 'teachers',
        redirect: '/teachers/all',
        component: {
            template: '<router-view/>',
        },
        meta: {
            requiresAuth: true,
        },
        children: [
            {
                path: 'all',
                name: 'teachers.all',
                component: TeacherList
            },
            {
                path: ':id',
                name: 'teachers.name',
                component: Teacher
            }
        ]
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