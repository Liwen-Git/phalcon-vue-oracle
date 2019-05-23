<template>
    <page title="明细账">
        <el-form :model="form" ref="form" size="small" inline>
            <el-form-item prop="date" label="日期">
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
            <el-form-item prop="subject_level" label="科目级别">
                <el-select v-model="form.subject_level" @change="getParentSubject">
                    <el-option label="一级科目" value="1"></el-option>
                    <el-option label="二级科目" value="2"></el-option>
                    <el-option label="三级科目" value="3"></el-option>
                    <el-option label="四级科目" value="4"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item prop="subject_code" label="科目类型">
                <el-select v-model="form.subject_code">
                    <el-option v-for="(item, index) in subjectOptions" :key="index" :label="item.code + item.name" :value="item.code"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="search">查询</el-button>
            </el-form-item>
        </el-form>
        <el-table :data="list" stripe>
            <el-table-column prop="tck_date" label="日期">
                <template slot-scope="scope">
                    {{new Date(scope.row.tck_date).getFullYear() + '-' + (new Date(scope.row.tck_date).getMonth() + 1) + '-' + new Date(scope.row.tck_date).getDate()}}
                </template>
            </el-table-column>
            <el-table-column prop="tck_inc" label="凭证号"></el-table-column>
            <el-table-column prop="subject_name" label="科目名称"></el-table-column>
            <el-table-column prop="subject_code" label="科目代码"></el-table-column>
            <el-table-column prop="memo" label="摘要"></el-table-column>
            <el-table-column label="上笔余额">
                <el-table-column prop="last_deposit_amt" label="借方">
                    <template slot-scope="scope">
                        {{(scope.row.last_deposit_amt / 100).toFixed(2)}}
                    </template>
                </el-table-column>
                <el-table-column prop="last_credit_amt" label="贷方">
                    <template slot-scope="scope">
                        {{(scope.row.last_credit_amt / 100).toFixed(2)}}
                    </template>
                </el-table-column>
            </el-table-column>
            <el-table-column prop="occur_deposit_amt" label="借方金额">
                <template slot-scope="scope">
                    {{(scope.row.occur_deposit_amt / 100).toFixed(2)}}
                </template>
            </el-table-column>
            <el-table-column prop="occur_credit_amt" label="贷方金额">
                <template slot-scope="scope">
                    {{(scope.row.occur_credit_amt / 100).toFixed(2)}}
                </template>
            </el-table-column>
            <el-table-column label="余额">
                <el-table-column prop="curr_deposit_amt" label="借方">
                    <template slot-scope="scope">
                        {{(scope.row.curr_deposit_amt / 100).toFixed(2)}}
                    </template>
                </el-table-column>
                <el-table-column prop="cur_credit_amt" label="贷方">
                    <template slot-scope="scope">
                        {{(scope.row.cur_credit_amt / 100).toFixed(2)}}
                    </template>
                </el-table-column>
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
    </page>
</template>

<script>
    export default {
        name: "subsidiary-ledger-list",
        data() {
            return {
                form: {
                    query_start_date: '',
                    query_end_date: '',
                    subject_level: '',
                    subject_code: '',

                    page: 1,
                    pageSize: 10,
                },
                list: [],
                total: 0,

                subjectOptions: [],
            }
        },
        methods: {
            search() {
                this.form.page = 1;
                this.getList();
            },
            getList() {
                api.get('ledger/subsidiaryLedgerList', this.form).then(data => {
                    this.list = data.list;
                    this.total = data.total;
                })
            },
            changePageSize(pageSize){
                this.form.page = 1;
                this.form.pageSize = pageSize;
                this.getList();
            },
            getParentSubject(val) {
                this.form.subject_code = '';
                api.get('subject/getParentSubject', {level: parseInt(val) + 1}).then(data => {
                    this.subjectOptions = data.data;
                })
            }
        },
        created() {
            this.form.query_start_date = moment().subtract(1, 'days').format("YYYY-MM-DD");
            this.form.query_end_date = moment().subtract(1, 'days').format("YYYY-MM-DD");
            this.getList();
        }
    }
</script>

<style scoped>

</style>
