import '../css/app.css';
import './bootstrap';

import { createApp } from 'vue';
import { createPinia } from 'pinia';
import router from './router';
import App from './App.vue';
import axios from 'axios';
import Toast, { POSITION } from 'vue-toastification';
import 'vue-toastification/dist/index.css';
import setFavicon from './favicon';
import axiosInstance from './utils/axios'
import { setupAxiosInterceptors } from './services/errorHandler'

// Configure axios
axios.defaults.baseURL = '/api/v1';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.withCredentials = true;

// Add auth token if exists
const token = localStorage.getItem('token');
if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

// Setup axios interceptors
setupAxiosInterceptors(axios)

const app = createApp(App);

// Configure toast options
const toastOptions = {
    position: POSITION.TOP_RIGHT,
    timeout: 3000,
    closeOnClick: true,
    pauseOnFocusLoss: true,
    pauseOnHover: true,
    draggable: true,
    draggablePercent: 0.6,
    showCloseButtonOnHover: false,
    hideProgressBar: false,
    closeButton: "button",
    icon: true,
    rtl: false
};

app.use(createPinia());
app.use(router);
app.use(Toast, toastOptions);

app.config.globalProperties.$axios = axiosInstance

app.mount('#app');

setFavicon();
