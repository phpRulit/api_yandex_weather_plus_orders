import Vue from 'vue'
import VueRouter from 'vue-router'
import Weather from '../views/Weather.vue'
import OrdersIndex from '../views/Orders/Index.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'weather',
    component: Weather
  },
  {
    path: '/orders',
    name: 'orders.index',
    component: OrdersIndex,
    redirect: {name: 'orders.list'},
    children: [
      {
        path: 'list',
        name: 'orders.list',
        component: () => import(/* webpackChunkName: "about" */ '../views/Orders/List.vue')
      },
      {
        path: 'order-edit/:id',
        name: 'order.edit',
        component: () => import(/* webpackChunkName: "about" */ '../views/Orders/Edit.vue')
      }
    ]
  },

]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
