<script setup>
import AppModal from "../app/AppModal.vue";
import PeriodsForm from "./PeriodsForm.vue";
import { useStore } from "vuex";

const store = useStore();

const createPeriod = async (periodData) => {
    try {
        await store.dispatch("periods/storePeriod", periodData);
    } catch (e) {
        throw e;
    }
};
</script>

<template>
    <AppModal title="Создать период">
        <template #body>
            <PeriodsForm btn-label="Создать" :submit-fn="createPeriod" />
        </template>
    </AppModal>
</template>

<style></style>
