import { createApp } from 'vue'
// import './style.css'
import "./assets/scss/tailwind.scss";
import router from "./router/router.js";
import App from './App.vue'

const app = createApp(App)
    .use(router)

app.mount("#app");