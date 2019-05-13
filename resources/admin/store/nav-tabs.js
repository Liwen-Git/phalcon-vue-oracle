
const NavTabs = {
    namespaced: true,
    state: {
        activeTabName: '/welcome',
        tabList: [
            {
                name: '主页',
                path: '/welcome',
                disabled: false,
                closable: false,
            }
        ]
    },
    mutations: {
        setActiveTabName(state, path) {
            state.activeTabName = path;
        },
        addTab(state, index) {
            if (state.tabList.filter(f => f.path == index.path) == 0) {
                state.tabList.push({
                    name: index.name,
                    path: index.path,
                    disabled: false,
                    closable: true,
                })
            }
            state.activeTabName = index.path;
        },
        closeTab(state, route) {
            let index = 0;
            for (let tab of state.tabList) {
                if (tab.path === route) {
                    break;
                }
                index++;
            }
            state.tabList.splice(index, 1);
            state.activeTabName = state.tabList[state.tabList.length - 1].path;
        },
        resetTabList(state) {
            state.tabList = [
                {
                    name: '主页',
                    path: '/welcome',
                    disabled: false,
                    closable: false,
                }
            ];
            state.activeTabName = '/welcome';
        }
    }
};

export default NavTabs;
