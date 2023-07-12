<script setup>
import AppLoader from "@/components/app/AppLoader.vue";
import AppAlert from "@/components/app/AppAlert.vue";
import RatesActions from "@/components/rates/RatesActions.vue";
import AppBackdropModal from "@/components/app/AppBackdropModal.vue";
import { computed, onMounted, ref } from "vue";
import { useStore } from "vuex";
import { dateFormat } from "@/utils/date.format.js";
import { currencyFormat } from "@/utils/currency.format.js";

const store = useStore();

const ratesActionsComponent = ref("create");
const rateId = ref(null);
const rerenderRatesActionsComponent = ref(0);
const loading = ref(false);

const rates = computed(() => store.getters["rates/rates"]);
const message = computed(() => store.getters.message);
const clearMessage = () => store.commit("clearMessage");

const changeRatesActionsComponent = (component, currentRateId) => {
    rerenderRatesActionsComponent.value = Date.now();
    ratesActionsComponent.value = component;
    if (currentRateId) {
        rateId.value = currentRateId;
    } else {
        rateId.value = null;
    }
};

onMounted(async () => {
    try {
        loading.value = true;
        await store.dispatch("rates/getRates");
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
            <h1 class="m-2">Тариф</h1>
            <button
                type="button"
                class="btn btn-success m-2"
                data-bs-toggle="modal"
                data-bs-target="#modal"
                @click="changeRatesActionsComponent('create')"
            >
                Создать
            </button>
        </div>
        <AppLoader v-if="loading" />
        <div class="table-responsive" v-else-if="rates?.length">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Начало действия тарифа</th>
                        <th scope="col">Тариф (руб./м<sup>3</sup>)</th>
                        <th scope="col">Редактировать</th>
                        <th scope="col">Удалить</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(rate, i) in rates" :key="rate.id">
                        <td>{{ i + 1 }}</td>
                        <td>
                            {{ dateFormat(rate.period.begin_date) }}
                        </td>
                        <td>{{ currencyFormat(rate.price) }}</td>
                        <td>
                            <button
                                type="button"
                                class="btn btn-warning m-2"
                                data-bs-toggle="modal"
                                data-bs-target="#modal"
                                @click="
                                    changeRatesActionsComponent('edit', rate.id)
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
                                    changeRatesActionsComponent(
                                        'delete',
                                        rate.id
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
        <div v-else class="text-center">Тарифы не найдены</div>
    </div>
    <teleport to="body">
        <AppBackdropModal>
            <RatesActions
                v-if="ratesActionsComponent"
                :component="ratesActionsComponent"
                :rate-id="rateId"
                :key="rerenderRatesActionsComponent"
            />
        </AppBackdropModal>
    </teleport>
</template>

<style></style>
