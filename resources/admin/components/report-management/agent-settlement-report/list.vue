<template>
    <page title="代理商结算报表">
        <el-form :model="form" ref="form" size="small" inline label-width="150px">
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
            <el-form-item prop="agent_id" label="代理商号">
                <el-input type="text" v-model="form.agent_id" placeholder="请输入代理商号" clearable></el-input>
            </el-form-item>
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
            <el-form-item>
                <el-button type="primary" @click="search">搜索</el-button>
                <el-button type="success" @click="exportExcel">导出记录</el-button>
            </el-form-item>
        </el-form>
        <el-table :data="list" stripe border>
            <el-table-column prop="trade_date" label="交易日期区间" show-overflow-tooltip>
                <template slot-scope="scope">
                    {{start_date}} - {{end_date}}
                </template>
            </el-table-column>
            <el-table-column prop="agent_id" label="代理商号"></el-table-column>
            <el-table-column prop="agent_name" label="代理商名称" show-overflow-tooltip></el-table-column>
            <el-table-column prop="agent_level" label="代理商级别" show-overflow-tooltip>
                <template slot-scope="scope">
                    <span v-if="scope.row.agent_level == 1">一级代理</span>
                    <span v-else-if="scope.row.agent_level == 2">二级代理</span>
                    <span v-else></span>
                </template>
            </el-table-column>
            <el-table-column prop="merchant_num" label="商户数量">
                <template slot-scope="scope">
                    <el-button type="text" @click="jumpTo(scope.row)">{{scope.row.merchant_num}}</el-button>
                </template>
            </el-table-column>
            <el-table-column prop="second_busi_name" label="二级业务类型"></el-table-column>
            <el-table-column prop="busi_direction" label="业务方向">
                <template slot-scope="scope">
                    <span v-if="scope.row.busi_direction == '01'">消费</span>
                    <span v-else-if="scope.row.busi_direction == '02'">退货</span>
                    <span v-else>other</span>
                </template>
            </el-table-column>
            <el-table-column prop="tran_amt" label="交易金额">
                <template slot-scope="scope">
                    {{parseFloat(scope.row.tran_amt).toFixed(2)}}
                </template>
            </el-table-column>
            <el-table-column prop="tran_cnt" label="交易笔数"></el-table-column>
            <el-table-column prop="agentps_amt" label="代理商分润">
                <template slot-scope="scope">
                    {{parseFloat(scope.row.agentps_amt).toFixed(2)}}
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
                交易笔数小计：{{subsum_tran_cnt}}笔，
                代理商分润小计：{{subsum_agentps_amt}}元
                <span style="color: red;">（当前页面数据汇总）</span>
            </el-col>
            <el-col style="margin-top: 10px">
                交易金额总计：{{sum_tran_amt}}元，
                交易笔数总计：{{sum_tran_cnt}}笔，
                代理商分润总计：{{sum_agentps_amt}}元
                <span style="color: red;">（所有页面数据汇总）</span>
            </el-col>
        </el-row>
    </page>
</template>

<script>
    export default {
        name: "agent-settlement-report-list",
        data() {
            return {
                form: {
                    tran_date_start: '',
                    tran_date_end: '',
                    agent_id: '',
                    agent_name: '',
                    busitypes_code: [],

                    page: 1,
                    pageSize: 10,
                },
                total: 10,
                list: [],

                agentOptions: [],
                theAgentNameLoading: false,

                businessTypes: [],

                sum_tran_amt: '0.00',
                sum_tran_cnt: '0',
                sum_agentps_amt: '0.00',

                subsum_tran_amt: '0.00',
                subsum_tran_cnt: '0',
                subsum_agentps_amt: '0.00',

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
                api.get('report_of_agent/agentSettlementReportList', this.form).then(data => {
                    this.list = data.list;
                    this.total = data.total;
                    if (Object.keys(data.sum).length > 0) {
                        this.sum_tran_amt = parseFloat(data.sum.sum_tran_amt).toFixed(2);
                        this.sum_tran_cnt = parseInt(data.sum.sum_tran_cnt);
                        this.sum_agentps_amt = parseFloat(data.sum.sum_agentps_amt).toFixed(2);

                        this.subsum_tran_amt = parseFloat(data.sum.subsum_tran_amt).toFixed(2);
                        this.subsum_tran_cnt = parseInt(data.sum.subsum_tran_cnt);
                        this.subsum_agentps_amt = parseFloat(data.sum.subsum_agentps_amt).toFixed(2);
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
            exportExcel() {
                api.get('report_of_agent/exportAgentSettlementReport', this.form).then(data => {
                    window.open(data.list.url);
                })
            },
            jumpTo(row) {
                this.$router.push({
                    path: '/report/qryagentpslist',
                    query: {
                        agent_id: row.agent_id,
                        tran_date_start: this.start_date,
                        tran_date_end: this.end_date,
                    }
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
