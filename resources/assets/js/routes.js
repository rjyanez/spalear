import Errors from './views/error'
import Home from './views/home'
import Login from './views/auth/login'
import PasswordForgot from './views/auth/password/forgot'
import PasswordReset from './views/auth/password/reset'
import Signup from './views/auth/signup'
import Dashboard from './views/dashboard'
import Meeting from './views/meeting/meeting'
import UserList from './views/user/list'
import User from './views/user/user'
import TeacherList from './views/teacher/list'
import TeacherProfile from './views/teacher/profile'
import StudentList from './views/student/list'
import StudentProfile from './views/student/profile'
import Lessons from './views/lesson/lesson'

export const routes = [{
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
        path: '/password',
        name: 'password',
        component: {
            template: '<router-view/>',
        },
        children: [{
                path: 'forgot',
                name: 'password.forgot',
                component: PasswordForgot
            },
            {
                path: 'reset/:token',
                name: 'password.reset',
                component: PasswordReset,
            }
        ]
    },
    {
        path: '/dashboard',
        meta: {
            requiresAuth: true
        },
        component: {
            template: '<router-view/>',
        },
        children: [{
                path: '/',
                name: 'dashboard',
                component: Dashboard
            },
            {
                path: ':id',
                component: Dashboard,
                meta: {
                    allowedRoles: ['AD', 'SC']
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
        children: [{
                path: '/',
                name: 'user.list',
                component: UserList,
                meta: {
                    allowedRoles: ['AD', 'SC']
                },
            },
            {
                path: 'create',
                name: 'user.new',
                component: User,
                meta: {
                    allowedRoles: ['AD', 'SC']
                },
            },
            {
                path: 'password',
                name: 'user.password',
                component: PasswordReset,
            },
            {
                path: 'setting',
                component: {
                    template: '<router-view/>',
                },
                children: [{
                        path: '/',
                        name: 'user.setting',
                        component: User
                    },
                    {
                        path: ':id',
                        component: User,
                        meta: {
                            allowedRoles: ['AD', 'SC']
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
        path: '/teacher',
        name: 'teacher',
        redirect: '/teacher/all',
        component: {
            template: '<router-view/>',
        },
        meta: {
            requiresAuth: true,
            allowedRoles: ['ST']
        },
        children: [{
                path: 'all',
                name: 'teacher.all',
                component: TeacherList
            },
            {
                path: 'favorite',
                name: 'teacher.favorite',
                component: TeacherList
            },
            {
                path: ':id',
                name: 'teacher.name',
                component: TeacherProfile
            }
        ]
    },
    {
        path: '/student',
        name: 'student',
        redirect: '/student/favorite',
        component: {
            template: '<router-view/>',
        },
        meta: {
            requiresAuth: true,
            allowedRoles: ['TE', 'SC', 'AD']
        },
        children: [
            {
                path: 'favorite',
                name: 'student.favorite',
                meta: {
                    requiresAuth: true
                },
                component: {
                    template: '<router-view/>',
                },
                children: [
                    {
                        path: '/',
                        component: StudentList
                    },
                    {
                        path: ':id',
                        component: StudentList,
                        meta: {
                            allowedRoles: ['AD', 'SC']
                        },
                    }
                ]
            },
            {
                path: ':id',
                name: 'student.profile',
                component: StudentProfile
            }          
        ]
    },
    {
        path: 'meeting',
        name: 'meeting',
        component: {
            template: '<router-view/>',
        },
        children: [{
            path: ':id',
            name: 'meeting.show',
            component: Meeting
        }]
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
        children: [{
            path: ':code',
            name: 'lessons',
            component: Lessons
        }]
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