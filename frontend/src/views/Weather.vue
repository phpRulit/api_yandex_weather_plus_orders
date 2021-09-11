<template>
  <div class="col-md-12 p-5" v-if="weather">
    <div class="col-md-2 mx-auto pb-5">
      <select class="form-control" v-model="choice">
        <option :value="town.value" v-for="town in towns" :key="town.value">{{town.name}}</option>
      </select>
    </div>
    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 row mx-auto text-center">
      <div class="col-md-3 text-center mb-3">
        <img :src="this.weather.icon_url" alt="Погода в доме">
      </div>
      <div class="col-md-3 text-center mb-3">
        <b>Температура:</b><br>{{(this.weather.temp > 0 ? '+ ' : (this.weather.temp !== 0 ? '- ' : '')) + this.weather.temp}}
      </div>
      <div class="col-md-3 text-center mb-3">
        <b>Ощущается как:</b><br>{{(this.weather.feels_like > 0 ? '+ ' : this.weather.feels_like !== 0 ? '- ' : '') + this.weather.feels_like}}
      </div>
      <div class="col-md-3 text-center">
        <b>Сила ветра:</b><br>{{this.weather.wind_speed}} м/с
      </div>
    </div>
  </div>
</template>

<script>
import {mapActions} from "vuex";
export default {
  name: 'Home',
  data () {
    return {
      towns: [
        {name: 'Москва', value: 0},
        {name: 'Санкт-Петербург', value: 1},
        {name: 'Екатеринбург', value: 2},
        {name: 'Брянск', value: 3},
      ],
      choice: 3,
      coordinates: [
        {lat: '55.7522200', lon: '37.6155600'},
        {lat: '59.9386300', lon: '30.3141300'},
        {lat: '56.8519000', lon: '60.6122000'},
        {lat: '53.2521', lon: '34.3717'}
      ],
      details: {lat: '53.2521', lon: '34.3717'},
      weather: null,
    }
  },
  watch: {
    choice (newVal, oldVal) {
      if (newVal !== oldVal) {
        this.details = this.coordinates[newVal];
        this.currentWeather();
      }
    }
  },
  methods: {
    ...mapActions(["getWeather"]),
    currentWeather() {
      this.getWeather(this.details)
        .then(() => {
          this.weather = this.$store.getters["weather"];
        });
    }
  },
  created() {
    this.currentWeather();
  }
}
</script>
