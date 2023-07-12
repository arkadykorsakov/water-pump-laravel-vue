<script setup>
import AppModal from "../app/AppModal.vue";
import ResidentsForm from "./ResidentsForm.vue";
import { useStore } from "vuex";

const store = useStore();

const createResident = async (residentData) => {
    try {
        await store.dispatch("residents/storeResident", residentData);
    } catch (e) {
        throw e;
    }
};
</script>

<template>
    <AppModal title="Создать дачника">
        <template #body>
            <ResidentsForm btn-label="Создать" :submit-fn="createResident" />
        </template>
    </AppModal>
</template>

<style></style>
