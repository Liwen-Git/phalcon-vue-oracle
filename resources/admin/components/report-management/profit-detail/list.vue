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
                    <el-form-item label="交易日期">
                        <el-date-picker
                                v-model="form.tran_date_start"
                                type="date"
                                placeholder="请选择开始日期"
                                format="yyyy 年 MM 月 dd 日"
                                value-format="yyyy-MM-dd"
                        ></el-date-picker>
                        <span> - </span>
                        <el-date-picker
                                v-model="form.tran_date_end"
                                type="date"
                                placeholder="请选择结束日期"
                                format="yyyy 年 MM 月 dd 日"
                                value-format="yyyy-MM-dd"
                                :picker-options="{disabledDate: (time) => {return time.getTime() < new Date(form.tran_date_start)}}"
                        ></el-date-picker>
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
                    <el-form-item prop="agentps_sum_id" label="分润汇总单号">
                        <el-input type="text" v-model="form.agentps_sum_id" placeholder="请输入汇总单号" clearable></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="12">
                    <el-form-item prop="agentps_detail_id" label="分润单号">
                        <el-input type="text" v-model="form.agentps_detail_id" placeholder="请输入分润单号" clearable></el-input>
                    </el-form-item>
                </el-col>
            </el-row>
            <el-row>
                <el-col :span="12">
                    <el-form-item prop="order_id" label="交易单号">
                        <el-input type="text" v-model="form.order_id" placeholder="请输入交易单号" clearable></el-input>
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
                <el-col :span="10" class="btn-class">
                    <el-form-item>
                        <el-button type="primary" @click="search">搜索</el-button>
                        <el-button type="success" @click="exportExcel">导出记录</el-button>
                    </el-form-item>
                </el-col>
            </el-row>
        </el-form>
        <el-table :data="list" stripe>
            <el-table-column prop="ps_date" label="分润日期" width="100px"></el-table-column>
            <el-table-column prop="agentps_sum_id" label="分润汇总单号" show-overflow-tooltip></el-table-column>
            <el-table-column prop="agentps_detail_id" label="分润单号" show-overflow-tooltip></el-table-column>
            <el-table-column prop="order_id" label="交易单号"></el-table-column>
            <el-table-column prop="agent_id" label="代理商号"></el-table-column>
            <el-table-column prop="agent_name" label="代理商名称" show-overflow-tooltip></el-table-column>
            <el-table-column prop="agent_level" label="代理商级别"></el-table-column>
            <el-table-column prop="agent_fee_rate" label="代理商费率"></el-table-column>
            <el-table-column prop="lower_agent_fee_rate" label="下级费率"></el-table-column>
            <el-table-column prop="merchant_id" label="商户号"></el-table-column>
            <el-table-column prop="merchant_name" label="商户名称" show-overflow-tooltip></el-table-column>
            <el-table-column prop="busi_type" label="业务类型" show-overflow-tooltip></el-table-column>
            <el-table-column prop="sub_busi_type" label="业务方向"></el-table-column>
            <el-table-column prop="bank_card_type" label="借贷标识"></el-table-column>
            <el-table-column prop="trade_time" label="交易时间"></el-table-column>
            <el-table-column prop="tran_amt" label="交易金额(元)" width="100px">
                <template slot-scope="scope">
                    {{parseFloat(scope.row.tran_amt).toFixed(2)}}
                </template>
            </el-table-column>
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
            <el-table-column prop="real_agentps_amt" label="实际分润金额(元)">
                <template slot-scope="scope">
                    {{parseFloat(scope.row.real_agentps_amt).toFixed(2)}}
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
            <el-col style="margin-top: 10px">
                交易总金额：{{sum_tran_amt}}元，
                分润总金额：{{sum_agentps_amt}}元，
                分润总笔数：{{sum_agentps_cnt}}笔，
                亏损总金额：{{sum_loss_amt}}元，
                实际分润总金额：{{sum_real_agentps_amt}}元
                <span style="color: red;">（所有页面数据汇总）</span>
            </el-col>
        </el-row>
    </page>
</template>

<script>
    export default {
        name: "profit-detail-list",
        data() {
            return {
                form: {
                    ps_date_start: '',
                    ps_date_end: '',

                    tran_date_start: '',
                    tran_date_end: '',

                    agentps_sum_id: '',
                    agentps_detail_id: '',

                    agent_id: '',
                    agent_name: '',

                    merchant_id: '',
                    merchant_name: '',

                    order_id: '',
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

                sum_tran_amt: '0.00',
                sum_agentps_amt: '0.00',
                sum_agentps_cnt: '0',
                sum_loss_amt: '0.00',
                sum_real_agentps_amt: '0.00',
            }
        },
        methods: {
            search() {
                this.form.page = 1;
                this.getList();
            },
            getList() {
                api.get('profit/profitDetailList', this.form).then(data => {
                    this.list = data.list;
                    this.total = data.total;
                    if (Object.keys(data.sum).length > 0) {
                        this.sum_tran_amt = parseFloat(data.sum.sum_tran_amt).toFixed(2);
                        this.sum_agentps_amt = parseFloat(data.sum.sum_agentps_amt).toFixed(2);
                        this.sum_agentps_cnt = parseInt(data.sum.sum_agentps_cnt);
                        this.sum_loss_amt = parseFloat(data.sum.sum_loss_amt).toFixed(2);
                        this.sum_real_agentps_amt = parseFloat(data.sum.sum_real_agentps_amt).toFixed(2);
                    }
                })
            },
            changePageSize(size) {
                this.form.pageSize = size;
                this.form.page = 1;
                this.getList();
            },
            initPage() {
                api.get('profit/businessTypesOfNew').then(data => {
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
            exportExcel() {
                api.post('profit/exportProfitDetail', this.form).then(data => {
                    window.open(data.list.url);
                })
            }
        },
        created() {
            this.form.ps_date_start = moment().subtract(1, 'days').format("YYYY-MM-DD");
            this.form.ps_date_end = moment().subtract(1, 'days').format("YYYY-MM-DD");
            this.form.tran_date_start = moment().subtract(1, 'days').format("YYYY-MM-DD");
            this.form.tran_date_end = moment().subtract(1, 'days').format("YYYY-MM-DD");
            this.initPage();
        },
    }
</script>

<style scoped>
    .btn-class {
        float: right;
    }
</style>
