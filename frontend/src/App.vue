<template>
  <div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item" v-for="(route, key) in routes" v-bind:key="route.path">
            <router-link :to="{ name : route.path }" :key="key" class="nav-link">{{route.name}}</router-link>
          </li>
        </ul>
      </div>
    </nav>
    <div id="app">
      <router-view/>
    </div>
  </div>

</template>

<script>
export default {
  data () {
    return {
      routes: [
        { name: 'Погода', path: 'weather'},
        { name: 'Заказы', path: 'orders.index'},
      ],
    }
  }
}
</script>

<style lang="scss">
@import "~bootstrap/dist/css/bootstrap.min.css";
@import './bootstrap/bootstrap.scss';
</style>
