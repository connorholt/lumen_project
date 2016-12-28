import Vue from 'vue'
import VueRouter from 'vue-router'
import VueResource from 'vue-resource'
import Vuex from 'vuex'

Vue.use(VueResource);
Vue.use(VueRouter);
Vue.use(Vuex);

import Text from './components/text.vue'
import Vote from './components/vote.vue'
import Select from './components/select.vue'
import About from './components/about.vue'

const routes = [
    { path: '/', component: Text },
    { path: '/vote', component: Vote },
    { path: '/select', component: Select },
    { path: '/about', component: About }
];

const router = new VueRouter({
    routes
});

const store = new Vuex.Store({
    state: {
        parts: []
    },
    getters: {
        all (state) {
            return state.parts;
        }
    },
    mutations: {
        add (state, part) {
            state.parts.unshift(part);
        },
        setAll (state, parts) {
            state.parts = parts;
        }
    },
    actions: {
        add ({ commit }, part) {
            commit('add', part)
        }
    }
});

const app = new Vue({
    router,
    store
}).$mount('#app');


var socket = io.connect('http://localhost:8890');
socket.on('message', function (data) {
    var part = JSON.parse(data);

    part.like_count = 0;
    part.dislike_count = 0;
    part.is_new = true;

    store.dispatch('add', part);

    $.notify(part.author + " только что добавил свой вариант", {
        globalPosition: "top center",
        className: "success"
    });
    console.log(part);
});