<script setup>
import ResidentsCreate from "./ResidentsCreate.vue";
import ResidentsDelete from "./ResidentsDelete.vue";
import ResidentsEdit from "./ResidentsEdit.vue";

const props = defineProps({
    component: { type: String, required: true },
    residentId: { type: [String, Number, null], required: true },
});
</script>

<template>
    <ResidentsCreate v-if="component === 'create'" />
    <ResidentsEdit v-if="component === 'edit'" :resident-id="residentId" />
    <ResidentsDelete v-if="component === 'delete'" :resident-id="residentId" />
</template>

<style scoped></style>
