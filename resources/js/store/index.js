import { createStore } from "vuex";
import residents from "./modules/residents.module";
import periods from "./modules/periods.module";
import records from "./modules/records.module";
import rates from "./modules/rates.module";
import bills from "./modules/bills.module";

const store = createStore({
    state: {
        message: null,
    },
    getters: {
        message: (s) => s.message,
    },
    mutations: {
        setMessage(state, message) {
            state.message = message;
        },
        clearMessage(state) {
            state.message = null;
        },
    },
    actions: {
        clearMessage({ commit }) {
            setTimeout(() => {
                commit("clearMessage");
            }, 10000);
        },
    },
    modules: {
        residents,
        periods,
        records,
        rates,
        bills,
    },
});

export default store;
