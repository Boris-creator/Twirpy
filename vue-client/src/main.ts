import '@quasar/extras/material-icons/material-icons.css'
import '@quasar/extras/material-icons-outlined/material-icons-outlined.css'
import 'quasar/src/css/index.sass'

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import { Quasar } from 'quasar'
import { VueQueryPlugin } from 'vue-query'
import { config as quasarConfig } from '@/quasar.config'

import App from './App.vue'
import router from './router'

const app = createApp(App)

app.use(createPinia())
app.use(router)
app.use(Quasar, quasarConfig)
app.use(VueQueryPlugin)

app.mount('#app')
