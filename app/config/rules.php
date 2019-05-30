<?php

return [
    // 用户管理
    '/admin/user/index' => '/user/list',
    '/admin/user/add' => '/user/add',
    '/admin/user/edit' => '/user/edit',
    '/admin/user/del' => '/user/delete',
    '/admin/user/unlock' => '/user/unlock',

    // 角色管理
    '/admin/role/index' => '/role/list',
    '/admin/role/edit' => '/role/edit',
    '/admin/role/add' => '/role/add',
    '/admin/role/del' => '/role/delete',

    // 操作日志
    '/admin/operate/index' => '/operate/list',

    // 会计科目
    '/admin/subject/index' => '/subject/list',
    '/admin/subject/add' => '/subject/add',
    '/admin/subject/edit' => '/subject/edit',
    '/admin/subject/del' => '/subject/delete',
    '/admin/subject/getSubjects' => '',
    '/admin/subject/getParentSubject' => '',
    '/admin/subject/getSubject' => '',
    '/admin/subject/getLevelSubject' => '',

    // 商户信息
    '/admin/merchant/index' => '/merchant/list',
    '/admin/merchant/detail' => '/merchant/detail',
    '/admin/merchant/querymerinfo' => '/merchant/query_merchant_info',

    // 代理商信息
    '/admin/agent/detail' => '/agent/detail',
    '/admin/agent/index' => '/agent/list',
    '/admin/agent/queryagentinfo' => '/agent/query_agent_info',

    // 渠道信息
    '/admin/channel/index' => '/channel/list',
    '/admin/channel/feedetail' => '',
    '/admin/channel/add' => '/channel/add',
    '/admin/channel/edit' => '/channel/edit',
    '/admin/channel/del' => '/channel/delete',
    '/admin/channel/querybankinfo' => '/channel/queryBankInfo',
    '/admin/channel/querychninfo' => '',

    // 业务类型
    '/admin/busitype/addBusitype' => '/business_type/add',
    '/admin/busitype/editBusitype' => '/business_type/edit',
    '/admin/busitype/getClassBusitype' => '',
    '/admin/busitype/getBusitype' => '/business_type/businessTypesOfNew',
    '/admin/busitype/index' => '/business_type/list',

    // 会计分录
    '/admin/entry/editEntry' => '/accounting_entry/edit',
    '/admin/entry/addEntry' => '/accounting_entry/add',
    '/admin/entry/index' => '/accounting_entry/list',

    // 余额查询
    '/admin/balance/frozen' => '/balance/frozen',
    '/admin/balance/unfrozen' => '/balance/unfrozen',
    '/admin/balance/index' => '/balance/list',

    // 手工记账管理
    '/admin/balance/queryManual' => '/balance/queryManualList',
    '/admin/balance/queryVirtualBal' => '/balance/queryVirtualBalance',
    '/admin/balance/addManual' => '/balance/addManual',
    '/admin/balance/checkManual' => '/balance/manualAudit',

    // 总账
    '/admin/Ledger/ledger' => '/ledger/ledgerList',

    // 明细账
    '/admin/Ledger/subsidiary' => '/ledger/subsidiaryLedgerList',

    // 序时账
    '/admin/Ledger/journal' => '/ledger/journalLedgerList',
    '/admin/Ledger/setGroupRelated' => '',

    // 试算平衡
    '/admin/Ledger/querysubbalance' => '/ledger/trialBalanceList',

    // 代理商分润报表
    '/admin/report/checkagentps' => '/report_of_agent/check',
    '/admin/report/exportagentpslist' => '/report_of_agent/export',
    '/admin/report/downagentpsdetail' => '/report_of_agent/downDetail',
    '/admin/report/audit' => '/report_of_agent/audit',
    '/admin/report/cwht' => '/report_of_agent/financial',
    '/admin/report/qryagentpslist' => '/report_of_agent/agentProfitSharingList',
    '/admin/report/updateagentps' => '/report_of_agent/edit',

    // 分润明细
    '/admin/profit/exportagentpsdetail' => '/profit/exportAgentProfitDetail',
    '/admin/profit/agentpsdetail' => '/profit/agentProfitDetailList',

    // 未分润明细
    '/admin/profit/agentpsfaildetail' => '/profit/profitFailDetailList',
    '/admin/profit/exportagentpsfaillist' => '/profit/exportProfitFailDetail',

    // 利润报表
    '/admin/profit/profitlist' => '/profit/profitList',
    '/admin/profit/exportprofitlist' => '/profit/exportProfitList',
    '/admin/profit/downprofitdetail' => '/profit/downProfitDetail',

    // 利润明细
    '/admin/profit/exportprofitdetail' => '/profit/exportProfitDetail',
    '/admin/profit/profitdetail' => '/profit/profitDetailList',

    // 报表数据修正
    '/admin/modifiedreport/getlist' => '/profit/modifyReportList',
    '/admin/modifiedreport/modifiedreport' => '/profit/modifyReport',

    // 日利润报表
    '/admin/profit/dailyprofitlist' => '/profit/exportDailyProfit',

    // 代理商结算报表
    '/admin/report/agentsettlementreport' => '/report_of_agent/exportAgentSettlementReport',

    // 渠道交易报表
    '/admin/channel/channeltradelist' => '/channel/exportChannelTrade',

    // 利润汇总报表
    '/admin/profit/profitsummarylist' => '/profit/exportProfitSummary',
];
