<template>
    <page title="代理商分润报表">
        <el-form :model="form" ref="form" size="small" inline label-width="150px">
            <el-row>
                <el-col :span="12">
                    <el-form-item label="分润日期">
                        <el-date-picker
                                v-model="form.ps_date_start"
                                type="date"
                                placeholder="请选择开始日期"
                                format="yyyy 年 MM 月 dd 日"
                                value-format="yyyy-MM-dd"
                        ></el-date-picker>
                        <span> - </span>
                        <el-date-picker
                                v-model="form.ps_date_end"
                                type="date"
                                placeholder="请选择结束日期"
                                format="yyyy 年 MM 月 dd 日"
                                value-format="yyyy-MM-dd"
                                :picker-options="{disabledDate: (time) => {return time.getTime() < new Date(form.ps_date_start)}}"
                        ></el-date-picker>
                    </el-form-item>
                </el-col>
                <el-col :span="12">
                    <el-form-item prop="agentps_sum_id" label="分润汇总单号">
                        <el-input type="text" v-model="form.agentps_sum_id" placeholder="请输入汇总单号" clearable></el-input>
                    </el-form-item>
                </el-col>
            </el-row>
            <el-row>
                <el-col :span="12">
                    <el-form-item prop="agent_id" label="代理商号">
                        <el-input type="text" v-model="form.agent_id" placeholder="请输入代理商号" clearable></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="12">
                    <el-form-item prop="agent_name" label="代理商名称">
                        <el-select v-model="form.agent_name"
                                   clearable
                                   filterable
                                   remote
                                   placeholder="请输入关键字"
                                   :remote-method="searchAgentName"
                                   :loading="theAgentNameLoading"
                        >
                            <el-option v-for="(item, index) in agentOptions" :key="index" :label="item.agent_name" :value="item.agent_name"></el-option>
                        </el-select>
                    </el-form-item>
                </el-col>
            </el-row>
            <el-row>
                <el-col :span="12">
                    <el-form-item prop="merchant_id" label="商户编号">
                        <el-input type="text" v-model="form.merchant_id" placeholder="请输入商户编号" clearable></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="12">
                    <el-form-item prop="merchant_name" label="商户名称">
                        <el-select v-model="form.merchant_name"
                                   clearable
                                   filterable
                                   remote
                                   placeholder="请输入关键字"
                                   :remote-method="searchMerchantName"
                                   :loading="theMerchantLoading"
                        >
                            <el-option v-for="(item, index) in merchantOptions" :key="index" :label="item.merchant_name" :value="item.merchant_name"></el-option>
                        </el-select>
                    </el-form-item>
                </el-col>
            </el-row>
            <el-row>
                <el-col :span="12">
                    <el-form-item prop="profit_share_cycle" label="代理商分润周期">
                        <el-select v-model="form.profit_share_cycle" placeholder="全部" clearable>
                            <el-option label="日结" value="0"></el-option>
                            <el-option label="周结" value="1"></el-option>
                            <el-option label="月结" value="2"></el-option>
                        </el-select>
                    </el-form-item>
                </el-col>
                <el-col :span="12">
                    <el-form-item prop="busitypes_code" label="业务类型">
                        <el-cascader
                                v-model="form.busitypes_code"
                                placeholder="请选择(可搜索)"
                                :options="businessTypes"
                                :props="{value: 'type', label: 'name', children: 'child'}"
                                filterable
                                change-on-select
                                clearable
                                class="w-300"
                        ></el-cascader>
                    </el-form-item>
                </el-col>
            </el-row>
            <el-row>
                <el-col :span="15">
                    <el-form-item prop="state" label="分润状态">
                        <el-checkbox :indeterminate="isIndeterminate" v-model="checkAll" @change="handleCheckAllChange">全选</el-checkbox>
                        <div style="margin: 15px 0;"></div>
                        <el-checkbox-group v-model="form.state" @change="handleCheckedChange">
                            <el-checkbox label="0">待审核</el-checkbox>
                            <el-checkbox label="1">待确认</el-checkbox>
                            <el-checkbox label="2">待付款</el-checkbox>
                            <el-checkbox label="3">关闭</el-checkbox>
                            <el-checkbox label="4">划款成功</el-checkbox>
                            <el-checkbox label="5">划款失败</el-checkbox>
                            <el-checkbox label="6">代理商回退</el-checkbox>
                        </el-checkbox-group>
                    </el-form-item>
                </el-col>
                <el-col :span="9" class="btn-class">
                    <el-form-item>
                        <el-button type="primary" @click="search">搜索</el-button>
                        <el-button type="success" @click="audit">审核</el-button>
                        <el-button type="success" @click="">财务回填</el-button>
                        <el-button type="success" @click="">导出记录</el-button>
                    </el-form-item>
                </el-col>
            </el-row>
        </el-form>
        <el-table :data="list" stripe @select-all="selectAll" @select="selectColumn">
            <el-table-column type="selection"></el-table-column>
            <el-table-column prop="ps_date" label="分润日期" width="100px"></el-table-column>
            <el-table-column prop="agentps_sum_id" label="分润汇总单号" show-overflow-tooltip></el-table-column>
            <el-table-column prop="agent_id" label="代理商号"></el-table-column>
            <el-table-column prop="agent_name" label="代理商名称" show-overflow-tooltip></el-table-column>
            <el-table-column prop="agent_level" label="代理商级别"></el-table-column>
            <el-table-column prop="merchant_id" label="商户号"></el-table-column>
            <el-table-column prop="merchant_name" label="商户名称" show-overflow-tooltip></el-table-column>
            <el-table-column prop="busi_type" label="业务类型" show-overflow-tooltip></el-table-column>
            <el-table-column prop="sub_busi_type" label="业务方向"></el-table-column>
            <el-table-column prop="profit_share_cycle" label="分润周期"></el-table-column>
            <el-table-column prop="tran_amt" label="交易总金额(元)" width="100px">
                <template slot-scope="scope">
                    {{parseFloat(scope.row.tran_amt).toFixed(2)}}
                </template>
            </el-table-column>
            <el-table-column prop="tran_cnt" label="交易总笔数"></el-table-column>
            <el-table-column prop="pssucc_tran_amt" label="成功分润交易金额(元)" width="100px">
                <template slot-scope="scope">
                    {{parseFloat(scope.row.pssucc_tran_amt).toFixed(2)}}
                </template>
            </el-table-column>
            <el-table-column prop="pssucc_tran_cnt" label="成功分润交易笔数"></el-table-column>
            <el-table-column prop="psfail_tran_amt" label="未分润交易金额(元)">
                <template slot-scope="scope">
                    {{parseFloat(scope.row.psfail_tran_amt).toFixed(2)}}
                </template>
            </el-table-column>
            <el-table-column prop="psfail_tran_cnt" label="未分润交易笔数"></el-table-column>
            <el-table-column prop="agentps_amt" label="分润金额(元)">
                <template slot-scope="scope">
                    {{parseFloat(scope.row.agentps_amt).toFixed(2)}}
                </template>
            </el-table-column>
            <el-table-column prop="loss_amt" label="亏损金额(元)">
                <template slot-scope="scope">
                    {{parseFloat(scope.row.loss_amt).toFixed(2)}}
                </template>
            </el-table-column>
            <el-table-column prop="other_amt" label="调整金额(元)">
                <template slot-scope="scope">
                    {{parseFloat(scope.row.other_amt).toFixed(2)}}
                </template>
            </el-table-column>
            <el-table-column prop="real_agentps_amt" label="实际分润金额(元)">
                <template slot-scope="scope">
                    {{parseFloat(scope.row.real_agentps_amt).toFixed(2)}}
                </template>
            </el-table-column>
            <el-table-column prop="receive_account_name" label="收款账户名"></el-table-column>
            <el-table-column prop="receive_account_id" label="收款账户"></el-table-column>
            <el-table-column prop="receive_account_bank" label="开户行"></el-table-column>
            <el-table-column prop="state" label="分润状态">
                <template slot-scope="scope">
                    <span v-if="scope.row.state == 0">待审核</span>
                    <span v-else-if="scope.row.state == 1">待确认</span>
                    <span v-else-if="scope.row.state == 2">待付款</span>
                    <span v-else-if="scope.row.state == 3">关闭</span>
                    <span v-else-if="scope.row.state == 4">划款成功</span>
                    <span v-else-if="scope.row.state == 5">划款失败</span>
                    <span v-else-if="scope.row.state == 6">代理商回退</span>
                    <span v-else>other</span>
                </template>
            </el-table-column>
            <el-table-column label="操作" fixed="right" width="200px">
                <template slot-scope="scope">
                    <el-button type="text" @click="edit(scope.row)">编辑</el-button>
                    <el-button type="text" @click="check(scope.row)">审核</el-button>
                    <el-button type="text" @click="down(scope.row.agentps_sum_id)">明细下载</el-button>
                </template>
            </el-table-column>
        </el-table>
        <el-pagination
                layout="sizes, total, prev, pager, next"
                :page-size="form.pageSize"
                :total="total"
                :current-page.sync="form.page"
                @current-change="getList"
                :page-sizes="[10, 50, 100, 200]"
                @size-change="changePageSize"
        ></el-pagination>
        <el-row style="margin-top: 20px">
            <el-col>
                成功分润交易金额小计：{{subsum_pssucc_tran_amt}}元，
                分润金额小计：{{subsum_agentps_amt}}元，
                亏损金额小计：{{subsum_loss_amt}}元，
                实际分润金额小计：{{subsum_real_agentps_amt}}元
                <span style="color: red;">（当前页面数据汇总）</span>
            </el-col>
            <el-col style="margin-top: 10px">
                成功分润交易金额总计：{{sum_pssucc_tran_amt}}元，
                分润金额总计：{{sum_agentps_amt}}元，
                亏损金额总计：{{sum_loss_amt}}元，
                实际分润金额总计：{{sum_real_agentps_amt}}元
                <span style="color: red;">（所有页面数据汇总）</span>
            </el-col>
        </el-row>

        <el-dialog title="代理商分润编辑" :visible.sync="editDialog" :close-on-click-modal="false" width="70%">
            <edit-agent-profit-sharing :theReport="theReport" @close="editDialog = false" @editSuccess="theSuccess"></edit-agent-profit-sharing>
        </el-dialog>

        <el-dialog title="代理商分润审核" :visible.sync="checkDialog" :close-on-click-modal="false" width="70%">
            <check-agent-profit-sharing :theCheck="theCheck" @close="checkDialog = false" @checkSuccess="theSuccess"></check-agent-profit-sharing>
        </el-dialog>

        <el-dialog title="代理商分润批量审核" :visible.sync="auditDialog" :close-on-click-modal="false" width="25%">
            <audit-agent-profit-sharing
                    :auditAmount="auditAmount"
                    :auditNum="auditNum"
                    :auditForm="auditForm"
                    :auditIds="auditIds"
                    :allSelection="allSelection"
                    @close="auditDialog = false"
                    @auditSuccess="theSuccess"></audit-agent-profit-sharing>
        </el-dialog>
    </page>
</template>

<script>
    import EditAgentProfitSharing from './edit';
    import CheckAgentProfitSharing from './check';
    import AuditAgentProfitSharing from './audit';

    export default {
        name: "agent-profit-sharing-list",
        data() {
            return {
                form: {
                    ps_date_start: '',
                    ps_date_end: '',
                    agentps_sum_id: '',
                    agent_id: '',
                    agent_name: '',
                    merchant_id: '',
                    merchant_name: '',
                    profit_share_cycle: '',
                    state: [],
                    busitypes_code: [],

                    page: 1,
                    pageSize: 10,
                },
                total: 10,
                list: [],

                agentOptions: [],
                theAgentNameLoading: false,
                merchantOptions: [],
                theMerchantLoading: false,

                businessTypes: [],

                checkAll: false,
                allStates: ['0', '1', '2', '3', '4', '5', '6'],
                isIndeterminate: true,

                subsum_pssucc_tran_amt: '0.00',
                subsum_agentps_amt: '0.00',
                subsum_loss_amt: '0.00',
                subsum_real_agentps_amt: '0.00',
                sum_pssucc_tran_amt: '0.00',
                sum_agentps_amt: '0.00',
                sum_loss_amt: '0.00',
                sum_real_agentps_amt: '0.00',

                editDialog: false,
                theReport: null,

                checkDialog: false,
                theCheck: null,

                allSelection: false,
                theSelections: [],

                auditDialog: false,
                auditNum: 0,
                auditAmount: 0,
                auditForm: {},
                auditIds: '',
            }
        },
        methods: {
            search() {
                this.form.page = 1;
                this.getList();
            },
            getList() {
                api.get('report_of_agent/agentProfitSharingList', this.form).then(data => {
                    this.list = data.list;
                    this.total = data.total;
                    if (Object.keys(data.sum).length > 0) {
                        this.subsum_pssucc_tran_amt = parseFloat(data.sum.subsum_pssucc_tran_amt).toFixed(2);
                        this.subsum_agentps_amt = parseFloat(data.sum.subsum_agentps_amt).toFixed(2);
                        this.subsum_loss_amt = parseFloat(data.sum.subsum_loss_amt).toFixed(2);
                        this.subsum_real_agentps_amt = parseFloat(data.sum.subsum_real_agentps_amt).toFixed(2);
                        this.sum_pssucc_tran_amt = parseFloat(data.sum.sum_pssucc_tran_amt).toFixed(2);
                        this.sum_agentps_amt = parseFloat(data.sum.sum_agentps_amt).toFixed(2);
                        this.sum_loss_amt = parseFloat(data.sum.sum_loss_amt).toFixed(2);
                        this.sum_real_agentps_amt = parseFloat(data.sum.sum_real_agentps_amt).toFixed(2);
                    }
                    // 更新列表 checkbox还原
                    this.allSelection = false;
                    this.theSelections = [];
                })
            },
            changePageSize(size) {
                this.form.pageSize = size;
                this.form.page = 1;
                this.getList();
            },
            initPage() {
                api.get('report_of_agent/businessTypesOfNew').then(data => {
                    this.businessTypes = data;
                }).then(() => {
                    this.getList();
                })
            },
            searchAgentName(query) {
                if (query !== '') {
                    this.theAgentNameLoading = true;
                    setTimeout(() => {
                        this.theAgentNameLoading = false;
                        api.get('agent/query_agent_info', {agent_name: query}).then(data => {
                            this.agentOptions = data.list;
                        })
                    }, 200);
                } else {
                    this.agentOptions = [];
                }
            },
            searchMerchantName(query) {
                if (query !== '') {
                    this.theMerchantLoading = true;
                    setTimeout(() => {
                        this.theMerchantLoading = false;
                        api.post('merchant/query_merchant_info', {merchant_name: query}).then(data => {
                            this.merchantOptions = data.list;
                        })
                    }, 200);
                } else {
                    this.merchantOptions = [];
                }
            },
            handleCheckAllChange(val) {
                this.form.state = val ? this.allStates : [];
                this.isIndeterminate = false;
            },
            handleCheckedChange(value) {
                let checkedCount = value.length;
                this.checkAll = checkedCount === this.allStates.length;
                this.isIndeterminate = checkedCount > 0 && checkedCount < this.allStates.length;
            },
            edit(row) {
                this.theReport = row;
                this.editDialog = true;
            },
            theSuccess() {
                this.getList();
            },
            check(row) {
                this.theCheck = row;
                this.checkDialog = true;
            },
            down(agentps_sum_id) {
                api.get('report_of_agent/downDetail', {agentps_sum_id: agentps_sum_id}).then(data => {
                    window.open(data.list.url);
                })
            },
            selectAll(selection) {
                if (selection.length <= 0) {
                    this.allSelection = false;
                } else {
                    this.allSelection = true;
                }
                this.theSelections = selection;
            },
            selectColumn(selection) {
                this.allSelection = false;
                this.theSelections = selection;
            },
            audit() {
                if (this.theSelections.length <= 0) {
                    this.$message.error('请勾选审核数据');
                    return false;
                }

                if (this.allSelection) {
                    // 全选
                    this.auditNum = this.total;
                    this.auditAmount = this.sum_real_agentps_amt;
                    this.auditForm = this.form;
                    this.auditDialog = true;
                } else {
                    // 不是全选
                    this.auditNum = this.theSelections.length;
                    let ids = [];
                    this.theSelections.forEach(item => {
                        this.auditAmount += parseFloat(item.real_agentps_amt);
                        ids.push(item.agentps_sum_id);
                    });
                    this.auditIds = ids.join();
                    this.auditDialog = true;
                }
            }
        },
        created() {
            this.form.ps_date_start = moment().subtract(1, 'days').format("YYYY-MM-DD");
            this.form.ps_date_end = moment().subtract(1, 'days').format("YYYY-MM-DD");
            this.initPage();
        },
        components: {
            EditAgentProfitSharing,
            CheckAgentProfitSharing,
            AuditAgentProfitSharing,
        }
    }
</script>

<style scoped>
    .btn-class {
        margin-top: 40px;
    }
</style>
