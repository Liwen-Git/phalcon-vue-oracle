import Welcome from '../components/welcome';

const NavTabs = {
    namespaced: true,
    state: {
        activeTabName: 'welcome',
        tabList: [
            {
                label: '主页',
                name: 'welcome',
                disabled: false,
                closable: false,
                component: Welcome,
            }
        ]
    },
    mutations: {
        setActiveTabName(state, name) {
            state.activeTabName = name;
        },
        addTab(state, index) {
            console.log(index);
        },
        closeTab(state, index) {

        }
    }
};

export default NavTabs;
