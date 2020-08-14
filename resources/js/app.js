window._ = require('lodash');
import { InertiaApp } from '@inertiajs/inertia-vue'
import Vue from 'vue'
import vuetify from '@/vuetify'
Vue.use(InertiaApp)
const app = document.getElementById('app')

Vue.prototype.$route = (...args) => route(...args).url();
if(app){
    new Vue({
        vuetify,
        render: h => h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: name => require(`./Pages/${name}`).default,
                beforeVisit: () => { NProgress.start() },
                afterVisit: () => { NProgress.done() },
            },
        }),
    }).$mount(app)
}
