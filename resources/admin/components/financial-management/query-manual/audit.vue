<template>
    <el-row>
        <el-table :data="list" stripe>
            <el-table-column prop="acc_inc" label="流水号"></el-table-column>
            <el-table-column prop="acc_id" label="记账主体"></el-table-column>
            <el-table-column prop="acc_name" label="记账主体名称"></el-table-column>
            <el-table-column prop="subject_name" label="账户类型">
                <template slot-scope="scope">
                    {{scope.row.acc_type != '4' ? scope.row.subject_name : ''}}
                </template>
            </el-table-column>
            <el-table-column prop="acc_type" label="记账主体类型">
                <template slot-scope="scope">
                    <span v-if="scope.row.acc_type == 1">商户</span>
                    <span v-else-if="scope.row.acc_type == 2">代理商</span>
                    <span v-else-if="scope.row.acc_type == 3">渠道</span>
                    <span v-else-if="scope.row.acc_type == 4">内部科目</span>
                    <span v-else>未知({{scope.row.acc_type}})</span>
                </template>
            </el-table-column>
            <el-table-column prop="acc_direction" label="借贷方向">
                <template slot-scope="scope">
                    {{scope.row.acc_direction == 'D' ? '借方' : '贷方'}}
                </template>
            </el-table-column>
            <el-table-column prop="acc_amount" label="记账金额">
                <template slot-scope="scope">
                    {{(scope.row.acc_amount / 100).toFixed(2)}}
                </template>
            </el-table-column>
        </el-table>

        <el-row class="btn-class">
            <el-button class="fr cancel-class" size="small" @click="closeDialog">取消</el-button>
            <el-button class="fr" type="primary" size="small" @click="audit('2')">审核通过</el-button>
            <el-button class="fr" type="danger" size="small" @click="audit('3')">审核拒绝</el-button>
        </el-row>
    </el-row>
</template>

<script>
    export default {
        name: "query-manual-audit",
        props: {
            theAccInc: {
                required: true,
            }
        },
        data() {
            return {
                list: [],
            }
        },
        methods: {
            getList() {
                api.get('balance/getCheckManualList', {acc_inc: this.theAccInc}).then(data => {
                    this.list = data.list;
                })
            },
            closeDialog() {
                this.$emit('close');
            },
            audit(state) {
                let param = {
                    acc_inc: this.theAccInc,
                    verify_state: state,
                };
                api.post('balance/manualAudit', param).then(() => {
                    this.$message.success('手工记账审核成功');
                    this.closeDialog();
                    this.$emit('success');
                })
            }
        },
        created() {
            this.getList();
        },
        watch: {
            theAccInc() {
                this.getList();
            }
        }
    }
</script>

<style scoped>
    .cancel-class {
        margin-left: 10px;
    }
    .btn-class {
        margin-top: 20px;
    }
</style>
