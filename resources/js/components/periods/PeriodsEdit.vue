<script setup>
import { onMounted, ref } from "vue";
import AppModal from "../app/AppModal.vue";
import AppLoading from "../app/AppLoader.vue";
import PeriodsForm from "./PeriodsForm.vue";
import { useStore } from "vuex";

const { periodId } = defineProps({
    periodId: { type: [String, Number], required: true },
});

const store = useStore();

const period = ref(null);
const loading = ref(false);

const updatePeriod = async (dataPeriod) => {
    try {
        await store.dispatch("periods/updatePeriod", {
            periodId,
            dataPeriod,
        });
    } catch (e) {
        throw e;
    }
};

onMounted(async () => {
    try {
        loading.value = true;
        period.value = await store.dispatch("periods/getByIdPeriod", periodId);
    } catch (e) {
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <AppModal title="Обновить период">
        <template #body>
            <AppLoading v-if="loading" />
            <PeriodsForm
                v-else
                btn-label="Обновить"
                :initial-data="period"
                :submit-fn="updatePeriod"
            />
        </template>
    </AppModal>
</template>

<style></style>
