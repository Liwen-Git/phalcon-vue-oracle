<template>
    <el-form :model="form" ref="form" size="small" label-width="150px">
        <el-row>
            <el-col :span="12">
                <el-form-item label="分润汇总单号">
                    {{theCheck.agentps_sum_id}}
                </el-form-item>
                <el-form-item label="代理商号">
                    {{theCheck.agent_id}}
                </el-form-item>
                <el-form-item label="商户号">
                    {{theCheck.merchant_id}}
                </el-form-item>
                <el-form-item label="业务类型">
                    {{theCheck.busi_type}}
                </el-form-item>
                <el-form-item label="交易总金额(元)">
                    {{theCheck.tran_amt}}
                </el-form-item>
                <el-form-item label="成功分润交易金额(元)">
                    {{theCheck.pssucc_tran_amt}}
                </el-form-item>
                <el-form-item label="未分润交易金额(元)">
                    {{theCheck.psfail_tran_amt}}
                </el-form-item>
                <el-form-item label="分润金额(元)">
                    {{theCheck.agentps_amt}}
                </el-form-item>
                <el-form-item label="调整金额（元）">
                    <span>{{theCheck.other_amt}}</span>
                </el-form-item>
                <el-form-item label="收款账户名">
                    {{theCheck.receive_account_name}}
                </el-form-item>
                <el-form-item label="开户行">
                    {{theCheck.receive_account_bank}}
                </el-form-item>
                <el-form-item prop="check_opinion" label="审核意见">
                    <el-input type="textarea" v-model="form.check_opinion" placeholder="请输入备注"></el-input>
                </el-form-item>
            </el-col>
            <el-col :span="12">
                <el-form-item label="分润日期">
                    {{theCheck.ps_date}}
                </el-form-item>
                <el-form-item label="代理商名称">
                    {{theCheck.agent_name}}
                </el-form-item>
                <el-form-item label="商户名称">
                    {{theCheck.merchant_name}}
                </el-form-item>
                <el-form-item label="分润周期">
                    {{theCheck.profit_share_cycle}}
                </el-form-item>
                <el-form-item label="交易总笔数">
                    {{theCheck.tran_cnt}}
                </el-form-item>
                <el-form-item label="成功分润交易笔数">
                    {{theCheck.pssucc_tran_cnt}}
                </el-form-item>
                <el-form-item label="未分润交易笔数">
                    {{theCheck.psfail_tran_cnt}}
                </el-form-item>
                <el-form-item label="亏损金额(元)">
                    {{theCheck.loss_amt}}
                </el-form-item>
                <el-form-item label="实际分润金额(元)">
                    {{theCheck.real_agentps_amt}}
                </el-form-item>
                <el-form-item label="收款账户">
                    {{theCheck.receive_account_id}}
                </el-form-item>
                <el-form-item label="分润状态">
                    <el-select v-model="theCheck.state" disabled>
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
            <el-button class="fr" type="primary" size="small" @click="commit(1)">审核通过</el-button>
            <el-button class="fr" type="danger" size="small" @click="commit(3)">审核拒绝</el-button>
        </el-row>
    </el-form>
</template>

<script>
    export default {
        name: "agent-profit-sharing-check",
        props: {
            theCheck: {
                required: true,
                type: Object,
            }
        },
        data() {
            return {
                form: {
                    agentps_sum_id: '',
                    check_opinion: '',
                },
            }
        },
        methods: {
            closeDialog() {
                this.$emit('close');
            },
            commit(changestate) {
                let param = {
                    agentps_sum_id: this.form.agentps_sum_id,
                    check_opinion: this.form.check_opinion,
                    changestate: changestate,
                };
                api.post('report_of_agent/check', param).then(() => {
                    this.$message.success('代理商分润审核成功');
                    this.closeDialog();
                    this.$emit('checkSuccess');
                })
            }
        },
        created() {
            this.form.agentps_sum_id = this.theCheck.agentps_sum_id;
        },
        watch: {
            theCheck() {
                this.form.agentps_sum_id = this.theCheck.agentps_sum_id;
                this.form.check_opinion = '';
            }
        }
    }
</script>

<style scoped>
    .cancel-class {
        margin-left: 10px;
    }
</style>
