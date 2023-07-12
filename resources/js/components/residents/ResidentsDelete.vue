<script setup>
import { useStore } from "vuex";
import AppModal from "../app/AppModal.vue";
import AppLoader from "../app/AppLoader.vue";
import { onMounted, ref } from "vue";

const { residentId } = defineProps({
    residentId: { type: [String, Number] },
});

const store = useStore();
const residentFioDelete = ref(null);

const loading = ref(false);

const btnClose = ref(null);

const deleteResident = async () => {
    try {
        await store.dispatch("residents/deleteResident", residentId);
        btnClose.value.click();
    } catch (e) {
        console.log(e);
    }
};

onMounted(async () => {
    try {
        loading.value = true;
        const { fio } = await store.dispatch(
            "residents/getByIdResident",
            residentId
        );
        residentFioDelete.value = fio;
    } catch (e) {
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <AppModal title="Удаление дачника">
        <template #body>
            <AppLoader v-if="loading" />
            <template v-else>
                Вы уверены что хотите удалить данного дачника
                <strong>{{ residentFioDelete }}</strong> ?
            </template>
        </template>
        <template #footer>
            <button
                type="button"
                class="btn btn-primary"
                data-bs-dismiss="modal"
                ref="btnClose"
            >
                Отмена
            </button>
            <button
                type="button"
                class="btn btn-danger"
                @click="deleteResident"
            >
                Удалить
            </button>
        </template>
    </AppModal>
</template>

<style></style>
