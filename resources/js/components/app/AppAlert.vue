<template>
    <div
        :class="[
            'alert',
            `alert-${message.type}`,
            ' d-flex',
            'align-items-center',
            'justify-content-between',
        ]"
        role="alert"
    >
        <div v-html="message.content"></div>
        <button
            type="button"
            class="btn-close"
            @click="$emit('closeAlert')"
        ></button>
    </div>
</template>

<script setup>
const emits = defineEmits(["closeAlert"]);
const props = defineProps({
    message: {
        type: Object,
        required: true,
    },
});
</script>

<style></style>
