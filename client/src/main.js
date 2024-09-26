import { createApp } from 'vue'
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";
import "./assets/scss/tailwind.scss";
import router from "./router/router.js";
import App from './App.vue'

const app = createApp(App)
    .use(router)
    .use(Toast)

app.mount("#app");