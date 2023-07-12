<script setup>
import PeriodsCreate from "./PeriodsCreate.vue";
import PeriodsDelete from "./PeriodsDelete.vue";
import PeriodsEdit from "./PeriodsEdit.vue";

const props = defineProps({
    component: { type: String, required: true },
    periodId: { type: [String, Number, null], required: true },
});
</script>

<template>
    <PeriodsCreate v-if="component === 'create'" />
    <PeriodsEdit v-if="component === 'edit'" :period-id="periodId" />
    <PeriodsDelete v-if="component === 'delete'" :period-id="periodId" />
</template>

<style scoped></style>
