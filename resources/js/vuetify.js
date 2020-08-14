import 'babel-polyfill'
import Vue from 'vue';
import '@mdi/font/css/materialdesignicons.css'
import Vuetify from 'vuetify';
Vue.use(Vuetify);
export default new Vuetify({
    theme: {
        themes: {
            light: {
                primary: '#5d9cec',
                error: '#f26422',
                success: '#42b14b',
                secondary: '#474747',
            },
        },
    },
    icons: {
        iconfont: "mdi" || "fa" || "fa4" || "fas" || 'md',
    },
});