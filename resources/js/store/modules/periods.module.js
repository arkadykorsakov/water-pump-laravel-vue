import axios from "axios";

const URL_PERIODS = "/api/periods/";

export default {
    namespaced: true,
    state: {
        periods: [],
    },
    getters: {
        periods: (s) => s.periods,
    },
    mutations: {
        setPeriods: (state, periods) => {
            state.periods = periods;
        },
        addPeriod: (state, period) => {
            state.periods.push(period);
        },
        replacePeriod: (state, period) => {
            state.periods = state.periods.map((curPer) =>
                curPer.id === period.id ? period : curPer
            );
        },
        removePeriod: (state, periodId) => {
            state.periods = state.periods.filter(
                (period) => period.id !== periodId
            );
            console.log(state.periods);
        },
    },
    actions: {
        async getPeriods({ commit }) {
            try {
                const { data } = await axios.get(URL_PERIODS);
                commit("setPeriods", data.periods);
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
        async getByIdPeriod({ commit }, periodId) {
            try {
                const { data } = await axios.get(URL_PERIODS + periodId);
                return { ...data.period };
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
        async storePeriod({ commit, dispatch }, dataPeriod) {
            try {
                const { data } = await axios.post(URL_PERIODS, dataPeriod);
                commit("addPeriod", data.period);
                commit(
                    "setMessage",
                    {
                        type: "success",
                        content: "Новый период создан",
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
        async updatePeriod({ commit, dispatch }, { periodId, dataPeriod }) {
            try {
                const { data } = await axios.put(
                    URL_PERIODS + periodId,
                    dataPeriod
                );
                commit("replacePeriod", data.period);
                commit(
                    "setMessage",
                    {
                        type: "success",
                        content: "Период обновлен",
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
        async deletePeriod({ commit, dispatch }, periodId) {
            try {
                await axios.delete(URL_PERIODS + periodId);
                commit("removePeriod", periodId);
                commit(
                    "setMessage",
                    {
                        type: "success",
                        content: "Период удален",
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
