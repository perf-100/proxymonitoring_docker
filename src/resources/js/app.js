import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import axios from 'axios'
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

window.Pusher = Pusher
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'hvagh3sxq7lc0yppmjqi',
    wsHost: 'localhost',
    wsPort: 6001,
    forceTLS: false,
    disableStats: true,
    cluster: 'mt1',
    enabledTransports: ['ws'],
});

axios.defaults.withCredentials = true

const app = createApp(App)
app.use(router)
app.mount('#app')