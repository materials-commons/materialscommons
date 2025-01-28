/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

require('raphael');
require('flowchart.js');
require('bootstrap-select');
window.mcfl = require('./flowchart.js');
window.formatters = require('./formatters');
window.simplefl = require('./simpleflowchart');
window.mcutil = require('./util');
window.xlsx = require('xlsx');
window.datagrid = require('canvas-datagrid');
window.htmx = require('htmx.org');
window.App = {};


import Uppy from '@uppy/core';
import Dashboard from '@uppy/dashboard';
import XHR from '@uppy/xhr-upload';

window.Uppy = Uppy;
window.UppyDashboard = Dashboard;
window.UppyXHRUpload = XHR;

import '@uppy/core/dist/style.min.css';
import '@uppy/dashboard/dist/style.min.css';


window.Tagify = require('@yaireo/tagify');

