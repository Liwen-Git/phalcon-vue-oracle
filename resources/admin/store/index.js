import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

/**
 * 去掉菜单中的url前缀
 * @param menus
 * @param prefix
 * @returns {*}
 */
let trimMenuUrlPrefix = function (menus, prefix = '/admin') {
    menus.forEach((menu) => {
        if (menu.url && menu.url.indexOf(prefix) === 0) {
            menu.url = menu.url.substr(prefix.length);
        }
        if (menu.sub && menu.sub.length > 0) {
            trimMenuUrlPrefix(menu.sub);
        }
    });
    return menus;
};

export default new Vuex.Store({
    state: {
        globalLoading: false,
        user: null,
        menus: [], // 用户的菜单列表(树型结构)
        rules: [], // 用户的权限列表(列表结构)
        currentMenu: null,
    },
    mutations: {
        setGlobalLoading(state, loading) {
            //这里的state对应着上面这个state
            state.globalLoading = loading;
        },
        setUser(state, user) {
            state.user = user;
        },
        setMenus(state, menus) {
            state.menus = menus;
        },
        setRules(state, rules) {
            state.rules = rules;
        },
        setCurrentMenu(state, currentMenu) {
            state.currentMenu = currentMenu;
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
        },
        // 登录的时候存储 用户、菜单、权限
        storeUserAndMenus(context, {user, menus, rules}) {
            menus = trimMenuUrlPrefix(menus);
            Lockr.set('user', user);
            Lockr.set('menus', menus);
            Lockr.set('rules', rules);
            context.commit('setUser', user);
            context.commit('setMenus', menus);
            context.commit('setRules', rules);
        },
        // 退出登录
        clearUserAndMenus(context) {
            Lockr.rm('user');
            Lockr.rm('menus');
            Lockr.rm('rules');
            context.commit('setUser', null);
            context.commit('setMenus', []);
            context.commit('setRules', []);
        }
    }
});
