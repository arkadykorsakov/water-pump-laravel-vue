<script setup>
import { onMounted, ref } from "vue";
import AppModal from "../app/AppModal.vue";
import AppLoading from "../app/AppLoader.vue";
import RecordsForm from "./RecordsForm.vue";
import { useStore } from "vuex";

const { recordId } = defineProps({
    recordId: { type: [String, Number], required: true },
});

const store = useStore();

const record = ref(null);
const loading = ref(false);

const updateRecord = async (dataRecord) => {
    try {
        await store.dispatch("records/updateRecord", {
            recordId,
            dataRecord,
        });
    } catch (e) {
        throw e;
    }
};

onMounted(async () => {
    try {
        loading.value = true;
        record.value = await store.dispatch("records/getByIdRecord", recordId);
    } catch (e) {
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <AppModal title="Обновить запись">
        <template #body>
            <AppLoading v-if="loading" />
            <RecordsForm
                v-else
                btn-label="Обновить"
                :initial-data="record"
                :submit-fn="updateRecord"
            />
        </template>
    </AppModal>
</template>

<style></style>
