import Welcome from '../components/welcome';

import Login from '../components/login';
import Home from '../components/home';

const routes = [
    {path: '/login', component: Login, name: 'Login'},

    {
        path: '/',
        component: Home,
        children: [
            {path: 'welcome', component: Welcome, name: 'Welcome'},
        ]
    }
];
export default routes;
