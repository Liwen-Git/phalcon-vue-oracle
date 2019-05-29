<template>
    <page title="渠道信息">
        <el-col>
            <el-form :model="form" ref="form" inline size="small">
                <el-form-item prop="chl_name" label="渠道编号">
                    <el-input type="text" v-model="form.chl_name" placeholder="请输入渠道编号" clearable></el-input>
                </el-form-item>
                <el-form-item prop="channel_merchant_id" label="渠道商户号">
                    <el-input type="text" v-model="form.channel_merchant_id" placeholder="请输入渠道商户号" clearable></el-input>
                </el-form-item>
                <el-form-item prop="business_types" label="业务类型">
                    <el-cascader
                        v-model="form.business_types"
                        placeholder="请选择(可搜索)"
                        :options="businessTypes"
                        :props="{value: 'type', label: 'name', children: 'child'}"
                        filterable
                        change-on-select
                        clearable
                    ></el-cascader>
                </el-form-item>
                <el-form-item prop="state" label="状态">
                    <el-select v-model="form.state" placeholder="全部" clearable>
                        <el-option label="作废" value="0"></el-option>
                        <el-option label="生效" value="1"></el-option>
                        <el-option label="即将过期" value="2"></el-option>
                        <el-option label="已过期" value="3"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="search">搜索</el-button>
                </el-form-item>
                <el-button type="success" size="small" class="fr" @click="addDialog = true">添加</el-button>
            </el-form>
        </el-col>
        <el-col>
            <el-table :data="list" stripe border>
                <el-table-column prop="row_num" label="序号" width="50px"></el-table-column>
                <el-table-column prop="chl_name" label="渠道编号"></el-table-column>
                <el-table-column prop="busi_type" label="业务类型"></el-table-column>
                <el-table-column prop="sys_chl_code" label="渠道名称"></el-table-column>
                <el-table-column prop="channel_merchant_id" label="渠道商户号"></el-table-column>
                <el-table-column prop="fbank_name" label="银行名称"></el-table-column>
                <el-table-column prop="bank_card_type" label="卡类型"></el-table-column>
                <el-table-column prop="left_value_amt" label="收费最小金额"></el-table-column>
                <el-table-column prop="right_value_amt" label="收费最大金额"></el-table-column>
                <el-table-column prop="charge_method" label="收费方式"></el-table-column>
                <el-table-column prop="charge_type" label="收费类型"></el-table-column>
                <el-table-column prop="fee_rate_type" label="价格模式"></el-table-column>
                <el-table-column prop="fee_rate" label="渠道成本">
                    <template slot-scope="scope">
                        <span v-if="scope.row.fee_rate_type == '混合收费'">
                            {{scope.row.fee_rate + '% + ' + scope.row.fee_fixed + '元/笔'}}
                        </span>
                        <span v-else-if="scope.row.fee_rate_type == '固定金额'">
                            {{scope.row.fee_fixed + '元/笔'}}
                        </span>
                        <span v-else>
                            {{scope.row.fee_rate + '%'}}
                        </span>
                    </template>
                </el-table-column>
                <el-table-column prop="min_fee_amt" label="最低金额"></el-table-column>
                <el-table-column prop="max_fee_amt" label="最高金额"></el-table-column>
                <el-table-column prop="state" label="状态">
                    <template slot-scope="scope">
                        <span v-if="scope.row.state == 0">作废</span>
                        <span v-else-if="scope.row.state == 1">生效</span>
                        <span v-else-if="scope.row.state == 2">即将过期</span>
                        <span v-else-if="scope.row.state == 3">已过期</span>
                        <span v-else>未知({{scope.row.state}})</span>
                    </template>
                </el-table-column>
                <el-table-column prop="service_time" label="生效时间"></el-table-column>
                <el-table-column prop="create_time" label="创建时间"></el-table-column>
                <el-table-column prop="update_time" label="更新时间"></el-table-column>
                <el-table-column prop="oper_name" label="最近操作人"></el-table-column>
                <el-table-column label="操作">
                    <template slot-scope="scope">
                        <el-button type="text" @click="editChannel(scope.row)">编辑</el-button>
                        <el-button type="text" @click="deleteChannel(scope.row.fee_rate_seq_no)">删除</el-button>
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
        </el-col>

        <el-dialog title="添加渠道" :visible.sync="addDialog" :close-on-click-modal="false" :show-close="false" width="40%">
            <channel-add :businessTypes="businessTypes" @close="addDialog = false" @addSuccess="theSuccess"></channel-add>
        </el-dialog>

        <el-dialog title="编辑渠道" :visible.sync="editDialog" :close-on-click-modal="false" :show-close="false" width="40%">
            <channel-edit :businessTypes="businessTypes" :theChannel="theChannel" @close="editDialog = false" @editSuccess="theSuccess"></channel-edit>
        </el-dialog>
    </page>
</template>

<script>
    import ChannelAdd from './add';
    import ChannelEdit from './edit';

    export default {
        name: "channel-list",
        data() {
            return {
                form: {
                    chl_name: '',
                    channel_merchant_id: '',
                    business_types: [],
                    state: '2',

                    page: 1,
                    page_size: 10,
                },
                businessTypes: [],
                total: 0,
                list: [],

                addDialog: false,
                theChannel: null,
                editDialog: false,
            }
        },
        methods: {
            getBusinessTypesAndInit() {
                api.get('channel/businessTypesOfNew').then(data => {
                    this.businessTypes = data;
                }).then(() => {
                    this.getList();
                })
            },
            getList() {
                api.get('channel/list', this.form).then(data => {
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
            theSuccess() {
                this.form.page = 1;
                this.getList();
            },
            editChannel(row) {
                this.theChannel = row;
                this.editDialog = true;
            },
            deleteChannel(code) {
                this.$confirm('确定删除吗?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    api.post('channel/delete', {fee_rate_seq_no: code}).then(() => {
                        this.$message.success('渠道费率删除成功');
                        this.getList();
                    })
                }).catch(() => {})
            }
        },
        created() {
            this.getBusinessTypesAndInit();
        },
        components: {
            ChannelAdd,
            ChannelEdit,
        }
    }
</script>

<style scoped>

</style>
