import Vue from 'vue'

import BootstrapVue from 'bootstrap-vue'
import Notifications from 'vue-notification'
import Loading from 'vue-loading-overlay';

import 'vue-loading-overlay/dist/vue-loading.css';

import App from './App.vue'

Vue.use(BootstrapVue);
Vue.use(Notifications)
Vue.use(Loading);

const app = new Vue({
    el: '#app',
    components: {
        App,
    }
});
