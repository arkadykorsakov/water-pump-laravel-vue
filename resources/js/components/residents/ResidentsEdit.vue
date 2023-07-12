<script setup>
import { onMounted, ref } from "vue";
import AppModal from "../app/AppModal.vue";
import AppLoading from "../app/AppLoader.vue";
import ResidentsForm from "./ResidentsForm.vue";
import { useStore } from "vuex";

const { residentId } = defineProps({
    residentId: { type: [String, Number], required: true },
});

const store = useStore();

const resident = ref(null);
const loading = ref(false);

const updateResident = async (dataResident) => {
    try {
        await store.dispatch("residents/updateResident", {
            residentId,
            dataResident,
        });
    } catch (e) {
        throw e;
    }
};

onMounted(async () => {
    try {
        loading.value = true;
        resident.value = await store.dispatch(
            "residents/getByIdResident",
            residentId
        );
    } catch (e) {
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <AppModal title="Обновить дачника">
        <template #body>
            <AppLoading v-if="loading" />
            <ResidentsForm
                v-else
                btn-label="Обновить"
                :initial-data="resident"
                :submit-fn="updateResident"
            />
        </template>
    </AppModal>
</template>

<style></style>
