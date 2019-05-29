<template>
    <page title="手工记账管理">
        <el-form :model="form" ref="form" size="small" inline>
            <el-form-item prop="acc_inc" label="流水号">
                <el-input type="text" v-model="form.acc_inc" placeholder="请输入流水号" clearable></el-input>
            </el-form-item>
            <el-form-item label="记账时间">
                <el-date-picker
                        v-model="form.query_start_date"
                        type="date"
                        placeholder="选择日期"
                        format="yyyy 年 MM 月 dd 日"
                        value-format="yyyy-MM-dd"
                ></el-date-picker>
                <span> - </span>
                <el-date-picker
                        v-model="form.query_end_date"
                        type="date"
                        placeholder="选择日期"
                        format="yyyy 年 MM 月 dd 日"
                        value-format="yyyy-MM-dd"
                        :picker-options="{disabledDate: (time) => {return time.getTime() < new Date(form.query_start_date)}}"
                ></el-date-picker>
            </el-form-item>
            <el-form-item prop="second_busi_type" label="记账类型">
                <el-select v-model="form.second_busi_type" placeholder="全部" clearable>
                    <el-option label="记账（余额）-长款" value="01"></el-option>
                    <el-option label="记账（余额）-短款" value="02"></el-option>
                    <el-option label="记账（保证金）" value="03"></el-option>
                    <el-option label="记账（预存款）" value="04"></el-option>
                    <el-option label="其它" value="05"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item prop="state" label="状态">
                <el-select v-model="form.state" placeholder="全部" clearable>
                    <el-option label="录入待审核" value="1"></el-option>
                    <el-option label="审核通过" value="2"></el-option>
                    <el-option label="审核拒绝" value="3"></el-option>
                    <el-option label="记账成功" value="4"></el-option>
                    <el-option label="记账失败" value="5"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="search">查询</el-button>
            </el-form-item>
            <el-button class="fr" size="small" type="success" @click="addDialog = true">添加</el-button>
        </el-form>
        <el-table :data="list" stripe border>
            <el-table-column prop="acc_inc" label="流水号"></el-table-column>
            <el-table-column prop="acc_amount" label="记账金额">
                <template slot-scope="scope">
                    {{(scope.row.acc_amount / 100).toFixed(2)}}
                </template>
            </el-table-column>
            <el-table-column prop="second_busi_name" label="记账类型"></el-table-column>
            <el-table-column prop="state" label="状态">
                <template slot-scope="scope">
                    <span v-if="scope.row.state == 1">录入待审核</span>
                    <span v-else-if="scope.row.state == 2">审核通过</span>
                    <span v-else-if="scope.row.state == 3">审核拒绝</span>
                    <span v-else-if="scope.row.state == 4">记账成功</span>
                    <span v-else-if="scope.row.state == 5">记账失败</span>
                    <span v-else>未知({{scope.row.state}})</span>
                </template>
            </el-table-column>
            <el-table-column prop="acc_time" label="记账时间"></el-table-column>
            <el-table-column prop="remark" label="备注"></el-table-column>
            <el-table-column prop="oper_name" label="录入操作员"></el-table-column>
            <el-table-column prop="verify_name" label="审核操作员"></el-table-column>
            <el-table-column label="操作">
                <template slot-scope="scope">
                    <el-button type="text" @click="audit(scope.row.acc_inc)">审核</el-button>
                </template>
            </el-table-column>
        </el-table>
        <el-pagination
                layout="sizes, total, prev, pager, next"
                :page-size="form.page_size"
                :total="total"
                :current-page.sync="form.page"
                @current-change="getList"
                :page-sizes="[10, 50, 100, 200]"
                @size-change="changePageSize"
        ></el-pagination>

        <el-dialog title="手工记账录入" :visible.sync="addDialog" :close-on-click-modal="false" :show-close="false">
            <manual-add @close="addDialog = false" @addSuccess="theSuccess"></manual-add>
        </el-dialog>

        <el-dialog title="手工记账审核" :visible.sync="auditDialog" :close-on-click-modal="false">
            <manual-audit :theAccInc="theAccInc" @close="auditDialog = false" @success="theSuccess"></manual-audit>
        </el-dialog>
    </page>
</template>

<script>
    import ManualAdd from './add';
    import ManualAudit from './audit';

    export default {
        name: "query-manual-list",
        data() {
            return {
                form: {
                    acc_inc: '',
                    query_start_date: '',
                    query_end_date: '',
                    second_busi_type: '',
                    state: '',

                    page: 1,
                    page_size: 10,
                },
                total: 0,
                list: [],

                auditDialog: false,
                theAccInc: '',

                addDialog: false,
            }
        },
        methods: {
            getList() {
                api.get('balance/queryManualList', this.form).then(data => {
                    this.list = data.list;
                    this.total = data.total;
                })
            },
            search() {
                this.form.page = 1;
                this.getList();
            },
            changePageSize(size) {
                this.form.page = 1;
                this.form.page_size = size;
                this.getList();
            },
            audit(inc) {
                this.theAccInc = inc;
                this.auditDialog = true;
            },
            theSuccess() {
                this.form.page = 1;
                this.getList();
            }
        },
        created() {
            this.form.query_start_date = moment().format("YYYY-MM-DD");
            this.form.query_end_date = moment().add(1, 'month').format("YYYY-MM-DD");
            this.getList();
        },
        components: {
            ManualAdd,
            ManualAudit,
        }
    }
</script>

<style scoped>

</style>
