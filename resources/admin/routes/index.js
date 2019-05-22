import Welcome from '../components/welcome';
import Login from '../components/login';
import Home from '../components/home';

import User from '../components/system-management/user/user';
import Role from '../components/system-management/role/role';
import Menu from '../components/system-management/menu/menu';
import OperateLog from '../components/system-management/operate-log/list';

import AccountingSubjectList from '../components/basic-info/accounting-subject/list';
import MerchantList from '../components/basic-info/merchant/list';
import AgentList from '../components/basic-info/agent/list';
import ChannelList from '../components/basic-info/channel/list';
import BusinessTypeList from '../components/basic-info/business-type/list';
import AccountingEntryList from '../components/basic-info/accounting-entry/list';

import BalanceList from '../components/financial-management/balance/list';
import QueryManualList from '../components/financial-management/query-manual/list';

import GeneralLedgerList from '../components/ledger/general-ledger/list';

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
            {path: '/merchant/index', component: MerchantList, name: '商户信息'},
            {path: '/agent/index', component: AgentList, name: '代理商信息'},
            {path: '/channel/index', component: ChannelList, name: '渠道信息'},
            {path: '/busitype/index', component: BusinessTypeList, name: '业务类型'},
            {path: '/entry/index', component: AccountingEntryList, name: '会计分录'},
        ]
    },

    {
        path: '/',
        component: Home,
        children: [
            {path: '/balance/index', component: BalanceList, name: '余额查询'},
            {path: '/balance/queryManual', component: QueryManualList, name: '手工记账管理'},
        ]
    },

    {
        path: '/',
        component: Home,
        children: [
            {path: '/ledger/ledger', component: GeneralLedgerList, name: '总账'},
        ]
    },
];
export default routes;
