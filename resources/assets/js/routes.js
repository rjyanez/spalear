import Home from './views/home.vue';
import Login from './views/auth/login.vue';
import Signup from './views/auth/signup.vue';
import Dashboard from './views/dashboard';
import Meeting from './views/meeting/meeting'
import UserList from './views/user/list';
import User from './views/user/user';
import UserProgress from './views/user/progress.vue'
import TeacherList from './views/teacher/list'
import TeacherProfile from './views/teacher/profile'

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
                component:  {
                    template: '<router-view/>',
                },
                children: [
                    {
                        path: '/',
                        name: 'user.setting',
                        component: User
                    },
                    {
                        path: ':id',
                        component: User
                    }
                ]
            },
            {
                path: 'progress',
                component:  {
                    template: '<router-view/>',
                },
                children: [
                    {
                        path: '/',
                        name: 'user.progress',
                        component: UserProgress
                    },
                    {
                        path: ':id',
                        component: UserProgress
                    }
                ]
            },
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
                path: 'favorite',
                name: 'teachers.favorite',
                component: TeacherList
            },
            {
                path: ':id',
                name: 'teachers.name',
                component: TeacherProfile 
            }
        ]
    },
    {
        path: 'meeting',
        name: 'meeting',
        component: {
            template: '<router-view/>',
        },
        children: [
            {
                path: ':id',
                name: 'meeting.show',
                component: Meeting 
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