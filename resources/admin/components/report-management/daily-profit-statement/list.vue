<template>
    <page title="日利润报表">
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
                <el-col :span="10" class="btn-class">
                    <el-form-item>
                        <el-button type="primary" @click="search">搜索</el-button>
                        <el-button type="success" @click="exportExcel">导出记录</el-button>
                    </el-form-item>
                </el-col>
            </el-row>
        </el-form>
        <el-table :data="list" stripe border>
            <el-table-column prop="tran_date" label="交易日期区间" show-overflow-tooltip>
                <template slot-scope="scope">
                    {{start_date}} - {{end_date}}
                </template>
            </el-table-column>
            <el-table-column prop="busi_name" label="一级业务类型" show-overflow-tooltip></el-table-column>
            <el-table-column prop="second_busi_name" label="二级业务类型" show-overflow-tooltip></el-table-column>
            <el-table-column prop="busi_direction" label="业务方向" show-overflow-tooltip>
                <template slot-scope="scope">
                    <span v-if="scope.row.busi_direction == '01'">消费</span>
                    <span v-else-if="scope.row.busi_direction == '02'">退货</span>
                    <span v-else>other</span>
                </template>
            </el-table-column>
            <el-table-column prop="tran_amt" label="交易金额" show-overflow-tooltip>
                <template slot-scope="scope">
                    {{parseFloat(scope.row.tran_amt).toFixed(2)}}
                </template>
            </el-table-column>
            <el-table-column prop="tran_cnt" label="交易笔数" show-overflow-tooltip></el-table-column>
            <el-table-column prop="merchant_fee" label="商户手续费" show-overflow-tooltip>
                <template slot-scope="scope">
                    {{parseFloat(scope.row.merchant_fee).toFixed(2)}}
                </template>
            </el-table-column>
            <el-table-column prop="channel_fee" label="渠道成本" show-overflow-tooltip>
                <template slot-scope="scope">
                    {{parseFloat(scope.row.channel_fee).toFixed(2)}}
                </template>
            </el-table-column>
            <el-table-column prop="tfb_profit_amt" label="利润" show-overflow-tooltip>
                <template slot-scope="scope">
                    {{parseFloat(scope.row.tfb_profit_amt).toFixed(2)}}
                </template>
            </el-table-column>
            <el-table-column prop="all_agentps_amt" label="代理商分润">
                <template slot-scope="scope">
                    {{parseFloat(scope.row.all_agentps_amt).toFixed(2)}}
                </template>
            </el-table-column>
            <el-table-column prop="net_tfb_profit_amt" label="净利润">
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
                交易金额小计：{{subsum_tran_amt}}元，
                交易总笔数小计：{{subsum_tran_cnt}}笔，
                渠道成本小计：{{subsum_channel_fee}}元，
                商户手续费小计：{{subsum_merchant_fee}}元，
                利润小计：{{subsum_tfb_profit_amt}}元，
                代理商分润小计：{{subsum_all_agentps_amt}}元，
                净分润小计：{{subsum_net_tfb_profit_amt}}元
                <span style="color: red;">（当前页面数据汇总）</span>
            </el-col>
            <el-col style="margin-top: 10px">
                交易金额总计：{{sum_tran_amt}}元，
                交易总笔数：{{sum_tran_cnt}}笔，
                渠道成本总计：{{sum_channel_fee}}元，
                商户手续费总计：{{sum_merchant_fee}}元，
                利润总计：{{sum_tfb_profit_amt}}元，
                代理商分润总计：{{sum_all_agentps_amt}}元，
                净分润总计：{{sum_net_tfb_profit_amt}}元
                <span style="color: red;">（所有页面数据汇总）</span>
            </el-col>
        </el-row>
    </page>
</template>

<script>
    export default {
        name: "profit-statement-list",
        data() {
            return {
                form: {
                    tran_date_start: '',
                    tran_date_end: '',
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

                subsum_tran_amt: '0.00',
                subsum_tran_cnt: '0',
                subsum_channel_fee: '0.00',
                subsum_merchant_fee: '0.00',
                subsum_tfb_profit_amt: '0.00',
                subsum_all_agentps_amt: '0.00',
                subsum_net_tfb_profit_amt: '0.00',

                sum_tran_amt: '0.00',
                sum_tran_cnt: '0',
                sum_channel_fee: '0.00',
                sum_merchant_fee: '0.00',
                sum_tfb_profit_amt: '0.00',
                sum_all_agentps_amt: '0.00',
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
                api.get('profit/dailyProfitList', this.form).then(data => {
                    this.list = data.list;
                    this.total = data.total;
                    if (Object.keys(data.sum).length > 0) {
                        this.subsum_tran_amt = parseFloat(data.sum.subsum_tran_amt).toFixed(2);
                        this.subsum_tran_cnt = parseInt(data.sum.subsum_tran_cnt);
                        this.subsum_channel_fee = parseFloat(data.sum.subsum_channel_fee).toFixed(2);
                        this.subsum_merchant_fee = parseFloat(data.sum.subsum_merchant_fee).toFixed(2);
                        this.subsum_tfb_profit_amt = parseFloat(data.sum.subsum_tfb_profit_amt).toFixed(2);
                        this.subsum_all_agentps_amt = parseFloat(data.sum.subsum_all_agentps_amt).toFixed(2);
                        this.subsum_net_tfb_profit_amt = parseFloat(data.sum.subsum_net_tfb_profit_amt).toFixed(2);

                        this.sum_tran_amt = parseFloat(data.sum.sum_tran_amt).toFixed(2);
                        this.sum_tran_cnt = parseInt(data.sum.sum_tran_cnt);
                        this.sum_channel_fee = parseFloat(data.sum.sum_channel_fee).toFixed(2);
                        this.sum_merchant_fee = parseFloat(data.sum.sum_merchant_fee).toFixed(2);
                        this.sum_tfb_profit_amt = parseFloat(data.sum.sum_tfb_profit_amt).toFixed(2);
                        this.sum_all_agentps_amt = parseFloat(data.sum.sum_all_agentps_amt).toFixed(2);
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
            exportExcel() {
                api.post('profit/exportDailyProfit', this.form).then(data => {
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
