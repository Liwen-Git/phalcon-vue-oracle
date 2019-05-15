<template>
    <el-form :model="form" ref="form" :rules="formRules" size="small" label-width="100px">
        <el-row>
            <el-col :span="12">
                <el-form-item prop="subject_level" label="科目级别">
                    <el-select v-model="form.subject_level" @change="subjectLevelChange" class="w-200">
                        <el-option v-for="(item,key) in subjectLevelArr" :key="key" :label="item" :value="key"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item prop="subject_code" label="科目代码">
                    <el-input type="text" v-model="form.subject_code" placeholder="请输入科目代码" clearable class="w-200"></el-input>
                </el-form-item>
                <el-form-item prop="subject_type" label="科目类型">
                    <el-select v-model="form.subject_type" class="w-200">
                        <el-option v-for="(item,key) in subjectTypeArr" :key="key" :label="item" :value="key"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item prop="balance_direction" label="余额方向">
                    <el-select v-model="form.balance_direction" class="w-200">
                        <el-option label="借方" value="D"></el-option>
                        <el-option label="贷方" value="C"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item prop="zero_flag" label="余额零标志">
                    <el-select v-model="form.zero_flag" class="w-200">
                        <el-option label="不限制" value="N"></el-option>
                        <el-option label="日终等于零" value="D"></el-option>
                        <el-option label="月终等于零" value="M"></el-option>
                        <el-option label="年终等于零" value="Y"></el-option>
                    </el-select>
                </el-form-item>
            </el-col>
            <el-col :span="12">
                <el-form-item prop="parent_subject_str" label="父级科目">
                    <el-select v-model="form.parent_subject_str" @change="parentSubjectChange" class="w-200">
                        <el-option v-for="(item, index) in parentSubjectArr" :key="index" :label="item.subject_code + item.subject_name" :value="item.subject_code + '.' + item.subject_name">
                            <span>{{item.subject_code}}</span>
                            <span>{{item.subject_name}}</span>
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item prop="subject_name" label="科目名称">
                    <el-input type="text" v-model="form.subject_name" placeholder="请输入科目名称" clearable class="w-200"></el-input>
                </el-form-item>
                <el-form-item prop="has_sub" label="有无子目">
                    <el-select v-model="form.has_sub" class="w-200">
                        <el-option label="有" value="1"></el-option>
                        <el-option label="无" value="0"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item prop="subject_relationship_property" label="科目关联性">
                    <el-select v-model="form.subject_relationship_property" class="w-200">
                        <el-option label="商户相关科目" value="1"></el-option>
                        <el-option label="渠道相关科目" value="2"></el-option>
                        <el-option label="内部虚户科目" value="3"></el-option>
                    </el-select>
                </el-form-item>
            </el-col>
        </el-row>
        <el-divider></el-divider>
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
        name: "accounting-subject-add",
        props: {
            subjectTypeArr: {
                required: true,
            },
            subjectLevelArr: {
                required: true,
            }
        },
        data() {
            let validateParent = (rule, value, callback) => {
                if (this.form.subject_level != 1 && value == '') {
                    callback(new Error('父级科目不能为空'));
                } else {
                    callback();
                }
            };

            return {
                form: {
                    subject_level: '',
                    parent_subject: '',
                    parent_subject_name: '',
                    subject_code: '',
                    subject_name: '',
                    subject_type: '',
                    has_sub: '',
                    balance_direction: '',
                    subject_relationship_property: '',
                    zero_flag: '',
                    parent_subject_str: '',
                },
                parentSubjectArr: [],

                formRules: {
                    subject_level: [
                        {required: true, message: '科目级别不能为空'},
                    ],
                    parent_subject_str: [
                        {validator: validateParent}
                    ],
                    subject_code: [
                        {required: true, message: '科目代码不能为空'},
                    ],
                    subject_name: [
                        {required: true, message: '科目名称不能为空'},
                    ],
                    subject_type: [
                        {required: true, message: '科目类型不能为空'},
                    ],
                    has_sub: [
                        {required: true, message: '有无子目不能为空'},
                    ],
                    balance_direction: [
                        {required: true, message: '余额方向不能为空'},
                    ],
                    subject_relationship_property: [
                        {required: true, message: '科目关联性不能为空'},
                    ],
                    zero_flag: [
                        {required: true, message: '余额零标志不能为空'},
                    ],
                }
            }
        },
        methods: {
            commit() {
                this.$refs.form.validate(valid => {
                    if (valid) {
                        let param = this.form;
                        if (param.subject_code.indexOf(param.parent_subject) != 0 && param.parent_subject != 0) {
                            this.$message.error('科目代码前几位需与父级代码保持一致');
                            return false;
                        }
                        api.post('subject/add', param).then(() => {
                            this.$message.success('添加会计科目成功');
                            this.closeDialog();
                            this.$emit('addSuccess');
                        })
                    }
                })
            },
            closeDialog() {
                this.$emit('close');
                this.$refs.form.resetFields();
            },
            subjectLevelChange(level) {
                if (level != 1) {
                    api.get('subject/level', {level: level}).then(data => {
                        this.parentSubjectArr = data.list;
                    })
                } else {
                    this.parentSubjectArr = [];
                }
                this.form.parent_subject_str = '';
                this.form.parent_subject = '';
                this.form.parent_subject_name = '';
            },
            parentSubjectChange(parent) {
                let arr = parent.split('.');
                this.form.parent_subject = arr[0];
                this.form.parent_subject_name = arr[1];
            }
        }
    }
</script>

<style scoped>
    .cancel-class {
        margin-left: 20px;
    }
</style>
