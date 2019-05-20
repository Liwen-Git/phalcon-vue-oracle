<template>
    <el-form :model="form" ref="form" :rules="formRules" size="small" label-width="130px">
        <el-form-item prop="busi_type" label="业务大类代码">
            <el-input type="text" v-model="form.busi_type" placeholder="请输入业务大类代码" clearable></el-input>
        </el-form-item>
        <el-form-item prop="busy_type_name" label="业务大类名称">
            <el-input type="text" v-model="form.busy_type_name" placeholder="请输入业务大类名称" clearable></el-input>
        </el-form-item>
        <el-form-item prop="second_busi_type" label="业务小类代码">
            <el-input type="text" v-model="form.second_busi_type" placeholder="请输入业务小类代码" clearable></el-input>
        </el-form-item>
        <el-form-item prop="second_busi_name" label="业务小类名称">
            <el-input type="text" v-model="form.second_busi_name" placeholder="请输入业务小类名称" clearable></el-input>
        </el-form-item>
        <el-form-item prop="sub_busi_type" label="业务子类代码">
            <el-input type="text" v-model="form.sub_busi_type" placeholder="请输入业务子类代码" clearable></el-input>
        </el-form-item>
        <el-form-item prop="sub_busi_name" label="业务子类名称">
            <el-input type="text" v-model="form.sub_busi_name" placeholder="请输入业务子类名称" clearable></el-input>
        </el-form-item>
        <el-form-item prop="remark" label="备注">
            <el-input type="textarea" v-model="form.remark" placeholder="请输入备注"></el-input>
        </el-form-item>
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
        name: "business-type-add",
        data() {
            return {
                form: {
                    busi_type: '',
                    busy_type_name: '',
                    second_busi_type: '',
                    second_busi_name: '',
                    sub_busi_type: '',
                    sub_busi_name: '',
                    remark: '',
                },

                formRules: {
                    busi_type: [
                        {required: true, message: '业务大类代码不允许为空'}
                    ],
                    busy_type_name: [
                        {required: true, message: '业务大类名称不允许为空'}
                    ],
                    second_busi_type: [
                        {required: true, message: '业务小类代码不允许为空'}
                    ],
                    second_busi_name: [
                        {required: true, message: '业务小类名称不允许为空'}
                    ],
                    sub_busi_type: [
                        {required: true, message: '业务子类代码不允许为空'}
                    ],
                    sub_busi_name: [
                        {required: true, message: '业务子类名称不允许为空'}
                    ],
                    remark: [
                        {required: true, message: '备注不允许为空'}
                    ],
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
                        api.post('business_type/add', this.form).then(data => {
                            this.$message.success('新增业务类型成功');
                            this.$emit('addSuccess');
                            this.closeDialog();
                        })
                    }
                })
            }
        }
    }
</script>

<style scoped>
    .cancel-class {
        margin-left: 20px;
    }
</style>
