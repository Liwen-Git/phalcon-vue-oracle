<template>
    <page title="总账">
        <el-form :model="form" ref="form" size="small" inline>
            <el-form-item prop="date" label="日期">
                <el-date-picker
                        v-model="form.date"
                        type="date"
                        placeholder="请选择日期"
                        format="yyyy 年 MM 月 dd 日"
                        value-format="yyyy-MM-dd"
                ></el-date-picker>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="search">查询</el-button>
            </el-form-item>
        </el-form>
        <el-table :data="list" stripe border>
            <el-table-column prop="ctime" label="日期">
                <template slot-scope="scope">
                    {{theDate}}
                </template>
            </el-table-column>
            <el-table-column prop="subject_name" label="科目名称"></el-table-column>
            <el-table-column prop="subject_code" label="科目代码"></el-table-column>
            <el-table-column label="期初余额">
                <el-table-column prop="last_deposit_amt" label="借方">
                    <template slot-scope="scope">
                        {{(scope.row.last_deposit_amt/100).toFixed(2)}}
                    </template>
                </el-table-column>
                <el-table-column prop="last_credit_amt" label="贷方">
                    <template slot-scope="scope">
                        {{(scope.row.last_credit_amt/100).toFixed(2)}}
                    </template>
                </el-table-column>
            </el-table-column>
            <el-table-column label="本期发生额">
                <el-table-column prop="occur_deposit_amt" label="借方">
                    <template slot-scope="scope">
                        {{(scope.row.occur_deposit_amt/100).toFixed(2)}}
                    </template>
                </el-table-column>
                <el-table-column prop="occur_credit_amt" label="贷方">
                    <template slot-scope="scope">
                        {{(scope.row.occur_credit_amt/100).toFixed(2)}}
                    </template>
                </el-table-column>
            </el-table-column>
            <el-table-column label="期末余额">
                <el-table-column prop="curr_deposit_amt" label="借方">
                    <template slot-scope="scope">
                        {{(scope.row.curr_deposit_amt/100).toFixed(2)}}
                    </template>
                </el-table-column>
                <el-table-column prop="cur_credit_amt" label="贷方">
                    <template slot-scope="scope">
                        {{(scope.row.cur_credit_amt/100).toFixed(2)}}
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
        name: "general-ledger-list",
        data() {
            return {
                form: {
                    date: '',

                    page: 1,
                    pageSize: 10,
                },
                list: [],
                total: 0,

                theDate: '',
            }
        },
        methods: {
            search() {
                this.form.page = 1;
                this.getList();
            },
            getList() {
                this.theDate = _.clone(this.form.date);
                api.get('ledger/ledgerList', this.form).then(data => {
                    this.list = data.list;
                    this.total = data.total;
                })
            },
            changePageSize(pageSize){
                this.form.page = 1;
                this.form.pageSize = pageSize;
                this.getList();
            }
        },
        created() {
            this.form.date = moment().subtract(1, 'days').format("YYYY-MM-DD");
            this.getList();
        }
    }
</script>

<style scoped>

</style>
