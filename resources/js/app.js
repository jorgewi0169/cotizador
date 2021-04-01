/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');


import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import locale from 'element-ui/lib/locale/lang/es';
import money from 'v-money';

/* import  fileInput from 'bootstrap-fileinput'; */
/* import locale-file from 'bootstrap-fileinput' */

import vSelect from 'vue-select';

Vue.use(ElementUI, { locale })


import Swal from 'sweetalert2'
window.Swal = Swal;

/**
 * EventBus - biblioteca para comunicacion entre componentes
 */
export const EventBus = new Vue();
window.EventBus = EventBus;


/**
 * Vuesax - biblioteca para interfaz de usuario
 */
import Vuesax from 'vuesax'
import 'vuesax/dist/vuesax.css' //Vuesax styles


Vue.use(Vuesax, {
    // options here
})

/*input-file */
/* Vue.use(fileInput, {
    // options here
}) */

/*Libreria de Moneda */
Vue.use(money, {precision: 2})

/**Libreria de select */
Vue.component("v-select", vSelect);
Vue.component('App', require('./components/Principal.vue').default);
Vue.component('Auth', require('./components/Auth.vue').default);



 import router from './routes';


const app = new Vue({
    //mixins: [funcionAlert],
    el: '#app',
    router,
    //
    data: {
        iva_igv: 0.0,

    },

});



