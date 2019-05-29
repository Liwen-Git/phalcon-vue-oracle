<template>
    <el-form :model="form" ref="form" :rules="formRules" size="small" label-width="150px">
        <el-form-item prop="type" label="重跑类型">
            <el-select v-model="form.type">
                <el-option label="重跑分润明细" value="0"></el-option>
                <el-option label="重跑分润汇总" value="1"></el-option>
            </el-select>
        </el-form-item>
        <el-form-item prop="trade_time" label="交易日期">
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
                    :picker-options="{disabledDate: (time) => {return time.getTime() < new Date(form.trade_begin_time) - 8.64e7}}"
            ></el-date-picker>
        </el-form-item>
        <el-form-item prop="agent_id" label="代理商编号">
            <el-input type="text" v-model="form.agent_id" placeholder="请输入代理商编号" clearable class="w-300"></el-input>
        </el-form-item>
        <el-form-item prop="merchant_id" label="商户编号">
            <el-input type="text" v-model="form.merchant_id" placeholder="请输入商户编号" clearable class="w-300"></el-input>
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
        <el-form-item prop="sum_id" label="分润/利润汇总单号">
            <el-input type="text" v-model="form.sum_id" placeholder="请输入分润/利润汇总单号" clearable class="w-300"></el-input>
        </el-form-item>
        <el-form-item prop="chl_name" label="渠道名称">
            <el-select
                    v-model="form.chl_name"
                    filterable
                    remote
                    clearable
                    placeholder="请输入渠道关键字"
                    :remote-method="searchChannel">
                <el-option v-for="(opt, i) in channelOptions" :key="i" :label="opt.acc_name" :value="opt.acc_name"></el-option>
            </el-select>
        </el-form-item>
        <el-form-item prop="order_type" label="订单类型">
            <el-select v-model="form.order_type" placeholder="全部" clearable>
                <el-option label="正常单" value="0"></el-option>
                <el-option label="异常单" value="1"></el-option>
            </el-select>
        </el-form-item>
        <el-form-item prop="order_id" label="天下单号">
            <el-input type="text" v-model="form.order_id" placeholder="请输入天下单号" clearable class="w-300"></el-input>
        </el-form-item>

        <el-row>
            <el-button class="fr cancel-class" size="small" @click="closeDialog">取消</el-button>
            <el-button class="fr" type="primary" size="small" @click="commit">确定</el-button>
        </el-row>
    </el-form>
</template>

<script>
    export default {
        name: "modify-report-modify",
        data() {
            let validateTradeTime = (rule, value, callback) => {
                if (!this.form.trade_begin_time || !this.form.trade_end_time) {
                    callback(new Error('交易日期不能为空'));
                } else {
                    callback();
                }
            };

            return {
                form: {
                    type: '0',
                    trade_begin_time: '',
                    trade_end_time: '',
                    agent_id: '',
                    merchant_id: '',
                    busitypes_code: [],
                    sum_id: '',
                    chl_name: '',
                    order_type: '',
                    oder_id: '',
                },
                businessTypes: [],
                channelOptions: [],
                formRules: {
                    trade_time: [
                        {validator: validateTradeTime}
                    ],
                    type: [
                        {required: true, message: '重跑类型不能为空'}
                    ]
                }
            }
        },
        methods: {
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
            getBusinessTypes() {
                api.get('profit/businessTypesOfNew').then(data => {
                    this.businessTypes = data;
                })
            },
            closeDialog() {
                this.$emit('close');
                this.$refs.form.resetFields();
            },
            commit() {
                this.$refs.form.validate(valid => {
                    if (valid) {
                        api.post('profit/modifyReport', this.form).then(() => {
                            this.$message.success('重跑分润成功');
                            this.$emit('success');
                            this.closeDialog();
                        })
                    }
                })
            }
        },
        created() {
            this.form.trade_begin_time = moment().subtract(1, 'days').format("YYYY-MM-DD");
            this.form.trade_end_time = moment().subtract(1, 'days').format("YYYY-MM-DD");
            this.getBusinessTypes();
        },
    }
</script>

<style scoped>
    .cancel-class {
        margin-left: 10px;
    }
</style>
