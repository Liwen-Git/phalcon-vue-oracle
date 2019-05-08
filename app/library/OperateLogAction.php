<?php

namespace App\Library;

class OperateLogAction
{
    //操作员日志
    const LOGININ = '登陆';
    const LOGINOUT = '登出';
    const EDITPWD = '修改密码';

    const USERADD = '系统管理-用户管理-添加';
    const USEREDIT = '系统管理-用户管理-修改';
    const USERDEL = '系统管理-用户管理-删除';
    const USERUNLOCK = '系统管理-用户管理-解锁';

    const ROLEADD = '系统管理-角色管理-添加';
    const ROLEEDIT = '系统管理-角色管理-修改';
    const ROLEDEL = '系统管理-角色管理-删除';

    const MENUADD = '系统管理-菜单管理-添加菜单';
    const MENUEDIT = '系统管理-菜单管理-修改菜单';
    const MENUDEL = '系统管理-菜单管理-删除菜单';
    const MENUDELACT = '系统管理-菜单管理-删除动作';
    const MENUDELFIELD = '系统管理-菜单管理-删除字段';

    const SUBJECTADD = '基础信息维护-会计科目-添加';
    const SUBJECTEDIT = '基础信息维护-会计科目-修改';
    const SUBJECTDEL = '基础信息维护-会计科目-删除';

    const CHANNELADD = '基础信息维护-渠道信息-添加';
    const CHANNELEDIT = '基础信息维护-渠道信息-修改';
    const CHANNELDEL = '基础信息维护-渠道信息-删除';

    const BUSITYPEADD = '基础信息维护-业务类型-添加';
    const BUSITYPEEDIT = '基础信息维护-业务类型-修改';

    const ENTRYADD = '基础信息维护-会记分录-添加';
    const ENTRYEDIT = '基础信息维护-会记分录-修改';

    const BALANCEFROZEN = '财务管理-余额查询-冻结';
    const BALANCEUNFROZEN = '财务管理-余额查询-解冻';

    const MANUALADD = '财务管理-手工记账管理-录入';
    const MANUALCHECK = '财务管理-手工记账管理-审核';

    const AGENTPSUPDATE = '报表管理-代理商分润报表-修改';
    const AGENTPSCHECK = '报表管理-代理商分润报表-审核';
    const AGENTPSDETAILDOWN = '报表管理-代理商分润报表-明细下载';
    const AGENTPSAUDIT = '报表管理-代理商分润报表-批量审核';
    const AGENTPSCWHT = '报表管理-代理商分润报表-财务回填';
    const AGENTPSEXPORT = '报表管理-代理商分润报表-导出记录';

    const AGENTPSDETAILEXPORT = '报表管理-分润明细-导出记录';

    const AGENTPSFAILEXPORT = '报表管理-未分润明细-导出记录';

    const PROFITDETAILDOWN = '报表管理-利润报表-明细下载';
    const PROFITLISTEXPORT = '报表管理-利润报表-导出记录';

    const PROFITDETAILEXPORT = '报表管理-利润明细-导出记录';
    const PROFITDAILYEXPORT = '报表管理-日利润报表-导出记录';
    const AGENTSETTLEMENTEXPORT = '报表管理-代理商结算报表-导出记录';
    const PROFITSUMMARYEXPORT = '报表管理-利润汇总报表-导出记录';
    const CHANNELTRADEEXPORT = '报表管理-渠道交易报表-导出记录';

    const MODIFIEDREPORT = '报表管理-报表数据修正-重跑分润';

}
