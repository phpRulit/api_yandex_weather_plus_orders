import Vue from 'vue'
import Vuex from 'vuex'
import axios from "axios";

Vue.use(Vuex)

import orders from "./modules/orders";

export default new Vuex.Store({
  state: {
    weather: null,
    errors: [],
    errorMsg: null,
    successMsg: null,
  },
  getters: {
    weather: state => state.weather,
    errors: state => state.errors,
    errorMsg: state => state.errorMsg,
    successMsg: state => state.successMsg,
  },
  mutations: {
    setWeather(state, data) {
      state.weather = data;
    },
    setErrors(state, errors) {
      state.errors = errors;
    },
    setError(state, msg) {
      state.errorMsg = msg;
    },
    setSuccess(state, msg) {
      state.successMsg = msg;
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
  }
})
