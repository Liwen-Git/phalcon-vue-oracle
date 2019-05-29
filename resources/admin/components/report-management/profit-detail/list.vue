<template>
    <page title="利润明细">
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
                <el-col :span="12">
                    <el-form-item prop="profit_sum_id" label="利润汇总单号">
                        <el-input type="text" v-model="form.profit_sum_id" placeholder="请输入利润汇总单号" clearable></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="12">
                    <el-form-item prop="profit_detail_id" label="利润单号">
                        <el-input type="text" v-model="form.profit_detail_id" placeholder="请输入利润单号" clearable></el-input>
                    </el-form-item>
                </el-col>
            </el-row>

            <el-row>
                <el-col :span="14">
                    <el-form-item prop="order_id" label="交易单号">
                        <el-input type="text" v-model="form.order_id" placeholder="请输入交易单号" clearable></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="10" class="btn-class">
                    <el-form-item>
                        <el-button type="primary" @click="search">搜索</el-button>
                        <el-button type="success" @click="exportExcel">导出记录</el-button>
                    </el-form-item>
                </el-col>
            </el-row>
        </el-form>
        <el-table :data="list" stripe border>
            <el-table-column prop="tran_date" label="交易日期" width="100px"></el-table-column>
            <el-table-column prop="profit_sum_id" label="利润汇总单号"></el-table-column>
            <el-table-column prop="profit_detail_id" label="利润单号"></el-table-column>
            <el-table-column prop="order_id" label="交易单号"></el-table-column>
            <el-table-column prop="merchant_id" label="商户号"></el-table-column>
            <el-table-column prop="merchant_name" label="商户名称" show-overflow-tooltip></el-table-column>
            <el-table-column prop="busi_type" label="业务类型" show-overflow-tooltip></el-table-column>
            <el-table-column prop="sub_busi_type" label="业务方向"></el-table-column>
            <el-table-column prop="bank_name" label="银行名称"></el-table-column>
            <el-table-column prop="bank_card_type" label="借贷标识"></el-table-column>
            <el-table-column prop="tran_amt" label="交易金额(元)" width="100px">
                <template slot-scope="scope">
                    {{parseFloat(scope.row.tran_amt).toFixed(2)}}
                </template>
            </el-table-column>
            <el-table-column prop="chn_name" label="渠道"></el-table-column>
            <el-table-column prop="channel_fee" label="渠道成本">
                <template slot-scope="scope">
                    {{parseFloat(scope.row.channel_fee).toFixed(2)}}
                </template>
            </el-table-column>
            <el-table-column prop="merchant_fee" label="收入">
                <template slot-scope="scope">
                    {{parseFloat(scope.row.merchant_fee).toFixed(2)}}
                </template>
            </el-table-column>
            <el-table-column prop="one_agentps_amt" label="一级代理分润">
                <template slot-scope="scope">
                    {{parseFloat(scope.row.one_agentps_amt).toFixed(2)}}
                </template>
            </el-table-column>
            <el-table-column prop="two_agentps_amt" label="二级代理分润">
                <template slot-scope="scope">
                    {{parseFloat(scope.row.two_agentps_amt).toFixed(2)}}
                </template>
            </el-table-column>
            <el-table-column prop="loss_amt" label="亏损金额">
                <template slot-scope="scope">
                    {{parseFloat(scope.row.loss_amt).toFixed(2)}}
                </template>
            </el-table-column>
            <el-table-column prop="tfb_profit_amt" label="天下收益">
                <template slot-scope="scope">
                    {{parseFloat(scope.row.tfb_profit_amt).toFixed(2)}}
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
                交易总金额：10000.10元，
                渠道总成本：0元，
                收入总金额：0元，
                一级代理总分润：0元，
                二级代理总分润：0元，
                代理总分润：0元，
                天下总收益：0元
                <span style="color: red;">（所有页面数据汇总）</span>
            </el-col>
        </el-row>
    </page>
</template>

<script>
    export default {
        name: "profit-fail-detail-list",
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

                    profit_sum_id: '',
                    profit_detail_id: '',

                    order_id: '',

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
                sum_channel_fee: '0.00',
                sum_merchant_fee: '0.00',
                sum_one_agentps_amt: '0.00',
                sum_two_agentps_amt: '0.00',
                sum_all_agentps_amt: '0.00',
                sum_tfb_profit_amt: '0.00',
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
                        this.sum_channel_fee = parseFloat(data.sum.sum_channel_fee).toFixed(2);
                        this.sum_merchant_fee = parseFloat(data.sum.sum_merchant_fee).toFixed(2);
                        this.sum_one_agentps_amt = parseFloat(data.sum.sum_one_agentps_amt).toFixed(2);
                        this.sum_two_agentps_amt = parseFloat(data.sum.sum_two_agentps_amt).toFixed(2);
                        this.sum_all_agentps_amt = parseFloat(data.sum.sum_all_agentps_amt).toFixed(2);
                        this.sum_tfb_profit_amt = parseFloat(data.sum.sum_tfb_profit_amt).toFixed(2);
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
