<template>
    <el-form :model="form" ref="form" size="small" label-width="100px">
        <el-form-item
                prop="second_busi_type"
                label="记账类型"
                :rules="{required: true, message: '记账类型不能为空'}">
            <el-select v-model="form.second_busi_type" placeholder="请选择" clearable>
                <el-option label="记账（余额）-长款" value="01"></el-option>
                <el-option label="记账（余额）-短款" value="02"></el-option>
                <el-option label="记账（保证金）" value="03"></el-option>
                <el-option label="记账（预存款）" value="04"></el-option>
                <el-option label="其它" value="05"></el-option>
            </el-select>
        </el-form-item>
        <el-row v-show="form.second_busi_type != '05'">
            <el-col :span="9">
                <el-form-item prop="acc_type" label="记账主体" :rules="form.second_busi_type != '05' ? {required: true, message: '记账主体不能为空'} : {}">
                    <el-select v-model="form.acc_type" placeholder="请选择" clearable>
                        <el-option label="商户" value="1"></el-option>
                        <el-option label="代理商" value="2"></el-option>
                    </el-select>
                </el-form-item>
            </el-col>
            <el-col :span="9">
                <el-form-item prop="acc_id" label="商户号" v-show="form.acc_type == 1" :rules="form.second_busi_type != '05' ? {required: true, message: '商户号不能为空'} : {}">
                    <el-input type="text" v-model="form.acc_id" placeholder="请输入商户号" clearable></el-input>
                </el-form-item>
                <el-form-item prop="acc_id" label="代理商号" v-show="form.acc_type == 2" :rules="form.second_busi_type != '05' ? {required: true, message: '代理商号不能为空'} : {}">
                    <el-input type="text" v-model="form.acc_id" placeholder="请输入代理商号" clearable></el-input>
                </el-form-item>
            </el-col>
            <el-col>
                <el-form-item prop="acc_amount" label="记账金额" :rules="form.second_busi_type != '05' ? {validator: validateAccAmount} : {validator: (rule, value, callback) => {callback()}}">
                    <el-input-number v-model.number="form.acc_amount" :precision="2" :step="1" :min="0"></el-input-number>
                </el-form-item>
            </el-col>
        </el-row>

        <el-row v-show="form.second_busi_type == '05'" v-for="(item, index) in form.list" :key="index">
            <el-divider></el-divider>
            <el-col :span="8">
                <el-form-item
                        :prop="'list.' + index + '.acc_type'"
                        label="记账主体"
                        :rules="form.second_busi_type == '05' ? {required: true, message: '记账主体不能为空'} : {}">
                    <el-select v-model="item.acc_type" placeholder="请选择" clearable>
                        <el-option label="商户" value="1"></el-option>
                        <el-option label="代理商" value="2"></el-option>
                        <el-option label="渠道" value="3"></el-option>
                        <el-option label="内部科目" value="4"></el-option>
                    </el-select>
                </el-form-item>
            </el-col>
            <el-col :span="8">
                <el-form-item
                        :prop="'list.' + index + '.acc_id'"
                        label="商户号"
                        v-show="item.acc_type == 1"
                        :rules="form.second_busi_type == '05' ? {required: true, message: '商户号不能为空'} : {}">
                    <el-input type="text" v-model="item.acc_id" placeholder="请输入商户号" clearable></el-input>
                </el-form-item>
                <el-form-item
                        :prop="'list.' + index + '.acc_id'"
                        label="代理商号"
                        v-show="item.acc_type == 2"
                        :rules="form.second_busi_type == '05' ? {required: true, message: '代理商号不能为空'} : {}">
                    <el-input type="text" v-model="item.acc_id" placeholder="请输入代理商号" clearable></el-input>
                </el-form-item>
                <el-form-item
                        :prop="'list.' + index + '.acc_id'"
                        label="渠道"
                        v-show="item.acc_type == 3"
                        :rules="form.second_busi_type == '05' ? {required: true, message: '渠道不能为空'} : {}">
                    <el-select
                            v-model="item.acc_id"
                            filterable
                            remote
                            placeholder="请输入渠道关键字"
                            :remote-method="searchChannel">
                        <el-option v-for="opt in channelOptions" :key="opt.channel_seq_no" :label="opt.acc_name" :value="opt.channel_seq_no"></el-option>
                    </el-select>
                </el-form-item>
            </el-col>
            <el-col :span="8">

            </el-col>
        </el-row>

        <el-row>
            <el-divider></el-divider>
            <el-col :span="9">
                <el-form-item prop="remark" label="备注">
                    <el-input type="textarea" v-model="form.remark" placeholder="请输入备注"></el-input>
                </el-form-item>
            </el-col>
        </el-row>
        <el-row>
            <el-button class="fr cancel-class" size="small" @click="closeDialog">取消</el-button>
            <el-button class="fr" type="primary" size="small" @click="commit">提交</el-button>
        </el-row>
    </el-form>
</template>

<script>
    export default {
        name: "query-manual-add",
        data() {
            return {
                validateAccAmount: (rule, value, callback) => {
                    if (value <= 0) {
                        callback(new Error('记账金额不允许为空'));
                    } else {
                        callback();
                    }
                },

                form: {
                    second_busi_type: '',
                    acc_type: '',
                    acc_id: '',
                    acc_amount: '',
                    remark: '',

                    list: [
                        {
                            acc_type: '',
                            acc_id: '',
                            subject_code: '',
                            acc_direction: 'D',
                            acc_amount: '',

                            acc_balance: '',
                            channelOptions: [],
                            subjectOptions: [],
                        },
                        {
                            acc_type: '',
                            acc_id: '',
                            subject_code: '',
                            acc_direction: 'C',
                            acc_amount: '',

                            acc_balance: '',
                            channelOptions: [],
                            subjectOptions: [],
                        },
                    ]
                },

                channelOptions: [],
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
                        console.log(JSON.stringify(this.form));
                    }
                })
            },
            searchChannel(query) {
                if (query !== '') {
                    // this.theAgentLoading = true;
                    setTimeout(() => {
                        // this.theAgentLoading = false;
                        api.get('channel/queryChannelInfo', {acc_name: query}).then(data => {
                            this.channelOptions = data.list;
                        })
                    }, 200);
                } else {
                    this.channelOptions = [];
                }
            }
        }
    }
</script>

<style scoped>
    .cancel-class {
        margin-left: 20px;
    }
</style>
