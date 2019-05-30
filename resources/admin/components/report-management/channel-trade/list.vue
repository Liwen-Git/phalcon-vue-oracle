<template>
    <page title="渠道交易报表">
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
            <el-form-item prop="sys_chl_code" label="渠道编号">
                <el-input type="text" v-model="form.chl_name" placeholder="请输入渠道编号" clearable></el-input>
            </el-form-item>
            <el-form-item prop="chl_name" label="渠道名称">
                <el-select
                        v-model="form.sys_chl_code"
                        filterable
                        remote
                        clearable
                        placeholder="请输入渠道关键字"
                        :remote-method="searchChannel">
                    <el-option v-for="(opt, i) in channelOptions" :key="i" :label="opt.acc_name" :value="opt.acc_name"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item prop="channel_merchant_id" label="渠道商户号">
                <el-input type="text" v-model="form.channel_merchant_id" placeholder="请输入渠道商户号" clearable></el-input>
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
            <el-form-item prop="bank_card_type" label="借贷标识">
                <el-select v-model="form.bank_card_type" placeholder="全部" clearable>
                    <el-option label="借记卡" value="1"></el-option>
                    <el-option label="贷记卡" value="2"></el-option>
                    <el-option label="不限卡种" value="3"></el-option>
                    <el-option label="准贷记卡" value="5"></el-option>
                </el-select>
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
            <el-table-column prop="chn_name" label="渠道编号"></el-table-column>
            <el-table-column prop="sys_chl_code" label="渠道名称"></el-table-column>
            <el-table-column prop="channel_merchant_id" label="渠道商户号"></el-table-column>
            <el-table-column prop="second_busi_type" label="业务类型"></el-table-column>
            <el-table-column prop="busi_direction" label="业务方向">
                <template slot-scope="scope">
                    <span v-if="scope.row.busi_direction == '01'">消费</span>
                    <span v-else-if="scope.row.busi_direction == '02'">退货</span>
                    <span v-else>other</span>
                </template>
            </el-table-column>
            <el-table-column prop="bank_card_type" label="借贷标识"></el-table-column>
            <el-table-column prop="interval_amt" label="单笔交易金额范围"></el-table-column>
            <el-table-column prop="tran_amt" label="交易金额">
                <template slot-scope="scope">
                    {{parseFloat(scope.row.tran_amt).toFixed(2)}}
                </template>
            </el-table-column>
            <el-table-column prop="tran_cnt" label="交易笔数"></el-table-column>
            <el-table-column prop="all_rate" label="费率"></el-table-column>
            <el-table-column prop="channel_fee" label="渠道成本">
                <template slot-scope="scope">
                    {{parseFloat(scope.row.channel_fee).toFixed(2)}}
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
                渠道成本小计：{{subsum_channel_fee}}元
                <span style="color: red;">（当前页面数据汇总）</span>
            </el-col>
            <el-col style="margin-top: 10px">
                交易金额总计：{{sum_tran_amt}}元，
                交易笔数总计：{{sum_tran_cnt}}笔，
                渠道成本总计：{{sum_channel_fee}}元
                <span style="color: red;">（所有页面数据汇总）</span>
            </el-col>
        </el-row>
    </page>
</template>

<script>
    export default {
        name: "channel-trade-list",
        data() {
            return {
                form: {
                    tran_date_start: '',
                    tran_date_end: '',
                    chn_name: '',
                    sys_chl_code: '',
                    channel_merchant_id: '',
                    busitypes_code: [],
                    bank_card_type: '',

                    page: 1,
                    pageSize: 10,
                },
                total: 10,
                list: [],

                channelOptions: [],

                businessTypes: [],

                sum_tran_amt: '0.00',
                sum_tran_cnt: '0',
                sum_channel_fee: '0.00',

                subsum_tran_amt: '0.00',
                subsum_tran_cnt: '0',
                subsum_channel_fee: '0.00',

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
                api.get('channel/channelTradeList', this.form).then(data => {
                    this.list = data.list;
                    this.total = data.total;
                    if (Object.keys(data.sum).length > 0) {
                        this.sum_tran_amt = parseFloat(data.sum.sum_tran_amt).toFixed(2);
                        this.sum_tran_cnt = parseInt(data.sum.sum_tran_cnt);
                        this.sum_channel_fee = parseFloat(data.sum.sum_channel_fee).toFixed(2);

                        this.subsum_tran_amt = parseFloat(data.sum.subsum_tran_amt).toFixed(2);
                        this.subsum_tran_cnt = parseInt(data.sum.subsum_tran_cnt);
                        this.subsum_channel_fee = parseFloat(data.sum.subsum_channel_fee).toFixed(2);
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
            searchChannel(query) {
                if (query !== '') {
                    setTimeout(() => {
                        api.get('channel/queryChannelInfo', {acc_name: query}).then(data => {
                            this.channelOptions = data.list;
                        })
                    }, 200);
                } else {
                    this.channelOptions = [];
                }
            },
            exportExcel() {
                api.get('channel/exportChannelTrade', this.form).then(data => {
                    window.open(data.list.url);
                })
            },
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
