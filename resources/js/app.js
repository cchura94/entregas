require('./bootstrap');
window.Vue = require('vue');

Vue.component('notificacion', require('./components/Ubicacion.vue').default);
Vue.component('sent-message', require('./components/Sent.vue').default);

Vue.component('message', require('./components/Notificacion.vue').default);

const app = new Vue({
    el: '#app',
    data: {
        messages: []
    },
    mounted() {
        this.fetchMessages();
        Echo.private('chat')
            .listen('NotificacionSentEvent', (e) => {
                this.messages.push({
                    message: e.message.message,
                    user: e.user
                })
            })
    },
    methods: {
        addMessage(message) {
            this.messages.push(message)
            axios.post('/notificacion', message).then(response => {
                //console.log(response)
            })

            //console.log(this.messages)
        },
        fetchMessages() {
            axios.get('/notificacion').then(response => {
                this.messages = response.data
            })
        }
    }

});
