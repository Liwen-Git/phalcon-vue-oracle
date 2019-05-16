<template>
    <el-form v-model="form" ref="form" :rules="formRules" size="small" label-width="150px">
        <el-form-item prop="chl_name" label="渠道编号">
            <el-input type="text" v-model="form.chl_name" placeholder="请输入渠道编号" clearable></el-input>
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
            <el-input type="text" v-model="form.sys_chl_code" placeholder="请输入渠道名称" clearable></el-input>
        </el-form-item>
        <el-form-item prop="channel_merchant_id" label="渠道商户号">
            <el-input type="text" v-model="form.channel_merchant_id" placeholder="请输入渠道商户号" clearable></el-input>
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
        <el-form-item label="生效时间">
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
            <el-input type="textarea" v-model="form.memo" placeholder="请输入备注"></el-input>
        </el-form-item>
        <el-form-item label="是否阶梯费率">
            <el-switch
                v-model="isStep"
                active-text="是"
                inactive-text="否"
                style="margin-right: 20px;"
            >
            </el-switch>
            <el-button size="mini" type="success" :disabled="!isStep">新增费率</el-button>
        </el-form-item>

        <el-col>

        </el-col>
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
                theLoading: false,
                bankOptions: [],
                isStep: false,
            }
        },
        methods: {
            searchBank() {

            }
        },
        created() {
            this.form.service_start_time = moment().format("YYYY-MM-DD");
            this.form.service_end_time = moment().add(1, 'month').format("YYYY-MM-DD 23:59:59");
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
</style>
