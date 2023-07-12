<script setup>
import { provide, ref } from "vue";

const props = defineProps({ title: { type: String, required: true } });

const btnClose = ref(null);
provide("btnClose", btnClose);
</script>

<template>
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">
                {{ title }}
            </h1>
            <button
                ref="btnClose"
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
            ></button>
        </div>
        <div class="modal-body">
            <slot name="body"></slot>
        </div>
        <div class="modal-footer">
            <slot name="footer"></slot>
        </div>
    </div>
</template>

<style scoped></style>
