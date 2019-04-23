import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        globalLoading: false,
    },
    mutations: {
        setGlobalLoading(state, loading) {
            //这里的state对应着上面这个state
            state.globalLoading = loading;
        }
    },
    /** 官方推荐 异步操作放在 actions 中 */
    actions: {
        openGlobalLoading(context) {
            //这里的context和我们使用的this.$store拥有相同的对象和方法
            context.commit('setGlobalLoading', true);
        },
        closeGlobalLoading(context) {
            context.commit('setGlobalLoading', false);
        }
    }
});
