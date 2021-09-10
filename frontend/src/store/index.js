import Vue from 'vue'
import Vuex from 'vuex'
import axios from "axios";

Vue.use(Vuex)

import orders from "./modules/orders";
import data from "./modules/data";

export default new Vuex.Store({
  state: {
    weather: null,
    errors: [],
    messageSuccess: null,
    messageError: null,
  },
  getters: {
    weather: state => state.weather,
    errors: state => state.errors,
    messageSuccess: state => state.messageSuccess,
    messageError: state => state.messageError,
  },
  mutations: {
    setWeather(state, data) {
      state.weather = data;
    },
    setErrors(state, errors) {
      state.errors = errors;
    },
    setMessageSuccess(state, msg) {
      state.messageSuccess = msg;
    },
    setMessageError(state, msg) {
      state.messageError = msg;
    },
  },
  actions: {
    getWeather({commit}, details) {
      return axios.get('/yandex/set-weather', {params: {lat: details.lat, lon: details.lon}})
        .then(({data}) => {
          commit('setWeather',data);
        })
    }
  },
  modules: {
    orders: orders,
    data: data,
  }
})
