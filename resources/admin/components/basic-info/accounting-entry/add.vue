<template>
    <el-form :model="form" ref="form" :rules="formRules" size="small" label-width="100px">
        <el-form-item prop="business_types" label="业务类型">
            <el-cascader
                    v-model="form.business_types"
                    placeholder="请选择(可搜索)"
                    :options="businessTypes"
                    :props="{value: 'type', label: 'name', children: 'child'}"
                    filterable
                    clearable
                    class="w-300"
            ></el-cascader>
        </el-form-item>
        <el-row v-for="(item, index) in form.data" :key="index">
            <el-divider></el-divider>
            <el-form-item
                    :prop="'data.' + index + '.subject_code'"
                    label="科目"
                    :rules="{required: true, message: '科目不能为空'}">
                <el-cascader
                        v-model="item.subject_code"
                        placeholder="请选择(可搜索)"
                        :options="subjectArr"
                        :props="{value: 'subject_code', label: 'code_name', children: 'child'}"
                        change-on-select
                        filterable
                        clearable
                        class="w-300"
                ></el-cascader>
            </el-form-item>
            <el-form-item
                    :prop="'data.' + index + '.depit_credit_dir'"
                    label="借贷方向"
                    :rules="{required: true, message: '借贷方向不能为空'}">
                <el-select v-model="item.depit_credit_dir" clearable>
                    <el-option label="借" value="D"></el-option>
                    <el-option label="贷" value="C"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item
                    :prop="'data.' + index + '.is_real'"
                    label="实时记账"
                    :rules="{required: true, message: '实时记账不能为空'}">
                <el-select v-model="item.is_real" clearable>
                    <el-option label="是" value="1"></el-option>
                    <el-option label="否" value="0"></el-option>
                </el-select>
            </el-form-item>
        </el-row>
        <el-row>
            <el-button class="fr commit-class" size="small" @click="closeDialog">取消</el-button>
            <el-button class="fr" type="primary" size="small" @click="commit">提交</el-button>
            <el-button class="fr" type="success" size="small" @click="addEntry">加一项</el-button>
            <el-button class="fr" type="danger" size="small" @click="reduceEntry" :disabled="form.data.length <= 1">减一项</el-button>
        </el-row>
    </el-form>
</template>

<script>
    export default {
        name: "accounting-entry-add",
        props: {
            businessTypes: {
                required: true,
                type: Array,
            }
        },
        data() {
            return {
                form: {
                    business_types: [],
                    data: [
                        {
                            subject_code: [],
                            depit_credit_dir: '',
                            is_real: '',
                        }
                    ]
                },
                subjectArr: [],

                formRules: {
                    business_types: [
                        {required: true, message: '业务类型不能为空'}
                    ]
                }
            }
        },
        methods: {
            getSubject() {
                api.get('accounting_entry/subjectSelect').then(data => {
                    this.subjectArr = data;
                })
            },
            addEntry() {
                this.form.data.push({
                    subject_code: [],
                    depit_credit_dir: '',
                    is_real: '',
                });
            },
            reduceEntry() {
                let length = this.form.data.length;
                if (length <= 1) {
                    this.$message.error('科目至少要有一个');
                    return;
                }
                this.form.data.splice(length - 1, 1);
            },
            closeDialog() {
                this.$emit('close');
                this.$refs.form.resetFields();
                this.form.data = [
                    {
                        subject_code: [],
                        depit_credit_dir: '',
                        is_real: '',
                    },
                ];
            },
            commit() {
                this.$refs.form.validate(valid => {
                    if (valid) {
                        api.post('accounting_entry/add', this.form).then(() => {
                            this.$message.success('会计分录录入成功');
                            this.$emit('addSuccess');
                            this.closeDialog();
                        })
                    }
                })
            }
        },
        created() {
            this.getSubject();
        }
    }
</script>

<style scoped>
    .commit-class {
        margin-left: 10px;
    }
</style>
