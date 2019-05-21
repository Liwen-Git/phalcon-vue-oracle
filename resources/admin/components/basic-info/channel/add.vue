<template>
    <el-form :model="form" ref="form" :rules="formRules" size="small" label-width="150px">
        <el-form-item prop="chl_name" label="渠道编号">
            <el-input type="text" v-model="form.chl_name" placeholder="请输入渠道编号" clearable class="w-200"></el-input>
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
        <el-form-item prop="sys_chl_code" label="渠道名称">
            <el-input type="text" v-model="form.sys_chl_code" placeholder="请输入渠道名称" clearable class="w-200"></el-input>
        </el-form-item>
        <el-form-item prop="channel_merchant_id" label="渠道商户号">
            <el-input type="text" v-model="form.channel_merchant_id" placeholder="请输入渠道商户号" clearable class="w-200"></el-input>
        </el-form-item>
        <el-form-item prop="bank_code" label="银行名称">
            <el-select v-model="form.bank_code"
                       clearable
                       filterable
                       remote
                       placeholder="请输入关键字"
                       :remote-method="searchBank"
                       :loading="theLoading"
            >
                <el-option v-for="(item, index) in bankOptions" :key="index" :label="item.bank_name" :value="item.bank_code"></el-option>
            </el-select>
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
        <el-form-item prop="memo" label="备注">
            <el-input type="textarea" v-model="form.memo" placeholder="请输入备注" class="w-200"></el-input>
        </el-form-item>
        <el-form-item label="是否阶梯费率">
            <el-switch
                v-model="isStep"
                active-text="是"
                inactive-text="否"
                style="margin-right: 20px;"
            >
            </el-switch>
            <el-button size="mini" type="success" :disabled="!isStep" @click="addRateList">新增费率</el-button>
        </el-form-item>

        <el-row v-for="(item, index) in form.list" :key="index">
            <el-divider></el-divider>
            <el-form-item
                    :prop="'list.' + index + '.fee_rate_type'"
                    label="价格模式"
                    :rules="[
                        {required: true, message: '价格模式不能为空'}
                    ]">
                <el-select v-model="item.fee_rate_type" clearable>
                    <el-option label="固定比例" value="1"></el-option>
                    <el-option label="固定金额" value="2"></el-option>
                    <el-option label="混合收费" value="3"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item prop="value_amt" label="梯度金额范围" :class="isStep ? 'show-class' : 'none-class'" class="no-margin-bottom">
                <el-form-item
                        class="inline"
                        :prop="'list.' + index + '.left_value_amt'"
                        :rules="isStep ? {required: true, message: '请填写梯度金额范围'} : {}">
                    <el-input type="text" v-model="item.left_value_amt" clearable class="w-100"></el-input>
                </el-form-item>
                <span> - </span>
                <el-form-item
                        class="inline"
                        :prop="'list.' + index + '.right_value_amt'"
                        :rules="isStep ? {required: true, message: '请填写梯度金额范围'} : {}">
                    <el-input type="text" v-model="item.right_value_amt" clearable class="w-100"></el-input>
                </el-form-item>
            </el-form-item>
            <el-form-item
                    :prop="'list.' + index + '.fee_rate'"
                    label="渠道成本价"
                    :class="item.fee_rate_type == 2 ? 'none-class' : 'show-class'"
                    :rules="item.fee_rate_type == 2 ? {} : {required: true, message: '渠道成本价不能为空'}">
                <el-input type="text" v-model="item.fee_rate" clearable class="w-200">
                    <template slot="append">%</template>
                </el-input>
            </el-form-item>
            <el-form-item
                    :prop="'list.' + index + '.fee_fixed'"
                    label="固定金额"
                    :class="item.fee_rate_type == 1 ? 'none-class' : 'show-class'"
                    :rules="item.fee_rate_type == 1 ? {} : {required: true, message: '固定金额不能为空'}">
                <el-input type="text" v-model="item.fee_fixed" clearable class="w-200">
                    <template slot="append">元</template>
                </el-input>
            </el-form-item>
            <el-form-item label="收费金额范围" class="no-margin-bottom">
                <el-form-item
                        class="inline"
                        :prop="'list.' + index + '.min_fee_amt'"
                        :rules="{required: true, message: '请填写收费金额范围'}">
                    <el-input type="text" v-model="item.min_fee_amt" clearable class="w-100"></el-input>
                </el-form-item>
                <span> - </span>
                <el-form-item
                        class="inline"
                        :prop="'list.' + index + '.max_fee_amt'"
                        :rules="{required: true, message: '请填写收费金额范围'}">
                    <el-input type="text" v-model="item.max_fee_amt" clearable class="w-100"></el-input>
                </el-form-item>
                <el-button
                        class="fr inline"
                        size="small"
                        type="danger"
                        icon="el-icon-delete"
                        @click.prevent="removeRateList(item)"
                        :class="showDelete ? 'show-class' : 'none-class'"></el-button>
            </el-form-item>
        </el-row>
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
        name: "channel-add",
        props: {
            businessTypes: {
                required: true,
                type: Array,
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
                    chl_name: '',
                    sys_chl_code: '',
                    business_types: [],
                    channel_merchant_id: '',
                    bank_code: '',
                    bank_card_type: '',
                    fee_settle_type: '',
                    charge_type: '',
                    service_start_time: '',
                    service_end_time: '',
                    memo: '',
                    list: [
                        {
                            fee_rate_type: '',
                            min_fee_amt: '',
                            max_fee_amt: '',
                            fee_rate: '',
                            fee_fixed: '',
                            left_value_amt: '',
                            right_value_amt: '',
                        },
                    ]
                },
                formRules: {
                    chl_name: [
                        {required: true, message: '渠道编号不能为空'}
                    ],
                    sys_chl_code: [
                        {required: true, message: '渠道名称不能为空'}
                    ],
                    business_types: [
                        {required: true, message: '业务类型不能为空'}
                    ],
                    channel_merchant_id: [
                        {required: true, message: '渠道商户号不能为空'}
                    ],
                    bank_code: [
                        {required: true, message: '银行名称不能为空'}
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
                    ]
                },
                theLoading: false,
                bankOptions: [],
                isStep: false,
                showDelete: false,
            }
        },
        methods: {
            searchBank(query) {
                if (query !== '') {
                    this.theLoading = true;
                    setTimeout(() => {
                        this.theLoading = false;
                        api.get('channel/queryBankInfo', {bank_name: query}).then(data => {
                            this.bankOptions = data.list;
                        })
                    }, 200);
                } else {
                    this.bankOptions = [];
                }
            },
            addRateList() {
                this.form.list.push({
                    fee_rate_type: '',
                    min_fee_amt: '',
                    max_fee_amt: '',
                    fee_rate: '',
                    fee_fixed: '',
                    left_value_amt: '',
                    right_value_amt: '',
                });
            },
            removeRateList(item) {
                if (this.form.list.length <= 1) {
                    this.$message.error('费率至少要有一个');
                    return;
                }
                let index = this.form.list.indexOf(item);
                if (index !== -1) {
                    this.form.list.splice(index, 1);
                }
            },
            closeDialog() {
                this.$emit('close');
                this.$refs.form.resetFields();
                this.isStep = false;
                this.form.list = [
                    {
                        fee_rate_type: '',
                        min_fee_amt: '',
                        max_fee_amt: '',
                        fee_rate: '',
                        fee_fixed: '',
                        left_value_amt: '',
                        right_value_amt: '',
                    },
                ];
            },
            commit() {
                this.$refs.form.validate(valid => {
                    if (valid) {
                        api.post('channel/add', this.form).then(() => {
                            this.$message.success('渠道费率增加成功');
                            this.closeDialog();
                            this.$emit('addSuccess');
                        })
                    }
                })
            }
        },
        created() {
            this.form.service_start_time = moment().format("YYYY-MM-DD");
            this.form.service_end_time = moment().add(1, 'month').format("YYYY-MM-DD");
        },
        watch: {
            'form.list': {
                handler(newValue, oldValue) {
                    if (newValue.length <= 1) {
                        this.showDelete = false;
                    } else {
                        this.showDelete = true;
                    }
                },
                deep: true,
                immediate: true,
            },
            isStep: {
                handler(newValue, oldValue) {
                    if (newValue == false) {
                        let length = this.form.list.length;
                        if (length > 1) {
                            this.form.list.splice(1, length - 1);
                        }
                    }
                },
                deep: true,
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
