import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import axios from 'axios';
import jQuery from 'jquery';
import { BootstrapVue, BootstrapVueIcons } from 'bootstrap-vue';

Vue.router = router;
window.$ = window.jQuery = jQuery;

Vue.use(BootstrapVue);
Vue.use(BootstrapVueIcons); //Иконки https://bootstrap-vue.org/docs/icons

Vue.config.productionTip = false;

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.baseURL = process.env.VUE_APP_API_URL;
Vue.prototype.$http = axios;

new Vue({
  router,
  store,
  created() {
    axios.interceptors.response.use(
        response => response,
        error => {
            if (error.response.status === 422) {
                this.$store.commit("setErrors", error.response.data.errors);
            }
            return Promise.reject(error);
        })
  },
  render: h => h(App)
}).$mount('#app')
