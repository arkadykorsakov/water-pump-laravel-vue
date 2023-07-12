<script setup>
import useVuelidate from "@vuelidate/core";
import {
    helpers,
    required,
    numeric,
    minValue,
    maxValue,
} from "@vuelidate/validators";
import { computed, inject, onMounted, reactive, ref } from "vue";
import { RouterLink } from "vue-router";
import { useStore } from "vuex";
import AppLoader from "@/components/app/AppLoader.vue";
import { dateFormat } from "@/utils/date.format.js";

const { submitFn, btnLabel, initialData } = defineProps({
    btnLabel: {
        type: String,
        required: true,
    },
    submitFn: {
        type: Function,
        required: true,
    },
    initialData: {
        type: Object,
        required: false,
    },
});

const store = useStore();

const stateForm = reactive({
    periodId: "",
    amountVolume: "",
});

const loading = ref(false);
const errorsServer = ref({});

const periods = computed(() => store.getters["periods/periods"]);
const residents = computed(() => store.getters["residents/residents"]);

const rules = computed(() => ({
    periodId: {
        required: helpers.withMessage("Невалидная дата", required),
    },
    amountVolume: {
        required: helpers.withMessage("Невалидная дата", required),
        number: helpers.withMessage("Введите число", numeric),
        min: helpers.withMessage(
            "Число не может быть меньше 0.01",
            minValue(0.01)
        ),
        max: helpers.withMessage(
            "Число не может быть больше 999999.99",
            maxValue(999999.99)
        ),
    },
}));

const v$ = useVuelidate(rules, stateForm);

const btnClose = inject("btnClose");

const onSubmit = async () => {
    v$.value.$touch();
    if (v$.value.$error) return;
    try {
        await submitFn({
            period_id: stateForm.periodId,
            amount_volume: stateForm.amountVolume,
        });
        btnClose.value.click();
    } catch (e) {
        if (e.response?.data?.errors) {
            errorsServer.value = { ...e.response.data.errors };
        }
    }
};

onMounted(async () => {
    if (initialData) {
        stateForm.periodId = initialData.period.id;
        stateForm.amountVolume = initialData.amount_volume;
    }
    try {
        loading.value = true;
        await store.dispatch("periods/getPeriods");
        await store.dispatch("residents/getResidents");
    } catch (e) {
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <AppLoader v-if="loading" />
    <div v-else-if="!periods">Периоды не найдены</div>
    <div v-else-if="!residents">Дачники не найдены</div>
    <form
        v-else-if="periods.length && residents.length"
        @submit.prevent="onSubmit"
    >
        <div class="mb-3">
            <label for="period_id" class="form-label">Начало периода</label>
            <select
                :class="[
                    'form-select',
                    {
                        'is-invalid':
                            v$.periodId.$errors.length ||
                            errorsServer.period_id,
                    },
                ]"
                id="period_id"
                name="period_id"
                v-model="v$.periodId.$model"
            >
                <option value="" selected>Выберите период</option>
                <option
                    v-for="period in periods"
                    :key="period.id"
                    :value="period.id"
                >
                    {{ dateFormat(period.begin_date) }} -
                    {{ dateFormat(period.end_date) }}
                </option>
            </select>
            <template
                v-if="v$.periodId.$errors.length || errorsServer.period_id"
            >
                <TransitionGroup>
                    <div
                        v-for="error of v$.periodId.$errors"
                        :key="error.$uid"
                        class="invalid-feedback"
                    >
                        {{ error.$message }}
                    </div>
                    <div class="invalid-feedback" v-if="errorsServer.period_id">
                        {{ errorsServer.period_id[0] }}
                    </div>
                </TransitionGroup>
            </template>
        </div>
        <div class="mb-3">
            <label for="amountVolume" class="form-label"
                >Показания счетчика</label
            >
            <input
                type="number"
                :class="[
                    'form-control',
                    { 'is-invalid': v$.amountVolume.$errors.length },
                ]"
                id="amountVolume"
                name="amountVolume"
                placeholder="Показания счетчика"
                step="0.01"
                min="0.01"
                max="999999.99"
                v-model="v$.amountVolume.$model"
            />
            <template v-if="v$.amountVolume.$errors.length">
                <TransitionGroup>
                    <div
                        v-for="error of v$.amountVolume.$errors"
                        :key="error.$uid"
                        class="invalid-feedback"
                    >
                        {{ error.$message }}
                    </div>
                </TransitionGroup>
            </template>
        </div>
        <button class="btn btn-primary">{{ btnLabel }}</button>
    </form>
    <RouterLink v-else-if="!periods.length" class="text-center" to="/periods"
        >Создайте период</RouterLink
    >
    <RouterLink
        v-else-if="!residents.length"
        class="text-center"
        to="/residents"
        >Создайте дачника</RouterLink
    >
</template>

<style scoped>
.v-enter-active,
.v-leave-active {
    transition: opacity 0.5s ease;
}
.v-enter-from,
.v-leave-to {
    opacity: 0;
}
</style>
