<template>
  <div class="col-md-12" v-if="order">
    <nav class="mt-3 mb-5" aria-label="breadcrumb small">
      <ol class="breadcrumb bg-white p-1 small">
        <li class="breadcrumb-item spanBreadcrumbs spanHover ml-2"><span @click="back">Назад в заказы</span></li>
        <li class="breadcrumb-item active" aria-current="page">Редактирование заказа id - {{order_id}}</li>
      </ol>
    </nav>
    <div class="table-responsive mb-3">
      <h4>Партнёр:</h4>
      <table class="table table-bordered small">
        <tbody>
          <tr>
            <th class="col-2">Email:</th>
            <td class="col-9">{{ this.order.partner.email }}</td>
            <td class="text-center col-1">
              <span class="spanHover"><b-icon class="icon" icon="binoculars" variant="primary"></b-icon></span>
            </td>
          </tr>
          <tr>
            <th>Наименование:</th>
            <td>{{ this.order.partner.name }}</td>
            <td></td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="table-responsive">
      <h4>Заказ:</h4>
      <table class="table table-bordered small">
        <tbody>
          <tr>
            <th class="col-2">Статус:</th>
            <td  class="col-9">
              <span v-if="order.status === 0">НОВЫЙ</span>
              <span v-else-if="order.status === 10">ПОДТВЕРЖДЁН</span>
              <span v-else-if="order.status === 20">ЗАВШЁН</span>
            </td>
            <td class="text-center col-1">
              <span class="spanHover"><b-icon class="icon" icon="pencil" variant="primary"></b-icon></span>
            </td>
          </tr>
          <tr>
            <th>ID:</th>
            <td>{{ order.id }}</td>
            <td></td>
          </tr>
          <tr>
            <th>Дата доставки:</th><td>{{ order.delivery_dt ? order.delivery_dt : 'не назначена' }}</td>
            <td class="text-center">
              <span class="spanHover"><b-icon class="icon" icon="pencil" variant="primary"></b-icon></span>
            </td>
          </tr>
          <tr>
            <th>Email клиента:</th>
            <td>{{ order.client_email }}</td>
            <td class="text-center">
              <span class="spanHover"><b-icon class="icon" icon="pencil" variant="primary"></b-icon></span>
            </td>
          </tr>
          <tr>
            <th>Состав заказа:</th>
            <td>
              <div class="row mb-2" v-for="(op, indexOp) in order.order_products">
                <div class="col-md-10">{{indexOp + 1}}.
                  <span v-for="(p, indexP) in order.products">
                   <span v-if="indexOp === indexP">{{p.name}}; Количество: {{op.quantity}}; Цена: {{p.price}}; Общая стоимость: {{op.quantity * p.price}}</span>
                </span>
                </div>
                <div class="col-md-2 text-right">
                  <span class="spanHover mr-2"><b-icon class="icon" icon="pencil" variant="primary"></b-icon></span>
                  <span class="spanHover mr-2"><b-icon class="icon" icon="x-square-fill" variant="danger"></b-icon></span>
                </div>
              </div>
            </td>
            <td class="text-center font-weight-bold">
              <span class="spanHover"><b-icon class="icon" icon="plus-circle-fill" variant="success"></b-icon></span>
            </td>
          </tr>
          <tr>
            <th>Сумма заказа:</th>
            <td>{{ Sum }}</td>
            <td></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import {mapActions} from "vuex";

export default {
  data () {
    return {
      order_id: this.$route.params.id,
      order: null,
      detailsOrder: {
        status: null,
        client_email: null,
        delivery_dt: null,
      },
      detailsPartner: {
        name:null,
        email: null
      }
    }
  },
  computed: {
    Sum () {
      if (this.order) {
        let sum = 0;
        return this.order.order_products.map(i => sum += i.price).reverse()[0];
      }
    }
  },
  methods: {
    back() {
      this.$router.push({name: 'orders.list'});
    },
    ...mapActions("orders", ["getOrderById"]),
    getOrder() {
      this.getOrderById(this.$route.params.id)
        .then(() => {
          this.order = this.$store.getters["orders/order"];
          //console.log(this.order)
        })
    }
  },
  created() {
    this.getOrder();
  }
}
</script>

<style>
.breadcrumb {
  border: 2px solid indigo;
}

.spanBreadcrumbs {
  font-weight: bold;
  color: indigo;
}

.spanHover:hover {
  opacity: 0.7;
  cursor: pointer;
}
.icon {
  font-size: 18px;
}
</style>