<template>
    <page title="会计科目">
        <el-col>
            <el-form :model="form" ref="form" size="small" inline>
                <el-form-item prop="subjectCode" label="科目代码">
                    <el-input type="text" v-model="form.subjectCode" placeholder="请输入科目代码" clearable></el-input>
                </el-form-item>
            </el-form>
            <el-form-item prop="subjectName" label="科目名称">
                <el-input type="text" v-model="form.subjectName" placeholder="请输入科目名称" clearable></el-input>
            </el-form-item>
            <div>hello world hello</div>
        </el-col>
    </page>
</template>

<script>
    export default {
        name: "accounting-subject-list",
        data() {
            return {
                form: {
                    subjectCode: '',
                    subjectName: '',
                    subjectLevel: '',
                    subjectType: '',

                    page: 1,
                    pageSize: 10,
                },
                total: 0,
            }
        }
    }
</script>

<style scoped>

</style>
