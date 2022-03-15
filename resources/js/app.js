require('./bootstrap')

import { createApp } from 'vue'
import Orders from './components/Orders'

import Datepicker from 'vue3-date-time-picker';
import 'vue3-date-time-picker/dist/main.css'

const app = createApp({})
var moment = require('moment');

app.component('Orders', Orders)
app.component('Datepicker', Datepicker)

app.mount('#app')
