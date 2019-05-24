<template>
    <el-form size="small" label-width="150px">
        <el-form-item label="已选中笔数">
            <el-input type="text" v-model="auditNum" disabled>
                <template slot="append">笔</template>
            </el-input>
        </el-form-item>
        <el-form-item label="实际分润金额总计">
            <el-input type="text" v-model="parseFloat(auditAmount).toFixed(2)" disabled>
                <template slot="append">元</template>
            </el-input>
        </el-form-item>
        <el-form-item label="备注">
            <el-input type="textarea" v-model="memo" placeholder="请输入内容"></el-input>
        </el-form-item>

        <el-row>
            <el-button class="fr cancel-class" size="small" @click="closeDialog">取消</el-button>
            <el-button class="fr" type="primary" size="small" @click="commit(1)">审核通过</el-button>
            <el-button class="fr" type="danger" size="small" @click="commit(3)">审核拒绝</el-button>
        </el-row>
    </el-form>
</template>

<script>
    export default {
        name: "agent-profit-sharing-audit",
        props: {
            auditNum: {
                required: true
            },
            auditAmount: {
                required: true
            },
            auditForm: {
                required: true,
                type: Object,
            },
            auditIds: {
                required: true
            },
            allSelection: {
                required: true,
                type: Boolean,
            }
        },
        data() {
            return {
                memo: '',
            }
        },
        methods: {
            closeDialog() {
                this.memo = '';
                this.$emit('close');
            },
            commit(state) {
                let param = {};
                if (this.allSelection) {
                    // 全部审核
                    param = this.auditForm;
                    param.changestate = state;
                    param.memo = this.memo;
                    param.is_all = 1;
                } else {
                    param = {
                        changestate: state,
                        memo: this.memo,
                        is_all: 0,
                        agentps_sum_ids: this.auditIds,
                    };
                }
                api.post('report_of_agent/audit', param).then(() => {
                    this.$message.success('代理商分润审核成功');
                    this.closeDialog();
                    this.$emit('auditSuccess');
                });
            }
        },
        watch: {
            auditAmount() {
                this.memo = '';
            },
            auditNum() {
                this.memo = '';
            }
        }
    }
</script>

<style scoped>
    .cancel-class {
        margin-left: 10px;
    }
</style>
