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
                    <el-select v-model="item.acc_type" placeholder="请选择" @change="changeSelect" @focus="getSelectIndex(index)" clearable>
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
                        :rules="(form.second_busi_type == '05' && item.acc_type == 1) ? {required: true, message: '商户号不能为空'} : {}">
                    <el-input
                            type="text"
                            v-model="item.acc_id"
                            placeholder="请输入商户号"
                            @focus="getBalanceIndex(index)"
                            @change="queryVirtualBalance"
                            clearable></el-input>
                </el-form-item>
                <el-form-item
                        :prop="'list.' + index + '.acc_id'"
                        label="代理商号"
                        v-show="item.acc_type == 2"
                        :rules="(form.second_busi_type == '05' && item.acc_type == 2) ? {required: true, message: '代理商号不能为空'} : {}">
                    <el-input
                            type="text"
                            v-model="item.acc_id"
                            placeholder="请输入代理商号"
                            @focus="getBalanceIndex(index)"
                            @change="queryVirtualBalance"
                            clearable></el-input>
                </el-form-item>
                <el-form-item
                        :prop="'list.' + index + '.acc_id'"
                        label="渠道"
                        v-show="item.acc_type == 3"
                        :rules="(form.second_busi_type == '05' && item.acc_type == 3) ? {required: true, message: '渠道不能为空'} : {}">
                    <el-select
                            v-model="item.acc_id"
                            filterable
                            remote
                            clearable
                            placeholder="请输入渠道关键字"
                            @change="queryVirtualBalance"
                            @focus="getChannelSelectIndex(index)"
                            :remote-method="searchChannel">
                        <el-option v-for="(opt, i) in item.channelOptions" :key="i" :label="opt.acc_name" :value="opt.channel_seq_no"></el-option>
                    </el-select>
                </el-form-item>
            </el-col>
            <el-col :span="8">
                <el-form-item
                        :prop="'list.' + index + '.subject_code'"
                        label="账户类型"
                        v-show="item.acc_type != 4"
                        :rules="(form.second_busi_type == '05' && item.acc_type != 4) ? {required: true, message: '账户类型不能为空'} : {}">
                    <el-select
                            v-model="item.subject_code"
                            @focus="getBalanceIndex(index)"
                            @change="queryVirtualBalance"
                            placeholder="请选择">
                        <el-option v-for="(opt, i) in item.subjectOptions" :key="i" :label="opt.subject_name" :value="opt.subject_code"></el-option>
                    </el-select>
                </el-form-item>
            </el-col>
            <el-col :span="8" v-show="item.acc_type == 4">
                <el-form-item
                        :prop="'list.' + index + '.subject_codes'"
                        label="科目"
                        :rules="(form.second_busi_type == '05' && item.acc_type == 4) ? {required: true, message: '科目不能为空'} : {validator: (rule, value, callback) => {callback()}}">
                    <el-cascader
                            v-model="item.subject_codes"
                            placeholder="请选择(可搜索)"
                            :options="item.subjectOptions"
                            :props="{value: 'subject_code', label: 'code_name', children: 'child'}"
                            change-on-select
                            filterable
                            clearable
                            class="w-300"
                    ></el-cascader>
                </el-form-item>
            </el-col>

            <el-col>
                <el-row>
                    <el-col :span="8">
                        <el-form-item
                                :prop="'list.' + index + '.acc_direction'"
                                label="借贷方向"
                                :rules="form.second_busi_type == '05' ? {required: true, message: '借贷方向不能为空'} : {}">
                            <el-select v-model="item.acc_direction" placeholder="请选择">
                                <el-option label="借" value="D"></el-option>
                                <el-option label="贷" value="C"></el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8">
                        <el-form-item
                                :prop="'list.' + index + '.acc_balance'"
                                label="当前余额">
                            <el-input v-model="item.acc_balance" disabled></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8">
                        <el-form-item
                                :prop="'list.' + index + '.acc_amount'"
                                label="记账金额"
                                :rules="form.second_busi_type == '05' ? {validator: validateAccAmount} : {validator: (rule, value, callback) => {callback()}}">
                            <el-input-number v-model.number="item.acc_amount" :precision="2" :step="1" :min="0"></el-input-number>
                        </el-form-item>
                    </el-col>
                </el-row>
            </el-col>
        </el-row>

        <el-row v-show="form.second_busi_type == '05'">
            <el-divider></el-divider>
            <el-col :span="12">
                <el-form-item label="借方总额">
                    <el-input v-model="borrowTotal" disabled></el-input>
                </el-form-item>
            </el-col>
            <el-col :span="12">
                <el-form-item label="贷方总额">
                    <el-input v-model="loanTotal" disabled></el-input>
                </el-form-item>
            </el-col>
        </el-row>

        <el-row>
            <el-divider></el-divider>
            <el-col :span="9">
                <el-form-item
                        prop="remark"
                        label="备注"
                        :rules="{required: true, message: '备注不能为空'}">
                    <el-input type="textarea" v-model="form.remark" placeholder="请输入备注"></el-input>
                </el-form-item>
            </el-col>
        </el-row>
        <el-row>
            <el-button class="fr cancel-class" size="small" @click="closeDialog">取消</el-button>
            <el-button class="fr" type="primary" size="small" @click="commit">提交</el-button>
            <el-button class="fr" type="success" size="small" @click="addForm" v-show="form.second_busi_type == '05'">加一项</el-button>
            <el-button class="fr" type="danger" size="small" @click="reduceForm" v-show="form.second_busi_type == '05'" :disabled="form.list.length <= 2">减一项</el-button>
            <el-button class="fr" type="info" size="small" @click="checkBalance" v-show="form.second_busi_type == '05'">检查借贷平衡</el-button>
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
                    acc_amount: 0,
                    remark: '',

                    list: [
                        {
                            acc_type: '',
                            acc_id: '',
                            subject_code: '',
                            subject_codes: [],
                            acc_direction: 'D',
                            acc_amount: 0,

                            acc_balance: 0,
                            channelOptions: [],
                            subjectOptions: [],
                        },
                        {
                            acc_type: '',
                            acc_id: '',
                            subject_code: '',
                            subject_codes: [],
                            acc_direction: 'C',
                            acc_amount: 0,

                            acc_balance: 0,
                            channelOptions: [],
                            subjectOptions: [],
                        },
                    ]
                },

                selectIndex: 0,
                channelSelectIndex: 0,
                accBalanceIndex: 0,

                borrowTotal: 0,
                loanTotal: 0,
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
                        let param = this.checkBalance();
                        if (param == false) {
                            return false;
                        }
                        console.log(param);
                        api.post('balance/addManual', param).then(() => {
                            this.$message.success('手工记账录入成功');
                            this.closeDialog();
                            this.$emit('addSuccess');
                        })
                    }
                })
            },
            searchChannel(query) {
                if (query !== '') {
                    setTimeout(() => {
                        api.get('channel/queryChannelInfo', {acc_name: query}).then(data => {
                            this.form.list[this.channelSelectIndex].channelOptions = data.list;
                        })
                    }, 200);
                } else {
                    this.form.list[this.channelSelectIndex].channelOptions = [];
                }
            },
            changeSelect(val) {
                this.form.list[this.selectIndex].acc_id = '';
                this.form.list[this.selectIndex].subject_code = '';
                this.form.list[this.selectIndex].subject_codes = [];

                if (val == 4) {
                    api.get('balance/subjectSelect').then(data => {
                        this.form.list[this.selectIndex].subjectOptions = data;
                    })
                } else {
                    let type = (val == 1 || val == 2) ? 1 : 2;
                    let param = {
                        page: 1,
                        pageSize: 100,
                        subject_relationship_property: type
                    };
                    api.get('subject/list', param).then(data => {
                        this.form.list[this.selectIndex].subjectOptions = data.list;
                    })
                }
            },
            getSelectIndex(index) {
                this.selectIndex = index;
            },
            getChannelSelectIndex(index) {
                this.channelSelectIndex = index;
                this.accBalanceIndex = index;
            },
            getBalanceIndex(index) {
                this.accBalanceIndex = index;
            },
            queryVirtualBalance() {
                let param = {
                    acc_type: this.form.list[this.accBalanceIndex].acc_type,
                    acc_id: this.form.list[this.accBalanceIndex].acc_id,
                    subject_code: this.form.list[this.accBalanceIndex].subject_code,
                };
                api.get('balance/queryVirtualBalance', param).then(data => {
                    if (data.data.hasOwnProperty('acc_amount')) {
                        this.form.list[this.accBalanceIndex].acc_balance = (data.data.acc_amount / 100).toFixed(2);
                    } else {
                        this.form.list[this.accBalanceIndex].acc_balance = '0.00';
                    }
                })
            },
            reduceForm() {
                let length = this.form.list.length;
                if (length <= 2) {
                    this.$message.error('记账主体至少要有两个');
                    return;
                }
                this.form.list.splice(length - 1, 1);
            },
            addForm() {
                this.form.list.push({
                    acc_type: '',
                    acc_id: '',
                    subject_code: '',
                    subject_codes: [],
                    acc_direction: 'D',
                    acc_amount: 0,

                    acc_balance: 0,
                    channelOptions: [],
                    subjectOptions: [],
                });
            },
            checkBalance() {
                let borow = 0;
                let loan = 0;

                let param = {};
                let list = [];

                this.form.list.forEach(item => {
                    if (item.acc_direction == 'D') {
                        borow += parseFloat(item.acc_amount);
                    } else {
                        loan += parseFloat(item.acc_amount);
                    }

                    list.push({
                        acc_type: item.acc_type,
                        acc_id: item.acc_id,
                        subject_code: item.subject_code,
                        subject_codes: item.subject_codes,
                        acc_direction: item.acc_direction,
                        acc_amount: item.acc_amount,
                    });
                });
                borow = borow.toFixed(2);
                loan = loan.toFixed(2);

                this.borrowTotal = borow;
                this.loanTotal = loan;

                if (borow != loan && this.form.second_busi_type == '05') {
                    this.$message.error('借贷不平衡');
                    return false;
                }
                param = {
                    second_busi_type: this.form.second_busi_type,
                    acc_type: this.form.acc_type,
                    acc_id: this.form.acc_id,
                    acc_amount: this.form.acc_amount,
                    remark: this.form.remark,

                    list: list,
                };
                return param;
            }
        }
    }
</script>

<style scoped>
    .cancel-class {
        margin-left: 10px;
    }
</style>
