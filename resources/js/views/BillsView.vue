<script setup>
import AppLoader from "@/components/app/AppLoader.vue";
import AppAlert from "@/components/app/AppAlert.vue";
import { computed, onMounted, ref } from "vue";
import { useStore } from "vuex";
import { dateFormat } from "@/utils/date.format.js";
import { currencyFormat } from "@/utils/currency.format.js";

const store = useStore();

const loading = ref(false);

const bills = computed(() => store.getters["bills/bills"]);
const periods = computed(() => store.getters["periods/periods"]);
const message = computed(() => store.getters.message);
const clearMessage = () => store.commit("clearMessage");

const conditionFilteredBills = ref("");
const changeConditionFilteredBills = (condition) => {
    conditionFilteredBills.value = condition;
};
const filteredBills = computed(() => {
    if (!conditionFilteredBills.value) return bills.value;
    return bills.value.filter(
        (bill) => bill.period.begin_date === conditionFilteredBills.value
    );
});

onMounted(async () => {
    try {
        loading.value = true;
        await store.dispatch("bills/getBills");
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
            <h1 class="m-2">Счета</h1>
            <div class="dropdown" v-if="periods.length">
                <button
                    class="btn btn-info dropdown-toggle"
                    type="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                >
                    Периоды
                </button>
                <ul class="dropdown-menu">
                    <li v-for="period in periods" :key="period.id">
                        <div
                            class="dropdown-item"
                            @click="
                                changeConditionFilteredBills(period.begin_date)
                            "
                        >
                            {{ dateFormat(period.begin_date) }} -
                            {{ dateFormat(period.end_date) }}
                        </div>
                    </li>
                    <li><hr class="dropdown-divider" /></li>
                    <li>
                        <div
                            class="dropdown-item"
                            @click="changeConditionFilteredBills('')"
                        >
                            Все
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <AppLoader v-if="loading" />
        <div v-else-if="filteredBills?.length" class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">ФИО</th>
                        <th scope="col">Период</th>
                        <th scope="col">Счет</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(bill, i) in filteredBills" :key="bill.id">
                        <td>{{ i + 1 }}</td>
                        <td>{{ bill.resident.fio }}</td>
                        <td>
                            {{ dateFormat(bill.period.begin_date) }} -
                            {{ dateFormat(bill.period.end_date) }}
                        </td>
                        <td>{{ currencyFormat(bill.amount_rub) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-else class="text-center">Счета не найдены</div>
    </div>
</template>

<style></style>
