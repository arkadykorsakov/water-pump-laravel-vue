import axios from "axios";

const URL_RATES = "/api/rates/";

export default {
    namespaced: true,
    state: {
        rates: [],
    },
    getters: {
        rates: (s) => s.rates,
    },
    mutations: {
        setRates: (state, rates) => {
            state.rates = rates;
        },
        addRate: (state, rate) => {
            state.rates.push(rate);
        },
        replaceRate: (state, rate) => {
            state.rates = state.rates.map((curPer) =>
                curPer.id === rate.id ? rate : curPer
            );
        },
        removeRate: (state, rateId) => {
            state.rates = state.rates.filter((rate) => rate.id !== rateId);
        },
    },
    actions: {
        async getRates({ commit }) {
            try {
                const { data } = await axios.get(URL_RATES);
                commit("setRates", data.rates);
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
        async getByIdRate({ commit }, rateId) {
            try {
                const { data } = await axios.get(URL_RATES + rateId);
                return { ...data.rate };
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
        async storeRate({ commit, dispatch }, dataRate) {
            try {
                const { data } = await axios.post(URL_RATES, dataRate);
                commit("addRate", data.rate);
                commit(
                    "setMessage",
                    {
                        type: "success",
                        content: "Новый тариф создан",
                    },
                    { root: true }
                );
                dispatch("clearMessage", {}, { root: true });
            } catch (e) {
                if (e.response?.status !== 422) {
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
        async updateRate({ commit, dispatch }, { rateId, dataRate }) {
            try {
                const { data } = await axios.put(URL_RATES + rateId, dataRate);
                commit("replaceRate", data.rate);
                commit(
                    "setMessage",
                    {
                        type: "success",
                        content: "Тариф обновлен",
                    },
                    { root: true }
                );
                dispatch("clearMessage", {}, { root: true });
            } catch (e) {
                if (e.response?.status !== 422) {
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
        async deleteRate({ commit, dispatch }, rateId) {
            try {
                await axios.delete(URL_RATES + rateId);
                commit("removeRate", rateId);
                commit(
                    "setMessage",
                    {
                        type: "success",
                        content: "Тариф удален",
                    },
                    { root: true }
                );
                dispatch("clearMessage", {}, { root: true });
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
