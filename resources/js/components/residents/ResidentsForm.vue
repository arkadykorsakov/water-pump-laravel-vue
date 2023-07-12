<script setup>
import useVuelidate from "@vuelidate/core";
import { helpers, required, numeric, minValue } from "@vuelidate/validators";
import { computed, inject, onMounted, reactive } from "vue";

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

const stateForm = reactive({
    fio: "",
    startDate: "",
    area: "",
});

const rules = computed(() => ({
    fio: {
        required: helpers.withMessage(
            "Обязательное для заполнения поле",
            required
        ),
    },
    area: {
        required: helpers.withMessage(
            "Обязательное для заполнения поле",
            required
        ),
        number: helpers.withMessage("Введите число", numeric),
        min: helpers.withMessage(
            "Число не может быть меньше 0.01",
            minValue(0.01)
        ),
    },
    startDate: {
        required: helpers.withMessage(
            "Обязательное для заполнения поле",
            required
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
            fio: stateForm.fio,
            start_date: stateForm.startDate,
            area: stateForm.area,
        });
        btnClose.value.click();
    } catch (e) {
        console.log(e);
    }
};

onMounted(() => {
    if (initialData) {
        stateForm.fio = initialData.fio;
        stateForm.area = initialData.area;
        stateForm.startDate = initialData.start_date;
    }
});
</script>

<template>
    <form @submit.prevent="onSubmit">
        <div class="mb-3">
            <label for="fio" class="form-label">ФИО</label>
            <input
                type="text"
                :class="[
                    'form-control',
                    { 'is-invalid': v$.fio.$errors.length },
                ]"
                id="fio"
                name="fio"
                placeholder="ФИО"
                v-model.trim="v$.fio.$model"
            />

            <template v-if="v$.fio.$error">
                <TransitionGroup>
                    <div
                        v-for="error of v$.fio.$errors"
                        :key="error.$uid"
                        class="invalid-feedback"
                    >
                        {{ error.$message }}
                    </div>
                </TransitionGroup>
            </template>
        </div>
        <div class="mb-3">
            <label for="area" class="form-label"
                >Площадь огорода дачника (а)</label
            >
            <input
                type="number"
                :class="[
                    'form-control',
                    { 'is-invalid': v$.area.$errors.length },
                ]"
                id="area"
                name="area"
                placeholder="Площадь огорода дачника (а)"
                step="0.01"
                min="0.01"
                v-model="v$.area.$model"
            />
            <template v-if="v$.area.$errors.length">
                <TransitionGroup>
                    <div
                        v-for="error of v$.area.$errors"
                        :key="error.$uid"
                        class="invalid-feedback"
                    >
                        {{ error.$message }}
                    </div>
                </TransitionGroup>
            </template>
        </div>
        <div class="mb-3">
            <label for="start_date" class="form-label"
                >Дата подключения дачника к водокачке</label
            >
            <input
                type="datetime-local"
                :class="[
                    'form-control',
                    { 'is-invalid': v$.startDate.$errors.length },
                ]"
                id="start_date"
                name="start_date"
                placeholder="Дата подключения дачника к водокачке"
                v-model="v$.startDate.$model"
            />

            <template v-if="v$.startDate.$errors.length">
                <TransitionGroup>
                    <div
                        v-for="error of v$.startDate.$errors"
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
