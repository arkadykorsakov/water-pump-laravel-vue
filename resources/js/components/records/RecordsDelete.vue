<script setup>
import { useStore } from "vuex";
import AppModal from "../app/AppModal.vue";
import AppLoader from "../app/AppLoader.vue";
import { onMounted, ref } from "vue";

const { recordId } = defineProps({
    recordId: { type: [String, Number] },
});

const store = useStore();
const recordDelete = ref(null);

const loading = ref(false);

const btnClose = ref(null);

const deleteRecord = async () => {
    try {
        await store.dispatch("records/deleteRecord", recordId);
        btnClose.value.click();
    } catch (e) {
        console.log(e);
    }
};

onMounted(async () => {
    try {
        loading.value = true;
        const { period } = await store.dispatch(
            "records/getByIdRecord",
            recordId
        );
        recordDelete.value = `${period.begin_date} - ${period.end_date}`;
    } catch (e) {
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <AppModal title="Удаление записи">
        <template #body>
            <AppLoader v-if="loading" />
            <template v-else>
                Вы уверены что хотите удалить данную запись
                <strong>{{ recordDelete }}</strong> ?
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
            <button type="button" class="btn btn-danger" @click="deleteRecord">
                Удалить
            </button>
        </template>
    </AppModal>
</template>

<style></style>
