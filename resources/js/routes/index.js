import { createRouter, createWebHistory } from "vue-router";

const routes = [
    {
        path: "/",
        component: () => import("@/views/IndexView.vue"),
    },
    {
        path: "/residents",
        component: () => import("@/views/ResidentsView.vue"),
    },
    {
        path: "/pump_meter_records",
        component: () => import("@/views/PumpMeterRecordsView.vue"),
    },
    {
        path: "/rates",
        component: () => import("@/views/RatesView.vue"),
    },
    {
        path: "/periods",
        component: () => import("@/views/PeriodsView.vue"),
    },
    {
        path: "/bills",
        component: () => import("@/views/BillsView.vue"),
    },
];

const router = createRouter({ routes, history: createWebHistory() });

export default router;
