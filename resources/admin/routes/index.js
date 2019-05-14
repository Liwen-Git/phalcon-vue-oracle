import Welcome from '../components/welcome';
import Login from '../components/login';
import Home from '../components/home';

import User from '../components/system-management/user/user';
import Role from '../components/system-management/role/role';
import Menu from '../components/system-management/menu/menu';
import OperateLog from '../components/system-management/operate-log/list';

import AccountingSubjectList from '../components/basic-info/accounting-subject/list';

const routes = [
    {path: '/login', component: Login, name: 'Login'},

    {
        path: '/',
        component: Home,
        children: [
            {path: '/welcome', component: Welcome, name: 'Welcome'},
        ]
    },

    {
        path: '/',
        component: Home,
        children: [
            {path: '/user/index', component: User, name: '用户管理'},
            {path: '/role/index', component: Role, name: '角色管理'},
            {path: '/menu/index', component: Menu, name: '菜单管理'},
            {path: '/operate/index', component: OperateLog, name: '操作日志'},
        ]
    },

    {
        path: '/',
        component: Home,
        children: [
            {path: '/subject/index', component: AccountingSubjectList, name: '会计科目'},
        ]
    },
];
export default routes;
