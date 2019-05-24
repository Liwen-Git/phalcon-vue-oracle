<template>
    <el-form :model="form" ref="form" :rules="formRules" size="small" label-width="150px">
        <el-row>
            <el-col :span="12">
                <el-form-item label="分润汇总单号">
                    {{theReport.agentps_sum_id}}
                </el-form-item>
                <el-form-item label="代理商号">
                    {{theReport.agent_id}}
                </el-form-item>
                <el-form-item label="商户号">
                    {{theReport.merchant_id}}
                </el-form-item>
                <el-form-item label="业务类型">
                    {{theReport.busi_type}}
                </el-form-item>
                <el-form-item label="交易总金额(元)">
                    {{theReport.tran_amt}}
                </el-form-item>
                <el-form-item label="成功分润交易金额(元)">
                    {{theReport.pssucc_tran_amt}}
                </el-form-item>
                <el-form-item label="未分润交易金额(元)">
                    {{theReport.psfail_tran_amt}}
                </el-form-item>
                <el-form-item label="分润金额(元)">
                    {{theReport.agentps_amt}}
                </el-form-item>
                <el-form-item label="分润金额(元)">
                    {{theReport.agentps_amt}}
                </el-form-item>
                <el-form-item prop="other_amt" label="调整金额（元）">
                    <span v-if="theReport.state != '0'">{{theReport.other_amt}}</span>
                    <span v-else>
                        <el-input-number v-model.number="form.other_amt" :precision="2" :step="1" :min="0"></el-input-number>
                    </span>
                </el-form-item>
                <el-form-item label="收款账户名">
                    {{theReport.receive_account_name}}
                </el-form-item>
                <el-form-item label="开户行">
                    {{theReport.receive_account_bank}}
                </el-form-item>
                <el-form-item prop="memo" label="备注">
                    <el-input type="textarea" v-model="form.memo" placeholder="请输入备注"></el-input>
                </el-form-item>
            </el-col>
            <el-col :span="12">
                <el-form-item label="分润日期">
                    {{theReport.ps_date}}
                </el-form-item>
                <el-form-item label="代理商名称">
                    {{theReport.agent_name}}
                </el-form-item>
                <el-form-item label="商户名称">
                    {{theReport.merchant_name}}
                </el-form-item>
                <el-form-item label="分润周期">
                    {{theReport.profit_share_cycle}}
                </el-form-item>
                <el-form-item label="交易总笔数">
                    {{theReport.tran_cnt}}
                </el-form-item>
                <el-form-item label="成功分润交易笔数">
                    {{theReport.pssucc_tran_cnt}}
                </el-form-item>
                <el-form-item label="未分润交易笔数">
                    {{theReport.psfail_tran_cnt}}
                </el-form-item>
                <el-form-item label="亏损金额(元)">
                    {{theReport.loss_amt}}
                </el-form-item>
                <el-form-item label="实际分润金额(元)">
                    {{theReport.real_agentps_amt}}
                </el-form-item>
                <el-form-item label="收款账户">
                    {{theReport.receive_account_id}}
                </el-form-item>
                <el-form-item label="分润状态">
                    <el-select v-model="theReport.state" disabled>
                        <el-option label="待审核" value="0"></el-option>
                        <el-option label="待确认" value="1"></el-option>
                        <el-option label="待付款" value="2"></el-option>
                        <el-option label="关闭" value="3"></el-option>
                        <el-option label="划款成功" value="4"></el-option>
                        <el-option label="划款失败" value="5"></el-option>
                        <el-option label="代理商回退" value="6"></el-option>
                    </el-select>
                </el-form-item>
            </el-col>
        </el-row>

        <el-row>
            <el-button class="fr cancel-class" size="small" @click="closeDialog">取消</el-button>
            <el-button class="fr" type="primary" size="small" @click="commit">确定</el-button>
        </el-row>
    </el-form>
</template>

<script>
    export default {
        name: "agent-profit-sharing-edit",
        props: {
            theReport: {
                required: true,
                type: Object,
            }
        },
        data() {
            let validateOtherAmt = (rule, value, callback) => {
                if (this.theReport.state == '0' && !value) {
                    callback(new Error('调整金额不能为空'));
                } else {
                    callback();
                }
            };

            return {
                form: {
                    agentps_sum_id: '',
                    other_amt: 0,
                    memo: '',
                },
                formRules: {
                    other_amt: [
                        {validator: validateOtherAmt}
                    ]
                }
            }
        },
        methods: {
            closeDialog() {
                this.$emit('close');
                this.$refs.form.clearValidate();
            },
            commit() {
                this.$refs.form.validate(valid => {
                    if (valid) {
                        let param = {
                            agentps_sum_id: this.form.agentps_sum_id,
                            memo: this.form.memo,
                        };
                        if (this.theReport.state == '0') {
                            param.other_amt = this.form.other_amt;
                        }
                        api.post('report_of_agent/edit', param).then(() => {
                            this.$message.success('代理商分润编辑成功');
                            this.closeDialog();
                            this.$emit('editSuccess');
                        })
                    }
                })
            }
        },
        created() {
            this.form.agentps_sum_id = this.theReport.agentps_sum_id;
            this.form.other_amt = this.theReport.other_amt;
        },
        watch: {
            theReport() {
                this.form.agentps_sum_id = this.theReport.agentps_sum_id;
                this.form.other_amt = this.theReport.other_amt;
                this.form.memo = '';
            }
        }
    }
</script>

<style scoped>
    .cancel-class {
        margin-left: 10px;
    }
</style>
