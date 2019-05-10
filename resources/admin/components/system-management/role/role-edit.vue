<template v-loading="tmpLoading">
    <el-form :model="form" ref="form" :rules="formRules" size="small" label-width="100px">
        <el-form-item prop="roleName" label="角色名">
            <el-input type="text" v-model="form.roleName" placeholder="请输入角色名" clearable></el-input>
        </el-form-item>
        <el-form-item prop="status" label="状态">
            <el-select v-model="form.status" placeholder="请选择">
                <el-option label="正常" value="1"></el-option>
                <el-option label="禁用" value="2"></el-option>
            </el-select>
        </el-form-item>
        <el-form-item prop="remark" label="角色描述">
            <el-input type="textarea" v-model="form.remark" placeholder="请输入角色描述" clearable></el-input>
        </el-form-item>
        <el-form-item prop="actions" label="权限">
            <el-tree
                :data="actionMenu"
                show-checkbox
                default-expand-all
                node-key="id"
                ref="tree"
                highlight-current
                :props="{children: 'sub', label: 'name'}"
            ></el-tree>
        </el-form-item>
        <el-form-item>
            <el-button size="small" type="primary" @click="commit">确定</el-button>
            <el-button size="small" @click="closeDialog">取消</el-button>
        </el-form-item>
    </el-form>
</template>

<script>
    export default {
        name: "role-add",
        props: {
            role: {
                type: Object,
                required: true,
            }
        },
        data() {
            return {
                tmpLoading: false,
                form: {
                    roleId: '',
                    roleName: '',
                    status: '',
                    remark: '',
                    actions: [],
                },
                actionMenu: [],

                formRules: {
                    roleName: [
                        {required: true, message: '角色名称不能为空'},
                    ],
                    status: [
                        {required: true, message: '状态不能为空'},
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
                        this.form.actions = this.$refs.tree.getCheckedNodes();
                        api.post('role/edit', this.form).then(() => {
                            this.$message.success('角色编辑成功');
                            this.$emit('editSuccess');
                            this.closeDialog();
                        })
                    }
                });
            },
            getActionMenu() {
                this.tmpLoading = true;
                api.get('role/action_menu').then(data => {
                    this.actionMenu = data;
                    this.getSelectActionMenu(this.role.role_id);
                    this.tmpLoading = false;
                })
            },
            getSelectActionMenu(roleId) {
                api.get('role/select_action_menu', {roleId: roleId}).then(data => {
                    this.form.actions = data;
                    this.$refs.tree.setCheckedKeys(this.form.actions);
                })
            },
            initForm() {
                this.form.roleId = this.role.role_id;
                this.form.roleName = this.role.role_name;
                this.form.status = this.role.status;
                this.form.remark = this.role.remark;
            }
        },
        created() {
            this.initForm();
            this.getActionMenu();
        },
        mounted() {
            console.log(this.form.actions);
        },
        watch: {
            role() {
                this.initForm();
                this.getSelectActionMenu(this.role.role_id);
            }
        }
    }
</script>

<style scoped>

</style>
