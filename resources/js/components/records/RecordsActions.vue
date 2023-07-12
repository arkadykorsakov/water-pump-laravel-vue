<script setup>
import RecordsCreate from "./RecordsCreate.vue";
import RecordsDelete from "./RecordsDelete.vue";
import RecordsEdit from "./RecordsEdit.vue";

const props = defineProps({
    component: { type: String, required: true },
    recordId: { type: [String, Number, null], required: true },
});
</script>

<template>
    <RecordsCreate v-if="component === 'create'" />
    <RecordsEdit v-if="component === 'edit'" :record-id="recordId" />
    <RecordsDelete v-if="component === 'delete'" :record-id="recordId" />
</template>

<style scoped></style>
