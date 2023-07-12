<script setup>
import RatesCreate from "./RatesCreate.vue";
import RatesDelete from "./RatesDelete.vue";
import RatesEdit from "./RatesEdit.vue";

const props = defineProps({
    component: { type: String, required: true },
    rateId: { type: [String, Number, null], required: true },
});
</script>

<template>
    <RatesCreate v-if="component === 'create'" />
    <RatesEdit v-if="component === 'edit'" :rate-id="rateId" />
    <RatesDelete v-if="component === 'delete'" :rate-id="rateId" />
</template>

<style scoped></style>
