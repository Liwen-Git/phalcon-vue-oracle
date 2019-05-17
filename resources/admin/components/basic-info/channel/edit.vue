<template>
    <el-form :model="form" ref="form" :rules="formRules" size="small" label-width="150px">
        <el-form-item prop="chl_name" label="渠道编号">
            <el-input type="text" v-model="form.chl_name" placeholder="请输入渠道编号" disabled class="w-200"></el-input>
        </el-form-item>
        <el-form-item prop="business_type" label="业务类型">
            <el-input type="text" v-model="form.busi_type" disabled class="w-200"></el-input>
        </el-form-item>
        <el-form-item prop="sys_chl_code" label="渠道名称">
            <el-input type="text" v-model="form.sys_chl_code" placeholder="请输入渠道名称" disabled class="w-200"></el-input>
        </el-form-item>
        <el-form-item prop="channel_merchant_id" label="渠道商户号">
            <el-input type="text" v-model="form.channel_merchant_id" placeholder="请输入渠道商户号" clearable class="w-200"></el-input>
        </el-form-item>
        <el-form-item prop="bank_code" label="银行名称">
            <el-input type="text" v-model="form.fbank_name" disabled class="w-200"></el-input>
        </el-form-item>
        <el-form-item prop="bank_card_type" label="支持卡种">
            <el-select v-model="form.bank_card_type" clearable>
                <el-option label="借记卡" value="1"></el-option>
                <el-option label="贷记卡" value="2"></el-option>
                <el-option label="不限卡种" value="3"></el-option>
                <el-option label="准贷记卡" value="5"></el-option>
            </el-select>
        </el-form-item>
        <el-form-item prop="fee_settle_type" label="渠道收费方式">
            <el-select v-model="form.fee_settle_type" clearable>
                <el-option label="日结" value="0"></el-option>
                <el-option label="月结" value="1"></el-option>
                <el-option label="1个季度结" value="2"></el-option>
                <el-option label="2个季度结" value="3"></el-option>
                <el-option label="3个季度结" value="4"></el-option>
                <el-option label="4个季度结" value="5"></el-option>
            </el-select>
        </el-form-item>
        <el-form-item prop="charge_type" label="渠道收费类型">
            <el-select v-model="form.charge_type" clearable>
                <el-option label="内收" value="0"></el-option>
                <el-option label="外收" value="1"></el-option>
            </el-select>
        </el-form-item>
        <el-form-item prop="service_time" label="生效时间">
            <el-date-picker
                v-model="form.service_start_time"
                type="date"
                placeholder="选择日期"
                format="yyyy 年 MM 月 dd 日"
                value-format="yyyy-MM-dd"
            ></el-date-picker>
            <span> - </span>
            <el-date-picker
                    v-model="form.service_end_time"
                    type="date"
                    placeholder="选择日期"
                    format="yyyy 年 MM 月 dd 日"
                    value-format="yyyy-MM-dd"
                    :picker-options="{disabledDate: (time) => {return time.getTime() < new Date(form.service_start_time)}}"
            ></el-date-picker>
        </el-form-item>


        <el-divider></el-divider>
        <el-form-item
                prop="fee_rate_type"
                label="价格模式"
                :rules="[
                    {required: true, message: '价格模式不能为空'}
                ]">
            <el-select v-model="form.fee_rate_type" clearable>
                <el-option label="固定比例" value="1"></el-option>
                <el-option label="固定金额" value="2"></el-option>
                <el-option label="混合收费" value="3"></el-option>
            </el-select>
        </el-form-item>
        <el-form-item prop="value_amt" label="梯度金额范围" :class="isStep ? 'show-class' : 'none-class'" class="no-margin-bottom">
            <el-form-item
                    class="inline"
                    prop="left_value_amt"
                    :rules="isStep ? {required: true, message: '请填写梯度金额范围'} : {}">
                <el-input type="text" v-model="form.left_value_amt" clearable class="w-100"></el-input>
            </el-form-item>
            <span> - </span>
            <el-form-item
                    class="inline"
                    prop="right_value_amt"
                    :rules="isStep ? {required: true, message: '请填写梯度金额范围'} : {}">
                <el-input type="text" v-model="form.right_value_amt" clearable class="w-100"></el-input>
            </el-form-item>
        </el-form-item>
        <el-form-item
                prop="fee_rate"
                label="渠道成本价"
                :class="form.fee_rate_type == 2 ? 'none-class' : 'show-class'"
                :rules="form.fee_rate_type == 2 ? {} : {required: true, message: '渠道成本价不能为空'}">
            <el-input type="text" v-model="form.fee_rate" clearable class="w-200">
                <template slot="append">%</template>
            </el-input>
        </el-form-item>
        <el-form-item
                prop="fee_fixed"
                label="固定金额"
                :class="form.fee_rate_type == 1 ? 'none-class' : 'show-class'"
                :rules="form.fee_rate_type == 1 ? {} : {required: true, message: '固定金额不能为空'}">
            <el-input type="text" v-model="form.fee_fixed" clearable class="w-200">
                <template slot="append">元</template>
            </el-input>
        </el-form-item>
        <el-form-item label="收费金额范围" class="no-margin-bottom">
            <el-form-item
                    class="inline"
                    prop="min_fee_amt"
                    :rules="{required: true, message: '请填写收费金额范围'}">
                <el-input type="text" v-model="form.min_fee_amt" clearable class="w-100"></el-input>
            </el-form-item>
            <span> - </span>
            <el-form-item
                    class="inline"
                    prop="max_fee_amt"
                    :rules="{required: true, message: '请填写收费金额范围'}">
                <el-input type="text" v-model="form.max_fee_amt" clearable class="w-100"></el-input>
            </el-form-item>
        </el-form-item>
        <el-form-item prop="state" label="状态">
            <el-select v-model="form.state">
                <el-option label="作废" value="0"></el-option>
                <el-option label="生效" value="1"></el-option>
                <el-option label="即将过期" value="2" disabled></el-option>
                <el-option label="已过期" value="3" disabled></el-option>
            </el-select>
        </el-form-item>

        <el-row>
            <el-col>
                <el-button class="fr cancel-class" size="small" @click="closeDialog">取消</el-button>
                <el-button class="fr" type="primary" size="small" @click="commit">确定</el-button>
            </el-col>
        </el-row>
    </el-form>
</template>

<script>
    export default {
        name: "channel-edit",
        props: {
            businessTypes: {
                required: true,
                type: Array,
            },
            theChannel: {
                required: true,
                type: Object,
            }
        },
        data() {
            let validateServiceTime = (rule, value, callback) => {
                if (!this.form.service_start_time || !this.form.service_end_time) {
                    callback(new Error('生效时间不能为空'));
                } else {
                    callback();
                }
            };

            return {
                form: {
                    fee_rate_seq_no: '',
                    chl_name: '',
                    sys_chl_code: '',
                    busi_type: '',
                    channel_merchant_id: '',
                    fbank_name: '',
                    bank_card_type: '',
                    fee_settle_type: '',
                    charge_type: '',
                    service_start_time: '',
                    service_end_time: '',
                    state: '',

                    fee_rate_type: '',
                    min_fee_amt: '',
                    max_fee_amt: '',
                    fee_rate: '',
                    fee_fixed: '',
                    left_value_amt: '',
                    right_value_amt: '',
                },
                formRules: {
                    channel_merchant_id: [
                        {required: true, message: '渠道商户号不能为空'}
                    ],
                    bank_card_type: [
                        {required: true, message: '支持卡种不能为空'}
                    ],
                    fee_settle_type: [
                        {required: true, message: '渠道收费方式不能为空'}
                    ],
                    charge_type: [
                        {required: true, message: '渠道收费类型不能为空'}
                    ],
                    service_time: [
                        {validator: validateServiceTime}
                    ],
                    state: [

                    ]
                },

                isStep: false,
            }
        },
        methods: {
            closeDialog() {
                this.$emit('close');
                this.$refs.form.resetFields();
            },
            commit() {
                this.$refs.form.validate(valid => {
                    if (valid) {
                        console.log(this.form);return;
                        api.post('channel/edit', this.form).then(() => {
                            this.$message.success('渠道费率增加成功');
                            this.closeDialog();
                            this.$emit('addSuccess');
                        })
                    }
                })
            },
            initForm() {
                this.form = _.clone(this.theChannel);
                if (this.theChannel.fee_rate_type == '固定比例') {
                    this.form.fee_rate_type = '1';
                } else if (this.theChannel.fee_rate_type == '固定金额') {
                    this.form.fee_rate_type = '2';
                } else {
                    this.form.fee_rate_type = '3';
                }

                if (this.theChannel.charge_method == '日结') {
                    this.form.fee_settle_type = '0';
                } else if (this.theChannel.charge_method == '月结') {
                    this.form.fee_settle_type = '1';
                } else if (this.theChannel.charge_method == '1个季度结') {
                    this.form.fee_settle_type = '2';
                } else if (this.theChannel.charge_method == '2个季度结') {
                    this.form.fee_settle_type = '3';
                } else if (this.theChannel.charge_method == '3个季度结') {
                    this.form.fee_settle_type = '4';
                } else {
                    this.form.fee_settle_type = '5';
                }

                if (this.theChannel.bank_card_type == '借记卡') {
                    this.form.bank_card_type = '1';
                } else if (this.theChannel.bank_card_type == '贷记卡') {
                    this.form.bank_card_type = '2';
                } else if (this.theChannel.bank_card_type == '不限卡种') {
                    this.form.bank_card_type = '3';
                } else {
                    this.form.bank_card_type = '5';
                }

                if (this.theChannel.charge_type == '内收') {
                    this.form.charge_type = '0';
                } else {
                    this.form.charge_type = '1';
                }

                this.form.service_start_time = moment(new Date(this.theChannel.service_start_time)).format('YYYY-MM-DD');
                this.form.service_end_time = moment(new Date(this.theChannel.service_end_time)).format('YYYY-MM-DD');

                if (this.theChannel.left_value_amt && this.theChannel.right_value_amt) {
                    this.isStep = true;
                }
            }
        },
        created() {
            this.initForm();
        },
        watch: {
            theChannel() {
                this.initForm();
            }
        }
    }
</script>

<style scoped>
    .show-class {
        display: block;
    }
    .none-class {
        display: none;
    }
    .cancel-class {
        margin-left: 20px;
    }
    .no-margin-bottom {
        margin-bottom: 0;
    }
</style>
