<template>
    <page title="数据报表修正">
        <el-form :model="form" ref="form" size="small" inline label-width="150px">
            <el-row>
                <el-col :span="12">
                    <el-form-item label="交易日期">
                        <el-date-picker
                                v-model="form.trade_begin_time"
                                type="date"
                                placeholder="请选择开始日期"
                                format="yyyy 年 MM 月 dd 日"
                                value-format="yyyy-MM-dd"
                        ></el-date-picker>
                        <span> - </span>
                        <el-date-picker
                                v-model="form.trade_end_time"
                                type="date"
                                placeholder="请选择结束日期"
                                format="yyyy 年 MM 月 dd 日"
                                value-format="yyyy-MM-dd"
                                :picker-options="{disabledDate: (time) => {return time.getTime() < new Date(form.trade_begin_time)}}"
                        ></el-date-picker>
                    </el-form-item>
                </el-col>
                <el-col :span="12">
                    <el-form-item prop="merchant_id" label="商户编号">
                        <el-input type="text" v-model="form.merchant_id" placeholder="请输入商户编号" clearable></el-input>
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
                    <el-form-item prop="agentps_sum_id" label="分润汇总单号">
                        <el-input type="text" v-model="form.agentps_sum_id" placeholder="请输入分润汇总单号" clearable></el-input>
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
                    <el-form-item prop="redo_state" label="重跑状态">
                        <el-select v-model="form.redo_state" placeholder="全部" clearable>
                            <el-option label="重跑成功" value="2"></el-option>
                            <el-option label="重跑中" value="1"></el-option>
                            <el-option label="重跑失败" value="3"></el-option>
                        </el-select>
                    </el-form-item>
                </el-col>
            </el-row>
            <el-row>
                <el-col :span="10" class="btn-class">
                    <el-form-item>
                        <el-button type="primary" @click="search">查询</el-button>
                        <el-button type="success" @click="redo">重跑分润</el-button>
                    </el-form-item>
                </el-col>
            </el-row>
        </el-form>

        <el-table :data="list" stripe border @selection-change="handleSelectionChange">
            <el-table-column type="selection"></el-table-column>
            <el-table-column prop="trade_date" label="交易日期区间" show-overflow-tooltip>
                <template slot-scope="scope">
                    {{scope.row.trade_begin_time}}  -  {{scope.row.trade_end_time}}
                </template>
            </el-table-column>
            <el-table-column prop="sum_id" label="利润汇总单号" show-overflow-tooltip></el-table-column>
            <el-table-column prop="agent_id" label="代理商号"></el-table-column>
            <el-table-column prop="agent_name" label="代理商名称" show-overflow-tooltip></el-table-column>
            <el-table-column prop="merchant_id" label="商户号"></el-table-column>
            <el-table-column prop="merchant_name" label="商户名称" show-overflow-tooltip></el-table-column>
            <el-table-column prop="second_busi_name" label="业务类型" show-overflow-tooltip></el-table-column>
            <el-table-column prop="busi_direction" label="业务方向">
                <template slot-scope="scope">
                    <span v-if="scope.row.busi_direction == '01'">消费</span>
                    <span v-else-if="scope.row.busi_direction == '02'">退货</span>
                    <span v-else>other</span>
                </template>
            </el-table-column>
            <el-table-column prop="chl_name" label="渠道名称"></el-table-column>
            <el-table-column prop="order_type" label="订单类型">
                <template slot-scope="scope">
                    <span v-if="scope.row.order_type == 0">正常单</span>
                    <span v-else-if="scope.row.order_type == 1">异常单</span>
                    <span v-else></span>
                </template>
            </el-table-column>
            <el-table-column prop="order_id" label="天下单号" show-overflow-tooltip></el-table-column>
            <el-table-column prop="redo_state" label="重跑状态">
                <template slot-scope="scope">
                    <span v-if="scope.row.redo_state == 1">重跑中</span>
                    <span v-else-if="scope.row.redo_state == 2">重跑成功</span>
                    <span v-else-if="scope.row.redo_state == 3">重跑失败</span>
                    <span v-else>other</span>
                </template>
            </el-table-column>
            <el-table-column prop="create_time" label="重跑开始时间" show-overflow-tooltip></el-table-column>
            <el-table-column prop="finish_time" label="重跑结束时间" show-overflow-tooltip></el-table-column>
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

        <el-dialog title="重跑分润" :visible.sync="modifyDialog" :close-on-click-modal="false" width="40%">
            <modify-report @close="modifyDialog = false" @success="theSuccess"></modify-report>
        </el-dialog>
    </page>
</template>

<script>
    import ModifyReport from './modify';

    export default {
        name: "modified-report-list",
        data() {
            return {
                form: {
                    trade_begin_time: '',
                    trade_end_time: '',
                    merchant_id: '',

                    agent_id: '',
                    agentps_sum_id: '',

                    profit_sum_id: '',
                    redo_state: '',

                    page: 1,
                    pageSize: 10,
                },
                total: 10,
                list: [],

                theSelections: [],

                modifyDialog: false,
            }
        },
        methods: {
            search() {
                this.form.page = 1;
                this.getList();
            },
            getList() {
                api.get('profit/modifyReportList', this.form).then(data => {
                    this.list = data.list;
                    this.total = data.total;
                })
            },
            changePageSize(size) {
                this.form.pageSize = size;
                this.form.page = 1;
                this.getList();
            },
            theSuccess() {
                this.getList();
            },
            handleSelectionChange(selection) {
                this.theSelections = selection;
            },
            redo() {
                if (this.theSelections.length > 0) {
                    if (this.theSelections.length == 1) {
                        api.post('profit/modifyReport', this.theSelections[0]).then(() => {
                            this.$message.success('重跑分润成功');
                            this.getList();
                        })
                    } else {
                        this.$message.error('请勿勾选多条数据进行重跑');
                        return false;
                    }
                } else {
                    // 弹窗
                    this.modifyDialog = true;
                }
            },
        },
        created() {
            this.form.trade_begin_time = moment().subtract(1, 'days').format("YYYY-MM-DD");
            this.form.trade_end_time = moment().subtract(1, 'days').format("YYYY-MM-DD");
            this.getList();
        },
        components: {
            ModifyReport,
        }
    }
</script>

<style scoped>
    .btn-class {
        float: right;
    }
</style>
