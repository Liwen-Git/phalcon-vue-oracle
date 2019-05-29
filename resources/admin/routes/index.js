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
import SubsidiaryLedgerList from '../components/ledger/subsidiary-ledger/list';
import JournalLedgerList from '../components/ledger/journal-ledger/list';
import TrialBalanceList from '../components/ledger/trial-balance/list';

import AgentProfitSharingList from '../components/report-management/agent-profit-sharing/list';
import AgentProfitDetailList from '../components/report-management/agent-profit-detail/list';
import ProfitFailDetailList from '../components/report-management/profit-fail-detail/list';
import ProfitList from '../components/report-management/profit-statement/list';

const routes = [
    {path: '/login', component: Login, name: 'Login'},

    // 主页
    {
        path: '/',
        component: Home,
        children: [
            {path: '/welcome', component: Welcome, name: 'Welcome'},
        ]
    },

    // 系统管理
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

    // 基础信息维护
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

    // 财务管理
    {
        path: '/',
        component: Home,
        children: [
            {path: '/balance/index', component: BalanceList, name: '余额查询'},
            {path: '/balance/queryManual', component: QueryManualList, name: '手工记账管理'},
        ]
    },

    // 分类账
    {
        path: '/',
        component: Home,
        children: [
            {path: '/ledger/ledger', component: GeneralLedgerList, name: '总账'},
            {path: '/ledger/subsidiary', component: SubsidiaryLedgerList, name: '明细账'},
            {path: '/ledger/journal', component: JournalLedgerList, name: '序时账'},
            {path: '/ledger/querysubbalance', component: TrialBalanceList, name: '试算平衡'},
        ]
    },

    // 报表管理
    {
        path: '/',
        component: Home,
        children: [
            {path: '/report/qryagentpslist', component: AgentProfitSharingList, name: '代理商分润报表'},
            {path: '/profit/agentpsdetail', component: AgentProfitDetailList, name: '分润明细'},
            {path: '/profit/agentpsfaildetail', component: ProfitFailDetailList, name: '未分润明细'},
            {path: '/profit/profitlist', component: ProfitList, name: '利润报表'},
        ]
    }
];
export default routes;
