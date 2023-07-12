<script setup>
import { ref } from "vue";
const routes = ref([
    { path: "/", name: "Главная" },
    { path: "/residents", name: "Дачники" },
    { path: "/periods", name: "Периоды" },
    { path: "/pump_meter_records", name: "Показания счётчика" },
    { path: "/rates", name: "Тариф" },
    { path: "/bills", name: "Счета" },
]);
</script>

<template>
    <nav
        class="navbar navbar-expand-lg bg-body-tertiary"
        style="background-color: #e3f2fd"
    >
        <div class="container container-fluid">
            <router-link class="navbar-brand" to="/">Водокачка+</router-link>
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li
                        class="nav-item"
                        v-for="route in routes"
                        :key="route.name"
                    >
                        <router-link class="nav-link" :to="route.path">{{
                            route.name
                        }}</router-link>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<style></style>
