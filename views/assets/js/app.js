import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'
import App from './App.vue'
// import 'boostrap-vue/dist/bootstrap-vue.css'
Vue.use(BootstrapVue);

const app = new Vue({
    el: '#app',
    components: {
        App,
    }
});
