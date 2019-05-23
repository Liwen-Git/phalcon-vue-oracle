<template>
    <page title="序时账">
        <el-form :model="form" ref="form" size="small" inline>
            <el-form-item prop="subject_code" label="科目">
                <el-cascader
                        v-model="form.subject_code"
                        placeholder="请选择(可搜索)"
                        :options="subjectArr"
                        :props="{value: 'subject_code', label: 'code_name', children: 'child'}"
                        change-on-select
                        filterable
                        clearable
                        class="w-300"
                ></el-cascader>
            </el-form-item>
            <el-form-item prop="chn_name" label="渠道名称">
                <el-input type="text" v-model="form.chn_name" placeholder="请输入渠道名称" clearable></el-input>
            </el-form-item>
            <el-form-item prop="merchant_id" label="商户编号">
                <el-input type="text" v-model="form.merchant_id" placeholder="请输入商户编号" clearable></el-input>
            </el-form-item>
            <el-form-item prop="agent_id" label="代理商编号">
                <el-input type="text" v-model="form.agent_id" placeholder="请输入代理商编号" clearable></el-input>
            </el-form-item>
            <el-form-item label="记账时间">
                <el-date-picker
                        v-model="form.query_start_date"
                        type="date"
                        placeholder="请选择日期"
                        format="yyyy 年 MM 月 dd 日"
                        value-format="yyyy-MM-dd"
                ></el-date-picker>
                <span> - </span>
                <el-date-picker
                        v-model="form.query_end_date"
                        type="date"
                        placeholder="请选择结束日期"
                        format="yyyy 年 MM 月 dd 日"
                        value-format="yyyy-MM-dd"
                        :picker-options="{disabledDate: (time) => {return time.getTime() < new Date(form.query_start_date)}}"
                ></el-date-picker>
            </el-form-item>
            <el-form-item prop="group_related" label="备注">
                <el-select v-model="form.group_related" placeholder="全部" clearable>
                    <el-option label="0" value="0"></el-option>
                    <el-option label="1" value="1"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="search">查询</el-button>
            </el-form-item>
        </el-form>
        <el-table :data="list" stripe>
            <el-table-column prop="tck_date" label="记账时间"></el-table-column>
            <el-table-column prop="subject_name" label="科目名称"></el-table-column>
            <el-table-column prop="subject_code" label="科目代码"></el-table-column>
            <el-table-column prop="agent_id" label="代理商编号"></el-table-column>
            <el-table-column prop="agent_name" label="代理商名称"></el-table-column>
            <el-table-column prop="chn_name" label="渠道编号"></el-table-column>
            <el-table-column prop="merchant_id" label="商户号"></el-table-column>
            <el-table-column prop="tck_inc" label="凭证号"></el-table-column>
            <el-table-column prop="order_id" label="订单号"></el-table-column>
            <el-table-column prop="acc_inc" label="流水号"></el-table-column>
            <el-table-column prop="acc_direction" label="发生额方向">
                <template slot-scope="scope">
                    {{scope.row.acc_direction == "C" ? '贷方' : '借方'}}
                </template>
            </el-table-column>
            <el-table-column prop="acc_amount" label="金额（元）">
                <template slot-scope="scope">
                    {{(scope.row.acc_amount / 100).toFixed(2)}}
                </template>
            </el-table-column>
            <el-table-column prop="trade_time" label="交易时间"></el-table-column>
            <el-table-column prop="merchant_name" label="客户名称"></el-table-column>
            <el-table-column prop="busi_type" label="业务大类">
                <template slot-scope="scope">
                    {{allBizTypes.hasOwnProperty(scope.row.busi_type) ? allBizTypes[scope.row.busi_type] : 'other'}}
                </template>
            </el-table-column>
            <el-table-column prop="memo" label="摘要"></el-table-column>
            <el-table-column prop="group_related" label="备注"></el-table-column>
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
    </page>
</template>

<script>
    export default {
        name: "subsidiary-ledger-list",
        data() {
            return {
                form: {
                    subject_code: [],
                    chn_name: '',
                    agent_id: '',
                    merchant_id: '',
                    query_start_date: '',
                    query_end_date: '',
                    group_related: '',

                    page: 1,
                    pageSize: 10,
                },
                list: [],
                total: 0,

                subjectArr: [],
                allBizTypes: [],
            }
        },
        methods: {
            search() {
                this.form.page = 1;
                this.getList();
            },
            getList() {
                api.get('ledger/journalLedgerList', this.form).then(data => {
                    this.list = data.list;
                    this.total = data.total;
                })
            },
            changePageSize(pageSize){
                this.form.page = 1;
                this.form.pageSize = pageSize;
                this.getList();
            },
            initPage() {
                api.get('ledger/subjectSelect').then(data => {
                    this.subjectArr = data;
                }).then(() => {
                    api.get('business_type/getAllBizTypes').then(data => {
                        this.allBizTypes = data;
                        this.getList();
                    });
                })
            }
        },
        created() {
            this.form.query_start_date = moment().subtract(1, 'days').format("YYYY-MM-DD");
            this.form.query_end_date = moment().subtract(1, 'days').format("YYYY-MM-DD");
            this.initPage();
        }
    }
</script>

<style scoped>

</style>
