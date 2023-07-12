import axios from "axios";

const URL_RESIDENTS = "/api/residents/";

export default {
    namespaced: true,
    state: {
        residents: [],
    },
    getters: {
        residents: (s) => s.residents,
    },
    mutations: {
        setResidents: (state, residents) => {
            state.residents = residents;
        },
        addResident: (state, resident) => {
            state.residents.push(resident);
        },
        replaceResident: (state, resident) => {
            state.residents = state.residents.map((curRes) =>
                curRes.id === resident.id ? resident : curRes
            );
        },
        removeResident: (state, residentId) => {
            state.residents = state.residents.filter(
                (resident) => resident.id !== residentId
            );
        },
    },
    actions: {
        async getResidents({ commit }) {
            try {
                const { data } = await axios.get(URL_RESIDENTS);
                commit("setResidents", data.residents);
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
        async getByIdResident({ commit }, residentId) {
            try {
                const { data } = await axios.get(URL_RESIDENTS + residentId);
                return { ...data.resident };
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
        async storeResident({ commit, dispatch }, dataResident) {
            try {
                const { data } = await axios.post(URL_RESIDENTS, dataResident);
                commit("addResident", data.resident);
                commit(
                    "setMessage",
                    {
                        type: "success",
                        content: "Новый дачник создан",
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
        async updateResident(
            { commit, dispatch },
            { residentId, dataResident }
        ) {
            try {
                const { data } = await axios.put(
                    URL_RESIDENTS + residentId,
                    dataResident
                );
                commit("replaceResident", data.resident);
                commit(
                    "setMessage",
                    {
                        type: "success",
                        content: "Дачник обновлен",
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
        async deleteResident({ commit, dispatch }, residentId) {
            try {
                await axios.delete(URL_RESIDENTS + residentId);
                commit("removeResident", residentId);
                commit(
                    "setMessage",
                    {
                        type: "success",
                        content: "Дачник удален",
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
