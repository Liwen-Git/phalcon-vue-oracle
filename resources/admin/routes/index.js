import Welcome from '../components/welcome';
import Login from '../components/login';
import Home from '../components/home';

import User from '../components/system-management/user/user';
import Role from '../components/system-management/role/role';
import Menu from '../components/system-management/menu/menu';
import OperateLog from '../components/system-management/operate-log/list';

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
            {path: 'role/index', component: Role, name: 'Role'},
            {path: 'menu/index', component: Menu, name: 'Menu'},
            {path: 'operate/index', component: OperateLog, name: 'OperateLog'},
        ]
    },
];
export default routes;
