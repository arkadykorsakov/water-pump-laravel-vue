<script setup>
import AppLoader from "@/components/app/AppLoader.vue";
import AppAlert from "@/components/app/AppAlert.vue";
import AppBackdropModal from "@/components/app/AppBackdropModal.vue";
import PeriodsActions from "@/components/periods/PeriodsActions.vue";
import { useStore } from "vuex";
import { computed, onMounted, ref } from "vue";
import { dateFormat } from "@/utils/date.format.js";

const store = useStore();

const periodsActionsComponent = ref("create");
const periodId = ref(null);
const rerenderPeriodActionsComponent = ref(0);
const loading = ref(false);

const periods = computed(() => store.getters["periods/periods"]);
const message = computed(() => store.getters.message);
const clearMessage = () => store.commit("clearMessage");

const changePeriodsActionsComponent = (component, currentPeriodId) => {
    rerenderPeriodActionsComponent.value = Date.now();
    periodsActionsComponent.value = component;
    if (currentPeriodId) {
        periodId.value = currentPeriodId;
    } else {
        periodId.value = null;
    }
};

onMounted(async () => {
    try {
        loading.value = true;
        await store.dispatch("periods/getPeriods");
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
            <h1 class="m-2">Периоды</h1>
            <button
                type="button"
                class="btn btn-success m-2"
                data-bs-toggle="modal"
                data-bs-target="#modal"
                @click="changePeriodsActionsComponent('create')"
            >
                Создать
            </button>
        </div>
        <AppLoader v-if="loading" />
        <div v-else-if="periods?.length" class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Начало периода</th>
                        <th scope="col">Окончание периода</th>
                        <th scope="col">Редактировать</th>
                        <th scope="col">Удалить</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(period, i) in periods" :key="period.id">
                        <td>{{ i + 1 }}</td>
                        <td>{{ dateFormat(period.begin_date) }}</td>
                        <td>{{ dateFormat(period.end_date) }}</td>
                        <td>
                            <button
                                type="button"
                                class="btn btn-warning m-2"
                                data-bs-toggle="modal"
                                data-bs-target="#modal"
                                @click="
                                    changePeriodsActionsComponent(
                                        'edit',
                                        period.id
                                    )
                                "
                            >
                                Редактировать
                            </button>
                        </td>
                        <td>
                            <button
                                v-if="!period.pump_meter_record"
                                type="button"
                                class="btn btn-danger m-2"
                                data-bs-toggle="modal"
                                data-bs-target="#modal"
                                @click="
                                    changePeriodsActionsComponent(
                                        'delete',
                                        period.id
                                    )
                                "
                            >
                                Удалить
                            </button>
                            <div v-else>Внесены показания счетчиков</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-else class="text-center">Периоды не найдены</div>
    </div>
    <teleport to="body">
        <AppBackdropModal>
            <PeriodsActions
                v-if="periodsActionsComponent"
                :component="periodsActionsComponent"
                :period-id="periodId"
                :key="rerenderPeriodActionsComponent"
            />
        </AppBackdropModal>
    </teleport>
</template>

<style scoped></style>
