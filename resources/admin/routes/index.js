import Welcome from '../components/welcome';
import Login from '../components/login';
import Home from '../components/home';

import User from '../components/system-management/user';

const routes = [
    {path: '/login', component: Login, name: 'Login'},

    {
        path: '/',
        component: Home,
        children: [
            {path: 'welcome', component: Welcome, name: 'Welcome'},
        ]
    },

    {
        path: '/',
        component: Home,
        children: [
            {path: 'user/index', component: User, name: 'User'},
        ]
    },
];
export default routes;
