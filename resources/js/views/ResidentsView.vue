<script setup>
import AppLoader from "@/components/app/AppLoader.vue";
import AppAlert from "@/components/app/AppAlert.vue";
import ResidentsActions from "@/components/residents/ResidentsActions.vue";
import AppBackdropModal from "@/components/app/AppBackdropModal.vue";
import { computed, onMounted, ref } from "vue";
import { useStore } from "vuex";
import { dateFormat } from "@/utils/date.format.js";

const store = useStore();

const residentsActionsComponent = ref("create");
const residentId = ref(null);
const rerenderResidentsActionsComponent = ref(0);
const loading = ref(false);

const residents = computed(() => store.getters["residents/residents"]);
const message = computed(() => store.getters.message);
const clearMessage = () => store.commit("clearMessage");

const changeResidentsActionsComponent = (component, currentResidentId) => {
    rerenderResidentsActionsComponent.value = Date.now();
    residentsActionsComponent.value = component;
    if (currentResidentId) {
        residentId.value = currentResidentId;
    } else {
        residentId.value = null;
    }
};

onMounted(async () => {
    try {
        loading.value = true;
        await store.dispatch("residents/getResidents");
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
            <h1 class="m-2">Дачники</h1>
            <button
                type="button"
                class="btn btn-success m-2"
                data-bs-toggle="modal"
                data-bs-target="#modal"
                @click="changeResidentsActionsComponent('create')"
            >
                Создать
            </button>
        </div>
        <AppLoader v-if="loading" />
        <div v-else-if="residents?.length" class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">ФИО</th>
                        <th scope="col">Площадь (а)</th>
                        <th scope="col">
                            Дата подключения дачника к водокачке
                        </th>

                        <th scope="col">Редактировать</th>
                        <th scope="col">Удалить</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(resident, i) in residents" :key="resident.id">
                        <td>{{ i + 1 }}</td>
                        <td>{{ resident.fio }}</td>
                        <td>{{ resident.area }}</td>
                        <td>{{ dateFormat(resident.start_date) }}</td>
                        <td>
                            <button
                                type="button"
                                class="btn btn-warning m-2"
                                data-bs-toggle="modal"
                                data-bs-target="#modal"
                                @click="
                                    changeResidentsActionsComponent(
                                        'edit',
                                        resident.id
                                    )
                                "
                            >
                                Редактировать
                            </button>
                        </td>
                        <td>
                            <button
                                v-if="!resident?.bills.length"
                                type="button"
                                class="btn btn-danger m-2"
                                data-bs-toggle="modal"
                                data-bs-target="#modal"
                                @click="
                                    changeResidentsActionsComponent(
                                        'delete',
                                        resident.id
                                    )
                                "
                            >
                                Удалить
                            </button>
                            <div v-else class="text-center">
                                Уже выставлены счета. Нельзя удалить
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-else class="text-center">Дачники не найдены</div>
    </div>
    <teleport to="body">
        <AppBackdropModal>
            <ResidentsActions
                v-if="residentsActionsComponent"
                :component="residentsActionsComponent"
                :resident-id="residentId"
                :key="rerenderResidentsActionsComponent"
            />
        </AppBackdropModal>
    </teleport>
</template>

<style></style>
