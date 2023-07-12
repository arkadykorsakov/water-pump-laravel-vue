<script setup>
import { useStore } from "vuex";
import AppModal from "../app/AppModal.vue";
import AppLoader from "../app/AppLoader.vue";
import { onMounted, ref } from "vue";

const { rateId } = defineProps({
    rateId: { type: [String, Number] },
});

const store = useStore();
const rateDelete = ref(null);

const loading = ref(false);

const btnClose = ref(null);

const deleteRate = async () => {
    try {
        await store.dispatch("rates/deleteRate", rateId);
        btnClose.value.click();
    } catch (e) {
        console.log(e);
    }
};

onMounted(async () => {
    try {
        loading.value = true;
        const { period, rate } = await store.dispatch(
            "rates/getByIdRate",
            rateId
        );
        rateDelete.value = `${period.begin_date} - ${rate}`;
    } catch (e) {
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <AppModal title="Удаление тариф">
        <template #body>
            <AppLoader v-if="loading" />
            <template v-else>
                Вы уверены что хотите удалить данный тариф
                <strong>{{ rateDelete }}</strong> ?
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
            <button type="button" class="btn btn-danger" @click="deleteRate">
                Удалить
            </button>
        </template>
    </AppModal>
</template>

<style></style>
