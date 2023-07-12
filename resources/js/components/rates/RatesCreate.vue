<script setup>
import AppModal from "../app/AppModal.vue";
import RatesForm from "./RatesForm.vue";
import { useStore } from "vuex";

const store = useStore();

const createRate = async (rateData) => {
    try {
        await store.dispatch("rates/storeRate", rateData);
    } catch (e) {
        throw e;
    }
};
</script>

<template>
    <AppModal title="Создать тариф">
        <template #body>
            <RatesForm btn-label="Создать" :submit-fn="createRate" />
        </template>
    </AppModal>
</template>

<style></style>
