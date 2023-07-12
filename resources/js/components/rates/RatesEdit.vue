<script setup>
import { onMounted, ref } from "vue";
import AppModal from "../app/AppModal.vue";
import AppLoading from "../app/AppLoader.vue";
import RatesForm from "./RatesForm.vue";
import { useStore } from "vuex";

const { rateId } = defineProps({
    rateId: { type: [String, Number], required: true },
});

const store = useStore();

const rate = ref(null);
const loading = ref(false);

const updateRate = async (dataRate) => {
    try {
        await store.dispatch("rates/updateRate", {
            rateId,
            dataRate,
        });
    } catch (e) {
        throw e;
    }
};

onMounted(async () => {
    try {
        loading.value = true;
        rate.value = await store.dispatch("rates/getByIdRate", rateId);
    } catch (e) {
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <AppModal title="Обновить тариф">
        <template #body>
            <AppLoading v-if="loading" />
            <RatesForm
                v-else
                btn-label="Обновить"
                :initial-data="rate"
                :submit-fn="updateRate"
            />
        </template>
    </AppModal>
</template>

<style></style>
