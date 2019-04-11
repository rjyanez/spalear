import Errors         from './views/error.vue'
import Home           from './views/home.vue'
import Login          from './views/auth/login.vue'
import Signup         from './views/auth/signup.vue'
import Dashboard      from './views/dashboard'
import Meeting        from './views/meeting/meeting'
import UserList       from './views/user/list'
import User           from './views/user/user'
import TeacherList    from './views/teacher/list'
import TeacherProfile from './views/teacher/profile'
import Lessons        from './views/lesson/lesson'

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
        meta: {
            requiresAuth: true
        },
        component:  {
            template: '<router-view/>',
        },
        children: [
            {
                path: '/',
                name: 'dashboard',
                component: Dashboard
            },
            {
                path: ':id',
                component: Dashboard,
                meta: {
                    allowedRoles: ['AD','SC']
                },
            }
        ]
    },
    {
        path: '/user',
        component: {
            template: '<router-view/>',
        },
        meta: {
            requiresAuth: true,
        },
        children: [
            {
                path: '/',
                name: 'user.list',
                component: UserList,
                meta: {
                    allowedRoles: ['AD','SC']
                },
            },
            {
                path: 'create',
                name: 'user.new',
                component: User,
                meta: {
                    allowedRoles: ['AD','SC']
                },
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
                        component: User,
                        meta: {
                            allowedRoles: ['AD','SC']
                        },
                    }
                ]
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
            allowedRoles: ['ST']
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
        redirect: '/lessons/basic', 
        component: {
            template: '<router-view/>',
        },
        meta: {
            requiresAuth: true,
            allowedRoles: ['ST']
        },
        children: [
            {
                path: ':code',
                name: 'lessons',
                component: Lessons 
            }
        ]
    },
    {
        path: '/error/:code',
        name: 'error',
        component: Errors
    },
    {   
        path: '*', 
        redirect: '/error/404' 
    }, 
];