<template>
    <span :class="['badge', backGroundColor]" v-if="data">{{
        data.title
    }}</span>
</template>

<script>
import { computed } from "vue";
export default {
    props: {
        data: {
            type: Object,
            required: true,
        },
    },
    setup({ data }) {
        const backGroundColor = computed(() => {
            if (data.color === "danger") {
                return "bg-danger";
            }
            if (data.color === "success") {
                return "bg-success";
            }
            if (data.color === "primary") {
                return "bg-primary";
            }
        });
        return {
            backGroundColor,
        };
    },
};
</script>

<style></style>
