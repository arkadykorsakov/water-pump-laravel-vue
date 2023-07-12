import "./bootstrap";
import "bootstrap/dist/css/bootstrap.min.css";
import { createApp } from "vue";
import App from "./App.vue";
import router from "./routes";
import store from "./store";

const app = createApp(App);

app.use(router).use(store).mount("#app");
