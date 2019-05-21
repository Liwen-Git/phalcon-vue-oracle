<template>
    <el-form :model="form" ref="form" :rules="formRules" size="small" label-width="100px">
        <el-form-item label="可用余额">
            <span>{{available_amount}}</span>
        </el-form-item>
        <el-form-item prop="acc_amount" label="冻结金额">
            <el-input-number v-model="form.acc_amount" :precision="2" :step="1" :min="0" :max="parseFloat(available_amount)"></el-input-number>
        </el-form-item>
        <el-form-item prop="remark" label="备注">
            <el-input type="textarea" v-model="form.remark" placeholder="请输入备注"></el-input>
        </el-form-item>
        <el-row>
            <el-button class="fr cancel-class" size="small" @click="closeDialog">取消</el-button>
            <el-button class="fr" type="primary" size="small" @click="commit">确定</el-button>
        </el-row>
    </el-form>
</template>

<script>
    export default {
        name: "balance-frozen",
        props: {
            theBalance: {
                required: true,
                type: Object,
            }
        },
        data() {
            let validateAccAmount = (rule, value, callback) => {
                if (value <= 0) {
                    callback(new Error('冻结金额不允许为空'));
                } else {
                    callback();
                }
            };

            return {
                form: {
                    merchant_id: '',
                    acc_amount: 0,
                    remark: '',
                },
                available_amount: '',

                formRules: {
                    acc_amount: [
                        {validator: validateAccAmount}
                    ]
                }
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
                        api.post('balance/frozen', this.form).then(() => {
                            this.$message.success('金额冻结成功');
                            this.closeDialog();
                            this.$emit('frozenSuccess');
                        })
                    }
                })
            },
            initForm() {
                this.available_amount = (this.theBalance.available_amount / 100).toFixed(2);
                this.form.merchant_id = this.theBalance.merchant_id;
            }
        },
        created() {
            this.initForm();
        },
        watch: {
            theBalance() {
                this.initForm();
            }
        }
    }
</script>

<style scoped>
    .cancel-class {
        margin-left: 20px;
    }
</style>
