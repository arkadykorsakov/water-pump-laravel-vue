<script setup>
import useVuelidate from "@vuelidate/core";
import { helpers, required } from "@vuelidate/validators";
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
    beginDate: "",
    endDate: "",
});

const rules = computed(() => ({
    beginDate: {
        required: helpers.withMessage("Невалидная дата", required),
    },
    endDate: {
        required: helpers.withMessage("Невалидная дата", required),
    },
}));

const v$ = useVuelidate(rules, stateForm);

const btnClose = inject("btnClose");

const onSubmit = async () => {
    v$.value.$touch();
    if (v$.value.$error) return;
    try {
        await submitFn({
            begin_date: stateForm.beginDate,
            end_date: stateForm.endDate,
        });
        btnClose.value.click();
    } catch (e) {
        console.log(e);
    }
};

onMounted(() => {
    if (initialData) {
        stateForm.beginDate = initialData.begin_date.split(" ")[0];
        stateForm.endDate = initialData.end_date.split(" ")[0];
    }
});
</script>

<template>
    <form @submit.prevent="onSubmit">
        <div class="mb-3">
            <label for="begin_date" class="form-label">Начало периода</label>
            <input
                type="date"
                :class="[
                    'form-control',
                    { 'is-invalid': v$.beginDate.$errors.length },
                ]"
                id="begin_date"
                name="begin_date"
                placeholder="Начало периода"
                v-model="v$.beginDate.$model"
            />

            <template v-if="v$.beginDate.$errors.length">
                <TransitionGroup>
                    <div
                        v-for="error of v$.beginDate.$errors"
                        :key="error.$uid"
                        class="invalid-feedback"
                    >
                        {{ error.$message }}
                    </div>
                </TransitionGroup>
            </template>
        </div>

        <div class="mb-3">
            <label for="end_date" class="form-label">Окончание периода</label>
            <input
                type="date"
                :class="[
                    'form-control',
                    { 'is-invalid': v$.endDate.$errors.length },
                ]"
                id="end_date"
                name="end_date"
                placeholder="Окончание периода"
                v-model="v$.endDate.$model"
            />

            <template v-if="v$.endDate.$errors.length">
                <TransitionGroup>
                    <div
                        v-for="error of v$.endDate.$errors"
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
