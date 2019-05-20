<template>
    <el-form :model="form" ref="form" size="small" label-width="100px">
        <el-form-item label="业务大类">
            <el-input type="text" v-model="form.busy_type_name" disabled></el-input>
        </el-form-item>
        <el-form-item label="业务小类">
            <el-input type="text" v-model="form.second_busi_name" disabled></el-input>
        </el-form-item>
        <el-form-item label="业务子类">
            <el-input type="text" v-model="form.sub_busi_name" disabled></el-input>
        </el-form-item>
        <el-form-item label="科目代码">
            <el-input type="text" v-model="form.subject_code" disabled></el-input>
        </el-form-item>
        <el-form-item label="科目名称">
            <el-input type="text" v-model="form.subject_name" disabled></el-input>
        </el-form-item>
        <el-form-item label="借贷方向">
            <el-select v-model="form.depit_credit_dir">
                <el-option label="借" value="D"></el-option>
                <el-option label="贷" value="C"></el-option>
            </el-select>
        </el-form-item>
        <el-form-item label="实时记账">
            <el-select v-model="form.is_real">
                <el-option label="是" value="1"></el-option>
                <el-option label="否" value="0"></el-option>
            </el-select>
        </el-form-item>
        <el-row>
            <el-button class="fr commit-class" size="small" @click="closeDialog">取消</el-button>
            <el-button class="fr" type="primary" size="small" @click="commit">提交</el-button>
        </el-row>
    </el-form>
</template>

<script>
    export default {
        name: "accounting-entry-edit",
        props: {
            theEntry: {
                required: true,
                type: Object,
            }
        },
        data() {
            return {
                form: {
                    busi_type: '',
                    second_busi_type: '',
                    sub_busi_type: '',
                    subject_code: '',
                    depit_credit_dir: '',
                    is_real: '',
                }
            }
        },
        methods: {
            closeDialog() {
                this.$emit('close');
            },
            commit() {
                let param = {
                    busi_type: this.form.busi_type,
                    second_busi_type: this.form.second_busi_type,
                    sub_busi_type: this.form.sub_busi_type,
                    subject_code: this.form.subject_code,
                    depit_credit_dir: this.form.depit_credit_dir,
                    is_real: this.form.is_real,
                };
                api.post('accounting_entry/edit', param).then(() => {
                    this.$message.success('会计分录编辑成功');
                    this.closeDialog();
                    this.$emit('editSuccess');
                })
            }
        },
        created() {
            this.form = this.theEntry;
        },
        watch: {
            theEntry() {
                this.form = this.theEntry;
            }
        }
    }
</script>

<style scoped>
    .commit-class {
        margin-left: 10px;
    }
</style>
