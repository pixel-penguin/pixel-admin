
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import Vue from 'vue'
import axios from 'axios'
import VueAxios from 'vue-axios'
 
Vue.use(VueAxios, axios);

import VueSweetalert2 from 'vue-sweetalert2'; 
Vue.use(VueSweetalert2);

import VueNestable from 'vue-nestable'
Vue.use(VueNestable)

import VTooltip from 'v-tooltip'
Vue.use(VTooltip)

import PrettyCheckbox from 'pretty-checkbox-vue';
Vue.use(PrettyCheckbox);

import VueClipboard from 'vue-clipboard2' 
Vue.use(VueClipboard)

import {ServerTable, ClientTable, Event} from 'vue-tables-2';
Vue.use(ClientTable, {}, false, 'bootstrap3');

import DateTimePicker from 'vue-vanilla-datetime-picker';
Vue.component('date-time-picker', DateTimePicker);

import VueApexCharts from 'vue-apexcharts'
Vue.use(VueApexCharts)
Vue.component('apexchart', VueApexCharts)

import Sortable from 'sortablejs'
Vue.directive('sortable', {
    inserted: function (el, binding) {
      new Sortable(el, binding.value || {})
    }
  })
  

import VoerroTagsInput from '@voerro/vue-tagsinput';

Vue.component('tags-input', VoerroTagsInput);

//import Editor from '@tinymce/tinymce-vue';
//var Editor = require('@tinymce/tinymce-vue').default;

Vue.component('admin-dashboard-analytics', require('./components/admin/dashboard/Analytics.vue').default);

Vue.component('admin-navigation', require('./components/admin/layout/AdminNavigation.vue').default);

Vue.component('admin-pages-list', require('./components/admin/pages/List.vue').default);
Vue.component('admin-page-edit', require('./components/admin/pages/Edit.vue').default);
Vue.component('admin-page-builder', require('./components/admin/pages/PageBuilder.vue').default);

Vue.component('admin-testimonial-list', require('./components/admin/testimonials/List.vue').default);
Vue.component('admin-testimonial-edit', require('./components/admin/testimonials/Edit.vue').default);

Vue.component('admin-blog-list', require('./components/admin/blogs/List.vue').default);
Vue.component('admin-blog-edit', require('./components/admin/blogs/Edit.vue').default);


Vue.component('admin-team-list', require('./components/admin/teams/List.vue').default);
Vue.component('admin-team-edit', require('./components/admin/teams/Edit.vue').default);

Vue.component('admin-project-list', require('./components/admin/projects/List.vue').default);
Vue.component('admin-project-edit', require('./components/admin/projects/Edit.vue').default);

Vue.component('admin-calendar-list', require('./components/admin/calendars/List.vue').default);
Vue.component('admin-calendar-edit', require('./components/admin/calendars/Edit.vue').default);

Vue.component('admin-cloudfiles-index', require('./components/admin/cloudfiles/Index.vue').default);
//Vue.component('concierge-settings-logo', require('./components/ConciergeSettingsLogo.vue')); move

Vue.component('admin-special-list', require('./components/admin/specials/List.vue').default);
Vue.component('admin-special-edit', require('./components/admin/specials/Edit.vue').default);

Vue.component('admin-product-categories-list', require('./components/admin/productcategories/List.vue').default);
Vue.component('admin-product-categories-edit', require('./components/admin/productcategories/Edit.vue').default);


Vue.component('admin-products-list', require('./components/admin/products/List.vue').default);
Vue.component('admin-products-edit', require('./components/admin/products/Edit.vue').default);


Vue.component('admin-service-list', require('./components/admin/services/List.vue').default);
Vue.component('admin-service-edit', require('./components/admin/services/Edit.vue').default);

Vue.component('admin-brand-list', require('./components/admin/brands/List.vue').default);
Vue.component('admin-brand-edit', require('./components/admin/brands/Edit.vue').default);

const app = new Vue({
    el: '#app'
});
