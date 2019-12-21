
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

import Buefy from 'buefy' // to import all plugins
//import Table from 'buefy/dist/components/table'
//import Input from 'buefy/dist/components/input'
import 'buefy/dist/buefy.css'

Vue.use(Buefy) // for all
//Vue.use(Table) // for buefy
//Vue.use(Input)


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

Vue.component('admin-calendar-list', require('./components/admin/calendars/List.vue').default);
Vue.component('admin-calendar-edit', require('./components/admin/calendars/Edit.vue').default);

//Vue.component('concierge-settings-logo', require('./components/ConciergeSettingsLogo.vue')); move

const app = new Vue({
    el: '#app'
});