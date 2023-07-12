<script setup>
import { useStore } from "vuex";
import AppModal from "../app/AppModal.vue";
import AppLoader from "../app/AppLoader.vue";
import { onMounted, ref } from "vue";

const { periodId } = defineProps({
    periodId: { type: [String, Number] },
});

const store = useStore();
const periodDelete = ref(null);

const loading = ref(false);

const btnClose = ref(null);

const deletePeriod = async () => {
    try {
        await store.dispatch("periods/deletePeriod", periodId);
        btnClose.value.click();
    } catch (e) {
        console.log(e);
    }
};

onMounted(async () => {
    try {
        loading.value = true;
        const { begin_date, end_date } = await store.dispatch(
            "periods/getByIdPeriod",
            periodId
        );
        periodDelete.value = `${begin_date} - ${end_date}`;
    } catch (e) {
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <AppModal title="Удаление периода">
        <template #body>
            <AppLoader v-if="loading" />
            <template v-else>
                Вы уверены что хотите удалить данный период
                <strong>{{ periodDelete }}</strong> ?
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
            <button type="button" class="btn btn-danger" @click="deletePeriod">
                Удалить
            </button>
        </template>
    </AppModal>
</template>

<style></style>
