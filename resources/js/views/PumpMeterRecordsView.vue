<script setup>
import AppLoader from "@/components/app/AppLoader.vue";
import AppAlert from "@/components/app/AppAlert.vue";
import RecordsActions from "@/components/records/RecordsActions.vue";
import AppBackdropModal from "@/components/app/AppBackdropModal.vue";
import { computed, onMounted, ref } from "vue";
import { useStore } from "vuex";
import { dateFormat } from "@/utils/date.format.js";

const store = useStore();

const recordsActionsComponent = ref("create");
const recordId = ref(null);
const rerenderRecordsActionsComponent = ref(0);
const loading = ref(false);

const records = computed(() => store.getters["records/records"]);
const message = computed(() => store.getters.message);
const clearMessage = () => store.commit("clearMessage");

const changeRecordsActionsComponent = (component, currentRecordId) => {
    rerenderRecordsActionsComponent.value = Date.now();
    recordsActionsComponent.value = component;
    if (currentRecordId) {
        recordId.value = currentRecordId;
    } else {
        recordId.value = null;
    }
};

onMounted(async () => {
    try {
        loading.value = true;
        await store.dispatch("records/getRecords");
    } catch (e) {
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <div class="container py-3">
        <AppAlert
            v-if="message"
            @closeAlert="clearMessage"
            :message="message"
        />
        <div
            class="d-flex justify-content-between align-items-center flex-wrap mb-2"
        >
            <h1 class="m-2">Показания счётчика водокачки</h1>
            <button
                type="button"
                class="btn btn-success m-2"
                data-bs-toggle="modal"
                data-bs-target="#modal"
                @click="changeRecordsActionsComponent('create')"
            >
                Создать
            </button>
        </div>
        <AppLoader v-if="loading" />
        <div class="table-responsive" v-else-if="records?.length">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Период</th>
                        <th scope="col">
                            Показания счетчика на конец периода (м<sup>3</sup>)
                        </th>
                        <th scope="col">Редактировать</th>
                        <th scope="col">Удалить</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(record, i) in records" :key="record.id">
                        <td>{{ i + 1 }}</td>
                        <td>
                            {{ dateFormat(record.period.begin_date) }} -
                            {{ dateFormat(record.period.end_date) }}
                        </td>
                        <td>{{ record.amount_volume }}</td>
                        <td>
                            <button
                                type="button"
                                class="btn btn-warning m-2"
                                data-bs-toggle="modal"
                                data-bs-target="#modal"
                                @click="
                                    changeRecordsActionsComponent(
                                        'edit',
                                        record.id
                                    )
                                "
                            >
                                Редактировать
                            </button>
                        </td>
                        <td>
                            <button
                                type="button"
                                class="btn btn-danger m-2"
                                data-bs-toggle="modal"
                                data-bs-target="#modal"
                                @click="
                                    changeRecordsActionsComponent(
                                        'delete',
                                        record.id
                                    )
                                "
                            >
                                Удалить
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-else class="text-center">
            Показания счётчика водокачки не найдены
        </div>
    </div>
    <teleport to="body">
        <AppBackdropModal>
            <RecordsActions
                v-if="recordsActionsComponent"
                :component="recordsActionsComponent"
                :record-id="recordId"
                :key="rerenderRecordsActionsComponent"
            />
        </AppBackdropModal>
    </teleport>
</template>

<style></style>
