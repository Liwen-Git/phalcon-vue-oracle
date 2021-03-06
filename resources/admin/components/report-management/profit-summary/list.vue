<template>
    <page title="利润汇总报表">
        <el-form :model="form" ref="form" size="small" inline label-width="150px">
            <el-row>
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
                <el-col :span="10" class="btn-class">
                    <el-form-item>
                        <el-button type="primary" @click="search">搜索</el-button>
                        <el-button type="success" @click="exportExcel">导出记录</el-button>
                    </el-form-item>
                </el-col>
            </el-row>
        </el-form>
        <el-table :data="list" stripe border>
            <el-table-column prop="trade_date" label="交易日期区间" show-overflow-tooltip>
                <template slot-scope="scope">
                    {{start_date}} - {{end_date}}
                </template>
            </el-table-column>
            <el-table-column prop="merchant_id" label="商户号"></el-table-column>
            <el-table-column prop="merchant_name" label="商户名称" show-overflow-tooltip></el-table-column>
            <el-table-column prop="busi_name" label="业务类型" show-overflow-tooltip></el-table-column>
            <el-table-column prop="busi_direction" label="业务方向">
                <template slot-scope="scope">
                    <span v-if="scope.row.busi_direction == '01'">消费</span>
                    <span v-else-if="scope.row.busi_direction == '02'">退货</span>
                    <span v-else>other</span>
                </template>
            </el-table-column>
            <el-table-column prop="one_agent_id" label="一级代理商号"></el-table-column>
            <el-table-column prop="one_agent_name" label="一级代理商名称" show-overflow-tooltip></el-table-column>
            <el-table-column prop="one_agentps_amt" label="一级代理分润">
                <template slot-scope="scope">
                    {{parseFloat(scope.row.one_agentps_amt).toFixed(2)}}
                </template>
            </el-table-column>
            <el-table-column prop="one_agent_other_amt" label="一级代理调整金额">
                <template slot-scope="scope">
                    {{parseFloat(scope.row.one_agent_other_amt).toFixed(2)}}
                </template>
            </el-table-column>
            <el-table-column prop="two_agent_id" label="二级代理商号"></el-table-column>
            <el-table-column prop="two_agent_name" label="二级代理商名称" show-overflow-tooltip></el-table-column>
            <el-table-column prop="two_agentps_amt" label="二级代理分润">
                <template slot-scope="scope">
                    {{parseFloat(scope.row.two_agentps_amt).toFixed(2)}}
                </template>
            </el-table-column>
            <el-table-column prop="two_agent_other_amt" label="二级代理调整金额">
                <template slot-scope="scope">
                    {{parseFloat(scope.row.two_agent_other_amt).toFixed(2)}}
                </template>
            </el-table-column>
            <el-table-column prop="loss_amt" label="亏损金额">
                <template slot-scope="scope">
                    {{parseFloat(scope.row.loss_amt).toFixed(2)}}
                </template>
            </el-table-column>
            <el-table-column prop="tran_amt" label="交易金额">
                <template slot-scope="scope">
                    {{parseFloat(scope.row.tran_amt).toFixed(2)}}
                </template>
            </el-table-column>
            <el-table-column prop="tran_cnt" label="交易笔数">
                <template slot-scope="scope">
                    {{parseFloat(scope.row.tran_cnt).toFixed(2)}}
                </template>
            </el-table-column>
            <el-table-column prop="merchant_fee" label="商户手续费">
                <template slot-scope="scope">
                    {{parseFloat(scope.row.merchant_fee).toFixed(2)}}
                </template>
            </el-table-column>
            <el-table-column prop="channel_fee" label="渠道成本">
                <template slot-scope="scope">
                    {{parseFloat(scope.row.channel_fee).toFixed(2)}}
                </template>
            </el-table-column>
            <el-table-column prop="all_agentps_amt" label="代理商分润">
                <template slot-scope="scope">
                    {{parseFloat(scope.row.all_agentps_amt).toFixed(2)}}
                </template>
            </el-table-column>
            <el-table-column prop="net_tfb_profit_amt" label="天下收益">
                <template slot-scope="scope">
                    {{parseFloat(scope.row.net_tfb_profit_amt).toFixed(2)}}
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
                一级代理分润小计：{{subsum_one_agentps_amt}}元，
                一级代理调整金额小计：{{subsum_one_agent_other_amt}}元，
                二级代理分润小计：{{subsum_two_agentps_amt}}元，
                二级代理调整金额小计：{{subsum_two_agent_other_amt}}元，
                交易金额小计：{{subsum_tran_amt}}元，
                交易笔数小计：{{subsum_tran_cnt}}笔，
                商户手续费小计：{{subsum_merchant_fee}}元，
                渠道成本小计：{{subsum_channel_fee}}元，
                代理商分润小计：{{subsum_all_agentps_amt}}元，
                亏损金额小计：{{subsum_loss_amt}}元，
                天下收益小计：{{subsum_net_tfb_profit_amt}}元
                <span style="color: red;">（当前页面数据汇总）</span>
            </el-col>
            <el-col style="margin-top: 10px">
                一级代理分润总计：{{sum_one_agentps_amt}}元，
                一级代理调整金额总计：{{sum_one_agent_other_amt}}元，
                二级代理分润总计：{{sum_two_agentps_amt}}元，
                二级代理调整金额总计：{{sum_two_agent_other_amt}}元，
                交易金额总计：{{sum_tran_amt}}元，
                交易笔数总计：{{sum_tran_cnt}}笔，
                商户手续费总计：{{sum_merchant_fee}}元，
                渠道成本总计：{{sum_channel_fee}}元，
                代理商分润总计：{{sum_all_agentps_amt}}元，
                亏损金额总计：{{sum_loss_amt}}元，
                天下收益总计：{{sum_net_tfb_profit_amt}}元
                <span style="color: red;">（所有页面数据汇总）</span>
            </el-col>
        </el-row>
    </page>
</template>

<script>
    export default {
        name: "profit-summary-list",
        data() {
            return {
                form: {
                    tran_date_start: '',
                    tran_date_end: '',
                    busitypes_code: [],

                    agent_id: '',
                    agent_name: '',

                    merchant_id: '',
                    merchant_name: '',

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

                subsum_one_agentps_amt: '0.00',
                subsum_one_agent_other_amt: '0.00',
                subsum_two_agentps_amt: '0.00',
                subsum_two_agent_other_amt: '0.00',
                subsum_tran_amt: '0.00',
                subsum_tran_cnt: '0',
                subsum_merchant_fee: '0.00',
                subsum_channel_fee: '0.00',
                subsum_all_agentps_amt: '0.00',
                subsum_loss_amt: '0.00',
                subsum_net_tfb_profit_amt: '0.00',

                sum_one_agentps_amt: '0.00',
                sum_one_agent_other_amt: '0.00',
                sum_two_agentps_amt: '0.00',
                sum_two_agent_other_amt: '0.00',
                sum_tran_amt: '0.00',
                sum_tran_cnt: '0',
                sum_merchant_fee: '0.00',
                sum_channel_fee: '0.00',
                sum_all_agentps_amt: '0.00',
                sum_loss_amt: '0.00',
                sum_net_tfb_profit_amt: '0.00',

                start_date: '',
                end_date: '',
            }
        },
        methods: {
            search() {
                this.form.page = 1;
                this.getList();
            },
            getList() {
                api.get('profit/profitSummaryList', this.form).then(data => {
                    this.list = data.list;
                    this.total = data.total;
                    if (Object.keys(data.sum).length > 0) {
                        this.subsum_one_agentps_amt = parseFloat(data.sum.subsum_one_agentps_amt).toFixed(2);
                        this.subsum_one_agent_other_amt = parseFloat(data.sum.subsum_one_agent_other_amt).toFixed(2);
                        this.subsum_two_agentps_amt = parseFloat(data.sum.subsum_two_agentps_amt).toFixed(2);
                        this.subsum_two_agent_other_amt = parseFloat(data.sum.subsum_two_agent_other_amt).toFixed(2);
                        this.subsum_tran_amt = parseFloat(data.sum.subsum_tran_amt).toFixed(2);
                        this.subsum_tran_cnt = parseInt(data.sum.subsum_tran_cnt);
                        this.subsum_merchant_fee = parseFloat(data.sum.subsum_merchant_fee).toFixed(2);
                        this.subsum_channel_fee = parseFloat(data.sum.subsum_channel_fee).toFixed(2);
                        this.subsum_all_agentps_amt = parseFloat(data.sum.subsum_all_agentps_amt).toFixed(2);
                        this.subsum_loss_amt = parseFloat(data.sum.subsum_loss_amt).toFixed(2);
                        this.subsum_net_tfb_profit_amt = parseFloat(data.sum.subsum_net_tfb_profit_amt).toFixed(2);

                        this.sum_one_agentps_amt = parseFloat(data.sum.sum_one_agentps_amt).toFixed(2);
                        this.sum_one_agent_other_amt = parseFloat(data.sum.sum_one_agent_other_amt).toFixed(2);
                        this.sum_two_agentps_amt = parseFloat(data.sum.sum_two_agentps_amt).toFixed(2);
                        this.sum_two_agent_other_amt = parseFloat(data.sum.sum_two_agent_other_amt).toFixed(2);
                        this.sum_tran_amt = parseFloat(data.sum.sum_tran_amt).toFixed(2);
                        this.sum_tran_cnt = parseInt(data.sum.sum_tran_cnt);
                        this.sum_merchant_fee = parseFloat(data.sum.sum_merchant_fee).toFixed(2);
                        this.sum_channel_fee = parseFloat(data.sum.sum_channel_fee).toFixed(2);
                        this.sum_all_agentps_amt = parseFloat(data.sum.sum_all_agentps_amt).toFixed(2);
                        this.sum_loss_amt = parseFloat(data.sum.sum_loss_amt).toFixed(2);
                        this.sum_net_tfb_profit_amt = parseFloat(data.sum.sum_net_tfb_profit_amt).toFixed(2);
                    }

                    this.start_date = this.form.tran_date_start;
                    this.end_date = this.form.tran_date_end;
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
                api.get('profit/exportProfitSummary', this.form).then(data => {
                    window.open(data.list.url);
                })
            }
        },
        created() {
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
