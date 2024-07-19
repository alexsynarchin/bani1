require('./bootstrap');
import Vue from "vue";
Vue.component('booking', require('./components/booking/index').default);
const app = new Vue({
    el: '#app',
});
