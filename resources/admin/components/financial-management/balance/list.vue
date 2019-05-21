<template>
    <page title="余额查询">
        <el-form :model="form" ref="form" inline size="small">
            <el-form-item prop="merchant_type" label="商户类型">
                <el-select v-model="form.merchant_type">
                    <el-option label="商户" value="1"></el-option>
                    <el-option label="代理商" value="2"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item prop="agent_id" label="代理商号" :class="form.merchant_type == 2 ? 'show-class' : 'none-class'">
                <el-input type="text" v-model="form.agent_id" placeholder="请输入代理商号" clearable></el-input>
            </el-form-item>
            <el-form-item prop="agent_name" label="代理商名称" :class="form.merchant_type == 2 ? 'show-class' : 'none-class'">
                <el-select v-model="form.agent_name"
                           clearable
                           filterable
                           remote
                           placeholder="请输入关键字"
                           :remote-method="searchAgentName"
                           :loading="theAgentLoading"
                >
                    <el-option v-for="(item, index) in agentOptions" :key="index" :label="item.agent_name" :value="item.agent_name"></el-option>
                </el-select>
            </el-form-item>

            <el-form-item prop="merchant_id" label="商户号" :class="form.merchant_type == 1 ? 'show-class' : 'none-class'">
                <el-input type="text" v-model="form.merchant_id" placeholder="请输入商户编号" clearable></el-input>
            </el-form-item>
            <el-form-item prop="merchant_name" label="商户名称" :class="form.merchant_type == 1 ? 'show-class' : 'none-class'">
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

            <el-form-item>
                <el-button type="primary" @click="search">查询</el-button>
            </el-form-item>
        </el-form>
        <el-table :data="list" stripe>
            <el-table-column prop="merchant_id" label="商户号"></el-table-column>
            <el-table-column prop="merchant_name" label="商户名称"></el-table-column>
            <el-table-column prop="merchant_type" label="商户类型">
                <template slot-scope="scope">
                    <span v-if="scope.row.merchant_type == '1'">商户</span>
                    <span v-else>代理商</span>
                </template>
            </el-table-column>
            <el-table-column prop="available_amount" label="可用余额账户">
                <template slot-scope="scope">
                    {{ (scope.row.available_amount / 100).toFixed(2) }}
                </template>
            </el-table-column>
            <el-table-column prop="settlement_amount" label="待结算金额">
                <template slot-scope="scope">
                    {{ (scope.row.settlement_amount / 100).toFixed(2) }}
                </template>
            </el-table-column>
            <el-table-column prop="withdraw_deposit_amount" label="提现中金额">
                <template slot-scope="scope">
                    {{ (scope.row.withdraw_deposit_amount / 100).toFixed(2) }}
                </template>
            </el-table-column>
            <el-table-column prop="freezing_amount" label="冻结中金额">
                <template slot-scope="scope">
                    {{ (scope.row.freezing_amount / 100).toFixed(2) }}
                </template>
            </el-table-column>
            <el-table-column prop="refund_amount" label="退款中金额">
                <template slot-scope="scope">
                    {{ (scope.row.refund_amount / 100).toFixed(2) }}
                </template>
            </el-table-column>
            <el-table-column prop="deposit_amount" label="保证金">
                <template slot-scope="scope">
                    {{ (scope.row.deposit_amount / 100).toFixed(2) }}
                </template>
            </el-table-column>
            <el-table-column prop="prestore_amount" label="预存款金额">
                <template slot-scope="scope">
                    {{ (scope.row.prestore_amount / 100).toFixed(2) }}
                </template>
            </el-table-column>
            <el-table-column prop="shared_amount" label="分润金额">
                <template slot-scope="scope">
                    {{ (scope.row.shared_amount / 100).toFixed(2) }}
                </template>
            </el-table-column>
            <el-table-column label="操作">
                <template slot-scope="scope">
                    <el-button type="text" @click="frozen(scope.row)">冻结</el-button>
                    <el-button type="text" @click="unfrozen(scope.row)">解冻</el-button>
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

        <el-dialog title="金额冻结" :visible.sync="frozenDialog" :close-on-click-modal="false" width="25%">
            <balance-frozen :theBalance="theBalance" @close="frozenDialog = false" @frozenSuccess="theSuccess"></balance-frozen>
        </el-dialog>

        <el-dialog title="金额解冻" :visible.sync="unfrozenDialog" :close-on-click-modal="false" width="25%">
            <balance-unfrozen :theBalance="theBalance" @close="unfrozenDialog = false" @unfrozenSuccess="theSuccess"></balance-unfrozen>
        </el-dialog>
    </page>
</template>

<script>
    import BalanceFrozen from './frozen';
    import BalanceUnfrozen from './unfrozen';

    export default {
        name: "balance-list",
        data() {
            return {
                form: {
                    merchant_type: '1',
                    merchant_id: '',
                    merchant_name: '',
                    agent_id: '',
                    agent_name: '',

                    page: 1,
                    page_size: 10
                },
                total: 0,
                list: [],

                agentOptions: [],
                merchantOptions: [],
                theAgentLoading: false,
                theMerchantLoading: false,

                frozenDialog: false,
                unfrozenDialog: false,
                theBalance: null,
            }
        },
        methods: {
            searchAgentName(query) {
                if (query !== '') {
                    this.theAgentLoading = true;
                    setTimeout(() => {
                        this.theAgentLoading = false;
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
            getList() {
                api.get('balance/list', this.form).then(data => {
                    this.list = data.list;
                    this.total = data.total;
                })
            },
            search() {
                this.form.page = 1;
                this.getList();
            },
            changePageSize(size) {
                this.form.page_size = size;
                this.form.page = 1;
                this.getList();
            },
            frozen(row) {
                this.theBalance = row;
                this.frozenDialog = true;
            },
            theSuccess() {
                this.form.page = 1;
                this.getList();
            },
            unfrozen(row) {
                this.theBalance = row;
                this.unfrozenDialog = true;
            }
        },
        components: {
            BalanceFrozen,
            BalanceUnfrozen,
        }
    }
</script>

<style scoped>
    .show-class {
        display: inline-block;
    }
    .none-class {
        display: none;
    }
</style>
