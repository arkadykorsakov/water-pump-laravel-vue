import axios from "axios";

const URL_RECORDS = "/api/pump_meter_records/";

export default {
    namespaced: true,
    state: {
        records: [],
    },
    getters: {
        records: (s) => s.records,
    },
    mutations: {
        setRecords: (state, records) => {
            state.records = records;
        },
        addRecord: (state, record) => {
            state.records.push(record);
        },
        replaceRecord: (state, record) => {
            state.records = state.records.map((curPer) =>
                curPer.id === record.id ? record : curPer
            );
        },
        removeRecord: (state, recordId) => {
            state.records = state.records.filter(
                (record) => record.id !== recordId
            );
        },
    },
    actions: {
        async getRecords({ commit }) {
            try {
                const { data } = await axios.get(URL_RECORDS);
                commit("setRecords", data.pump_meter_records);
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
        async getByIdRecord({ commit }, recordId) {
            try {
                const { data } = await axios.get(URL_RECORDS + recordId);
                return { ...data.pump_meter_record };
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
        async storeRecord({ commit, dispatch }, dataRecord) {
            try {
                const { data } = await axios.post(URL_RECORDS, dataRecord);
                commit("addRecord", data.pump_meter_record);
                commit(
                    "setMessage",
                    {
                        type: "success",
                        content: "Новая запись создана",
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
        async updateRecord({ commit, dispatch }, { recordId, dataRecord }) {
            try {
                const { data } = await axios.put(
                    URL_RECORDS + recordId,
                    dataRecord
                );
                commit("replaceRecord", data.pump_meter_record);
                commit(
                    "setMessage",
                    {
                        type: "success",
                        content: "Запись обновлена",
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
        async deleteRecord({ commit, dispatch }, recordId) {
            try {
                await axios.delete(URL_RECORDS + recordId);
                commit("removeRecord", recordId);
                commit(
                    "setMessage",
                    {
                        type: "success",
                        content: "Запись удалена",
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
