<script setup>
import AppModal from "../app/AppModal.vue";
import RecordsForm from "./RecordsForm.vue";
import { useStore } from "vuex";

const store = useStore();

const createRecord = async (recordData) => {
    try {
        await store.dispatch("records/storeRecord", recordData);
    } catch (e) {
        throw e;
    }
};
</script>

<template>
    <AppModal title="Создать запись">
        <template #body>
            <RecordsForm btn-label="Создать" :submit-fn="createRecord" />
        </template>
    </AppModal>
</template>

<style></style>
