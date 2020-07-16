/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');

window.Vue = require('vue');

axios.defaults.baseURL = 'http://localhost:8088/freelance-portal/public/';
Vue.prototype.$appBaseURL = 'http://localhost:8088/freelance-portal/public/';

import Vue from 'vue';
import Vuelidate from 'vuelidate';
Vue.use(Vuelidate);

import Select2 from 'v-select2-component';
Vue.component('Select2', Select2);

import VueToastr2 from 'vue-toastr-2'
import 'vue-toastr-2/dist/vue-toastr-2.min.css'
window.toastr = require('toastr')
Vue.use(VueToastr2)

import VueSweetalert2 from 'vue-sweetalert2';
Vue.use(VueSweetalert2);
import 'sweetalert2/dist/sweetalert2.min.css';

import ToggleButton from 'vue-js-toggle-button'
Vue.use(ToggleButton);

import CKEditor from '@ckeditor/ckeditor5-vue';
Vue.use( CKEditor );

import moment from 'moment';

Vue.filter('formatDate', function(value) {
    if (value) {
        return moment(String(value)).format('MM/DD/YYYY hh:mm')
    }
});

/**
 * The following block of code may be used to automatically register your
 * Vue support. It will recursively scan this directory for the Vue
 * support and automatically register them with their "basename".
 *
 * Eg. ./support/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('category-list-component', require('./components/category/CategoryList.vue').default);
Vue.component('category-form-component', require('./components/category/CategoryForm.vue').default);
Vue.component('article-list-component', require('./components/article/ArticleList.vue').default);
Vue.component('article-add-component', require('./components/article/ArticleAdd.vue').default);
Vue.component('article-edit-component', require('./components/article/ArticleEdit.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding support to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app'
});
