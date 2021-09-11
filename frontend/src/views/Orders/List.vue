<template>
  <div>
    <div class="pt-3">
      <div class="col-md-12 pr-0 row">
        <div class="col-xl-9 col-lg-9 col-md-7 col-sm-7 mb-3">
          <h3>
            <span v-if="this.details.status === 'all'">Все </span>
            <span v-else-if="this.details.status === 'overdue'">Просроченные </span>
            <span v-else-if="this.details.status === 'current'">Текущие </span>
            <span v-else-if="this.details.status === 'new'">Новые </span>
            <span v-else-if="this.details.status === 'completed'">Выполненные </span>
            заказы
          </h3>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-5 col-sm-5 mb-3 pr-0">
          <select class="form-control" v-model="details.max">
            <option :value="maxVal" v-for="maxVal in maxOnPage">Выводить по {{maxVal}} записей ...</option>
          </select>
        </div>
      </div>

      <div class="col-md-12 row mb-3 pr-0">
        <div class="col-lg-3 col-md-6 col-sm-6 mb-2 pr-0" v-if="this.details.status !== 'all'">
          <button @click="getListWithStatus('all')" class="btn w-100 btn-outline-primary">Все заказы</button>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 mb-2 pr-0" v-if="this.details.status !== 'overdue'">
        <button @click="getListWithStatus('overdue')" class="btn w-100 btn-outline-danger">Просроченные заказы</button>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 mb-2 pr-0" v-if="this.details.status !== 'current'">
          <button @click="getListWithStatus('current')" class="btn w-100 btn-outline-success">Текущие заказы</button>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 mb-2 pr-0" v-if="this.details.status !== 'new'">
          <button @click="getListWithStatus('new')" class="btn w-100 btn-outline-info">Новые заказы</button>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 pr-0" v-if="this.details.status !== 'completed'">
          <button @click="getListWithStatus('completed')" class="btn w-100 btn-outline-warning">Выполненные заказы</button>
        </div>
      </div>
      <div class="table-responsive p-3" v-if="orders && orders.data.length > 0">
        <table class="table table-bordered table-striped small">
          <thead class="text-center">
            <tr>
              <th class="col-1">ID</th>
              <th class="col-2">Партнёр</th>
              <th class="col-1">Стоимость</th>
              <th class="col-6">Состав заказа</th>
              <th class="col-1">Статус</th>
              <th class="col-1">Действия</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(order, index) in this.orders.data" :key="index" class="p-0">
              <td class="col-1 text-center">{{order.id}}</td>
              <td class="col-2">{{order.partner.name}}</td>
              <td class="col-1 text-center">{{order.order_products.map(i => sum += i.price, sum=0).reverse()[0]}}</td>
              <td class="col-6">
                <span v-for="(op, indexOp) in order.order_products">{{indexOp + 1}}.
                  <span v-if="indexOp === indexP" v-for="(p, indexP) in order.products">
                     {{p.name}}; Количество: {{op.quantity}}; Цена: {{p.price}}; Общая стоимость: {{op.price}}
                  </span><br>
                </span>
              </td>
              <td class="col-1 text-center">
                <span v-if="order.status === 0">НОВЫЙ</span>
                <span v-else-if="order.status === 10">ПОДТВЕРЖДЁН</span>
                <span v-else-if="order.status === 20">ЗАВШЁН</span>
              </td>
              <td class="col-1 text-center"><span @click="getShow(order.id)" class="spanHover"><b-icon class="icon" icon="eye-fill" variant="primary"></b-icon></span></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="col-md-12 text-center pt-5" v-else>
        Записей не найдено...
      </div>
    </div>
    <div class="col-md-12">
      <Pagination v-if="orders" :data="orders" @pagination-change-page="getList" :limit="5" align="center"></Pagination>
    </div>
  </div>

</template>

<script>
import {mapActions} from "vuex";
import Pagination from 'laravel-vue-pagination';
export default {
  components: {Pagination},
  data () {
    return {
      maxOnPage: [10, 20, 30, 40, 50],
      details: {
        page: null,
        status: 'all',
        max: 50
      },
      orders: null,
    }
  },
  watch: {
    'details.max' (newVal, oldVal) {
      if (oldVal !== newVal) {
        this.orders = null;
        this.getList();
      }
    }
  },
  methods: {
    ...mapActions("orders", ["getOrdersByParams"]),
    getList(page=null) {
      this.details.page = page;
      this.getOrdersByParams(this.details)
        .then(() => {
          this.orders = this.$store.getters["orders/orders"];
          //console.log(this.orders)
        })
    },
    getListWithStatus(status) {
      this.details.status = status;
      this.orders = null;
      this.getList();
    },
    getShow(id) {
      localStorage.setItem('dataForBack', JSON.stringify({
        page: this.orders.current_page,
        max: this.details.max,
        status: this.details.status,
      }));
      this.$router.push({name: 'order.edit', params: {id: id}});
    }
  },
  created() {
    if (!localStorage.getItem('dataForBack')) {
      this.getList();
    } else {
      let data = JSON.parse(localStorage.getItem('dataForBack'));
      this.details.max = data.max;
      this.details.status = data.status;
      this.getList(data.page);
      localStorage.removeItem('dataForBack');
    }
  }
}
</script>
