import Example from '../components/example';

import Login from '../components/login';
import Home from '../components/home';

const routes = [
    {path: '/login', component: Login, name: 'Login'},

    {
        path: '/',
        component: Home,
        children: [
            {path: 'example', component: Example, name: 'Example'},
        ]
    }
];
export default routes;
