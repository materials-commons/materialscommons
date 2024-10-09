/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// window.Vue = require('vue');

require('raphael');
require('flowchart.js');
require('bootstrap-select');
window.moment = require('moment');
window.mcfl = require('./flowchart.js');
window.formatters = require('./formatters');
window.simplefl = require('./simpleflowchart');
window.mcutil = require('./util');
// window.Intercooler = require('intercooler');
window.xlsx = require('xlsx');
window.datagrid = require('canvas-datagrid');
// require('alpinejs');
window.htmx = require('htmx.org');
window.App = {};

// window.Uppy = require('@uppy/core');
// window.UppyXHRUpload = require('@uppy/xhr-upload');
// window.UppyDashboard = require('@uppy/dashboard');

// import Alpine from 'alpinejs';
// window.Alpine = Alpine;
// Alpine.start();

import Uppy from '@uppy/core';
import Dashboard from '@uppy/dashboard';
import XHR from '@uppy/xhr-upload';

window.Uppy = Uppy;
window.UppyDashboard = Dashboard;
window.UppyXHRUpload = XHR;

// Uppy styles for dashboard
// require('@uppy/core/dist/style.css');
// require('@uppy/dashboard/dist/style.css');
import '@uppy/core/dist/style.min.css';
import '@uppy/dashboard/dist/style.min.css';


window.Tagify = require('@yaireo/tagify');

// 

// const Turbolinks = require("turbolinks");
// Turbolinks.start();


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// const app = new Vue({
//     el: '#app'
// });
