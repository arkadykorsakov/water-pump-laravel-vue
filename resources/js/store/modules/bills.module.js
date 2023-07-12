import axios from "axios";

const URL_BILLS = "/api/bills/";

export default {
    namespaced: true,
    state: {
        bills: [],
    },
    getters: {
        bills: (s) => s.bills,
    },
    mutations: {
        setBills: (state, bills) => {
            state.bills = bills;
        },
    },
    actions: {
        async getBills({ commit }) {
            try {
                const { data } = await axios.get(URL_BILLS);
                console.log(data);
                commit("setBills", data.bills);
            } catch (e) {
                if (e.response?.status >= 500) {
                    commit(
                        "setMessage",
                        {
                            type: "danger",
                            content: e.response.data?.message,
                        },
                        { root: true }
                    );
                }
                throw e;
            }
        },
    },
};
