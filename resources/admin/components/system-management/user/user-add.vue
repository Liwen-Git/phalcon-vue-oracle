<template>
    <el-form :model="form" ref="form" :rules="formRules" size="small" label-width="100px">
        <el-form-item prop="account" label="用户名">
            <el-input type="text" v-model="form.account" placeholder="请输入用户名" clearable></el-input>
        </el-form-item>
        <el-form-item prop="name" label="姓名">
            <el-input type="text" v-model="form.name" placeholder="请输入姓名" clearable></el-input>
        </el-form-item>
        <el-form-item prop="password" label="密码">
            <el-input type="password" v-model="form.password" placeholder="请输入密码" show-password></el-input>
        </el-form-item>
        <el-form-item prop="confirmPassword" label="确认密码">
            <el-input type="password" v-model="form.confirmPassword" placeholder="请输入确认密码" show-password></el-input>
        </el-form-item>
        <el-form-item prop="phone" label="绑定手机号">
            <el-input type="text" v-model="form.phone" placeholder="请输入手机号" clearable></el-input>
        </el-form-item>
        <el-form-item prop="roleIds" label="所属角色">
            <el-checkbox-group v-model="form.roleIds">
                    <el-checkbox v-for="(item, key) in roles" :key="key" :label="item.role_id">{{item.role_name}}</el-checkbox>
            </el-checkbox-group>
        </el-form-item>
        <el-form-item>
            <el-button size="small" type="primary" @click="commit">确定</el-button>
            <el-button size="small" @click="closeDialog">取消</el-button>
        </el-form-item>
    </el-form>
</template>

<script>
    export default {
        name: "user-add",
        data() {
            let validateConfirmPassword = (rule, value, callback) => {
                if (value === '') {
                    callback(new Error('请再次输入确认密码'));
                } else if (value !== this.form.password) {
                    callback(new Error('两次输入的密码不一致!'));
                } else {
                    callback();
                }
            };

            return {
                form: {
                    account: '',
                    name: '',
                    phone: '',
                    password: '',
                    confirmPassword: '',
                    roleIds: [],
                },
                roles: [],

                formRules: {
                    account: [
                        {required: true, message: '用户名不能为空'}
                    ],
                    name: [
                        {required: true, message: '姓名不能为空'}
                    ],
                    password: [
                        {required: true, message: '密码不能为空'},
                        {min: 6, max: 30, message: '密码必须在6-30位之间'},
                    ],
                    confirmPassword: [
                        {validator: validateConfirmPassword}
                    ],
                    phone: [
                        {required: true, message: '绑定手机号不能为空'},
                    ],
                    roleIds: [
                        {type: 'array', required: true, message: '所属角色不能为空', trigger: 'change'}
                    ]
                }
            }
        },
        methods: {
            getRoles() {
                api.get('role/role_names').then(data => {
                    this.roles = data;
                })
            },
            closeDialog() {
                this.$emit('close');
                this.$refs.form.resetFields();
            },
            commit() {
                this.$refs.form.validate(valid => {
                    if (valid) {
                        api.post('user/add', this.form).then(data => {
                            this.$emit('addSuccess');
                            this.$message.success('添加用户成功');
                            this.$refs.form.resetFields();
                        })
                    }
                })
            }
        },
        created() {
            this.getRoles();
        }
    }
</script>

<style scoped>

</style>
