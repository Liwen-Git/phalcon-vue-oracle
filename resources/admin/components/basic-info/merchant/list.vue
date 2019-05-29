<template>
    <page title="商户信息">
        <el-form :model="form" ref="form" inline size="small">
            <el-form-item prop="merchant_id" label="商户编号">
                <el-input type="text" v-model="form.merchant_id" placeholder="请输入商户编号" clearable></el-input>
            </el-form-item>
            <el-form-item prop="agent_id" label="代理商编号">
                <el-input type="text" v-model="form.agent_id" placeholder="请输入代理商编号" clearable></el-input>
            </el-form-item>
            <el-form-item prop="merchant_name" label="商户全称">
                <el-select v-model="form.merchant_name"
                    clearable
                    filterable
                    remote
                    placeholder="请输入关键字"
                    :remote-method="searchMerchantName"
                    :loading="theLoading"
                >
                    <el-option v-for="(item, index) in options" :key="index" :label="item.merchant_name" :value="item.merchant_name"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="search">查询</el-button>
            </el-form-item>
        </el-form>
        <el-table :data="list" stripe border>
            <el-table-column prop="index" label="序号"></el-table-column>
            <el-table-column prop="merchant_id" label="商户编号"></el-table-column>
            <el-table-column prop="merchant_name" label="商户全称"></el-table-column>
            <el-table-column prop="merchant_type" label="商户类型">
                <template slot-scope="scope">
                    <span v-if="scope.row.merchant_type == 1">普通用户</span>
                    <span v-else-if="scope.row.merchant_type == 2">平台用户</span>
                    <span v-else>未知({{scope.row.merchant_type}})</span>
                </template>
            </el-table-column>
            <el-table-column prop="agent_id" label="代理商编号"></el-table-column>
            <el-table-column prop="create_time" label="注册日期"></el-table-column>
            <el-table-column prop="merchant_verify" label="商户认证状态">
                <template slot-scope="scope">
                    <span v-if="scope.row.merchant_verify == 1">未认证</span>
                    <span v-else-if="scope.row.merchant_verify == 2">已认证</span>
                    <span v-else>未知({{scope.row.merchant_verify}})</span>
                </template>
            </el-table-column>
            <el-table-column prop="user_state" label="用户状态">
                <template slot-scope="scope">
                    <span v-if="scope.row.user_state == 1">待激活</span>
                    <span v-else-if="scope.row.user_state == 2">正常</span>
                    <span v-else-if="scope.row.user_state == 3">冻结变更</span>
                    <span v-else-if="scope.row.user_state == 4">全面冻结</span>
                    <span v-else-if="scope.row.user_state == 5">注销</span>
                    <span v-else>未知({{scope.row.user_state}})</span>
                </template>
            </el-table-column>
            <el-table-column label="操作">
                <template slot-scope="scope">
                    <el-button type="text" @click="detail(scope.row.merchant_id)">详情</el-button>
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

        <el-dialog title="合同费率信息" :visible.sync="dialogTableVisible" width="80%">
            <el-table :data="gridData">
                <el-table-column prop="index" label="序号"></el-table-column>
                <el-table-column prop="product_name" label="企业产品类型"></el-table-column>
                <el-table-column prop="fee_mode" label="手续费模式">
                    <template slot-scope="scope">
                        <span v-if="scope.row.fee_mode == 1">内收</span>
                        <span v-else-if="scope.row.fee_mode == 2">外收</span>
                        <span v-else>未知({{scope.row.fee_mode}})</span>
                    </template>
                </el-table-column>
                <el-table-column prop="out_type" label="外收类型">
                    <template slot-scope="scope">
                        <span v-if="scope.row.out_type == 1">预付费后结</span>
                        <span v-else-if="scope.row.out_type == 2">记账</span>
                        <span v-else-if="scope.row.out_type == 3">预付费实时</span>
                        <span v-else>--</span>
                    </template>
                </el-table-column>
                <el-table-column prop="type" label="固定收费模式">
                    <template slot-scope="scope">
                        <span v-if="scope.row.type == 1">百分比</span>
                        <span v-else-if="scope.row.type == 2">固定每笔</span>
                        <span v-else-if="scope.row.type == 3">混合收费</span>
                        <span v-else>未知({{scope.row.type}})</span>
                    </template>
                </el-table-column>
                <el-table-column prop="rate" label="固定比例(%)">
                    <template slot-scope="scope">
                        {{scope.row.rate ? scope.row.rate + '%' : '--'}}
                    </template>
                </el-table-column>
                <el-table-column prop="fixedFee" label="固定金额(元)">
                    <template slot-scope="scope">
                        {{scope.row.fixedFee ? parseFloat(scope.row.fixedFee/100) : '0'}}
                    </template>
                </el-table-column>
                <el-table-column prop="free_max" label="优惠笔数"></el-table-column>
                <el-table-column prop="ceiling" label="最高费用(元)">
                    <template slot-scope="scope">
                        {{scope.row.ceiling ? parseFloat(scope.row.ceiling/100) : '0'}}
                    </template>
                </el-table-column>
                <el-table-column prop="floor" label="最低费用(元)">
                    <template slot-scope="scope">
                        {{scope.row.floor ? parseFloat(scope.row.floor/100) : '0'}}
                    </template>
                </el-table-column>
                <el-table-column prop="start_time" label="生效时间"></el-table-column>
                <el-table-column prop="end_time" label="结束时间"></el-table-column>
                <el-table-column prop="pri_level" label="优先级"></el-table-column>
                <el-table-column prop="share_profit" label="是否分润">
                    <template slot-scope="scope">
                        {{scope.row.share_profit == '1' ? '是' : '否'}}
                    </template>
                </el-table-column>
                <el-table-column prop="state" label="状态">
                    <template slot-scope="scope">
                        <span v-if="scope.row.state == 0">未生效</span>
                        <span v-if="scope.row.state == 1">正常</span>
                        <span v-else-if="scope.row.state == 2">作废</span>
                        <span v-else-if="scope.row.state == 3">过期</span>
                        <span v-else>未知({{scope.row.state}})</span>
                    </template>
                </el-table-column>
                <el-table-column prop="memo" label="备注"></el-table-column>
            </el-table>
            <el-pagination
                    layout="sizes, total, prev, pager, next"
                    :page-size="dialogTablePageSize"
                    :total="dialogTableTotal"
                    :current-page.sync="dialogTablePage"
                    @current-change="getDetailList"
                    :page-sizes="[10, 20, 30]"
                    @size-change="changeDetailPageSize"
            ></el-pagination>
        </el-dialog>
    </page>
</template>

<script>
    export default {
        name: "merchant-list",
        data() {
            return {
                form: {
                    merchant_id: '',
                    agent_id: '',
                    merchant_name: '',

                    page: 1,
                    page_size: 10,
                },
                total: 0,
                list: [],
                theLoading: false,
                options: [],

                dialogTableVisible: false,
                gridData: [],
                dialogTablePage: 1,
                dialogTablePageSize: 10,
                dialogTableTotal: 0,
                merchantId: '',
            }
        },
        methods: {
            searchMerchantName(query) {
                if (query !== '') {
                    this.loading = true;
                    setTimeout(() => {
                        this.loading = false;
                        api.post('merchant/query_merchant_info', {merchant_name: query}).then(data => {
                            this.options = data.list;
                        })
                    }, 200);
                } else {
                    this.options = [];
                }
            },
            search() {
                this.form.page = 1;
                this.getList();
            },
            getList() {
                api.get('merchant/list', this.form).then(data => {
                    this.list = data.list;
                    this.total = data.total;
                })
            },
            changePageSize(pageSize) {
                this.form.page = 1;
                this.form.page_size = pageSize;
                this.getList();
            },
            detail(merchantId) {
                this.merchantId = merchantId;
                this.dialogTablePage = 1;
                this.dialogTablePageSize = 10;
                this.dialogTableVisible = true;
                this.getDetailList();
            },
            getDetailList() {
                let param = {
                    merchant_id: this.merchantId,
                    page: this.dialogTablePage,
                    pageSize: this.dialogTablePageSize,
                };
                api.get('merchant/detail', param).then(data => {
                    this.gridData = data.list;
                    this.dialogTableTotal = data.total;
                })
            },
            changeDetailPageSize(pageSize) {
                this.dialogTablePage = 1;
                this.dialogTablePageSize = pageSize;
                this.getDetailList();
            }
        },
        created() {
            this.getList();
        }
    }
</script>

<style scoped>

</style>
