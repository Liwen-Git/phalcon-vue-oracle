<template>

</template>

<script>
    export default {
        name: "agent-profit-sharing-edit",
        props: {
            theReport: {
                required: true,
                type: Object,
            }
        },
        data() {
            return {
                form: {
                    agentps_sum_id: '',
                    other_amt: '',
                    memo: '',
                }
            }
        }
    }
</script>

<style scoped>

</style>
