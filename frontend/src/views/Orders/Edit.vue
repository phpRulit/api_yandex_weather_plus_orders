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
            <span class="spanHover" v-if="order.status !== 20" @click="getPartnersModal"><b-icon class="icon" icon="binoculars" variant="primary"></b-icon></span>
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
    <transition name="modal" v-if="showPartnersModal">
      <div class="modal-mask">
        <div class="modal-wrapper fixed-overlay">
          <div class="modal-dialog" role="document">
            <div class="modal-content class-w400px">
              <div class="modal-header">
                <h5 class="modal-title">Смена партнёра</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true" @click="getPartnersModal">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <p class="p-0 pb-2">
                  Вы уверены, что хотите сменить партнёра для данного заказа?
                  Если да, то введите наименование организации нового партнёра,
                  сделайте выбор и сохраните изменения...
                </p>
                <div class="mb-3">
                  <div v-if="!loadingProcess">
                    <div v-if="errors.partner_id" class="alert alert-danger" role="alert">
                      {{ errors.partner_id[0] }}
                    </div>
                    <input v-model="partnerChoice" id="title" type="text" list="inputList" class="form-control" placeholder="Наименование организации...">
                    <dataList id="inputList">
                      <option :value="prompt.name" v-for="(prompt, index) in Partners" :key="index"></option>
                    </dataList>
                  </div>
                  <div v-else class="text-center">
                    Подгружаю партнёров...
                  </div>
                </div>
                <div v-if="partner && partner.length === 1">
                  <span class="mb-2">Ваш выбор...</span>
                  <table class="table table-bordered small">
                    <tbody>
                    <tr class="p-0">
                      <td>{{partner[0].name}}</td>
                      <td>{{partner[0].email}}</td>
                    </tr>
                    </tbody>
                  </table>
                  <button class="btn btn-primary w-100 mt-3" @click="changePartner">Сохранить изменения</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
    <div class="table-responsive">
      <h4>Заказ:</h4>
      <table class="table table-bordered small">
        <tbody>
        <tr>
          <th class="col-2">Статус:</th>
          <td  class="col-9">
            <div v-if="!showStatusSelect">
              <span v-if="order.status === 0">НОВЫЙ</span>
              <span v-else-if="order.status === 10">ПОДТВЕРЖДЁН</span>
              <span v-else-if="order.status === 20">ЗАВШЁН</span>
            </div>
            <div class="col-12 row p-0" v-else>
              <div class="col-lg-5 col-md-9 col-sm-12 col-xs-12 pr-0">
                <div class="input-group pr-0">
                  <select class="form-control" v-model="details.status" :class="{ 'is-invalid': errors.status }">
                    <option :value="status.value" v-for="status in statuses">{{status.text}}</option>
                  </select>
                  <div class="invalid-feedback" v-if="errors.status">{{ errors.status[0] }}</div>
                  <div class="input-group-append pr-0">
                    <button @click="editOrder" :disabled="details.status === order.status" class="btn btn-primary" type="button"><b-icon class="icon" icon="arrow-counterclockwise"></b-icon></button>
                    <button class="btn btn-secondary" @click="getStatusSelect">X</button>
                  </div>
                </div>
              </div>
            </div>
          </td>
          <td class="text-center col-1">
            <span class="spanHover" v-if="!showStatusSelect" @click="getStatusSelect"><b-icon class="icon" icon="pencil" variant="primary"></b-icon></span>
          </td>
        </tr>
        <tr>
          <th>ID:</th>
          <td>{{ order.id }}</td>
          <td></td>
        </tr>
        <tr>
          <th>Дата доставки:</th>
          <td>
            {{order.delivery_dt ? this.$moment(order.delivery_dt).format('L') : 'не назначена'}}
          </td>
          <td class="text-center">
            <span class="spanHover" v-if="order.status !== 20" @click="getModalCalendar"><b-icon class="icon" icon="pencil" variant="primary"></b-icon></span>
          </td>
        </tr>
        <tr>
          <th>Email клиента:</th>
          <td>
            <span v-if="!showEmailClientInput">{{ order.client_email }}</span>
            <div class="col-12 row p-0" v-else>
              <div class="col-lg-5 col-md-9 col-sm-12 col-xs-12 pr-0">
                <div class="input-group pr-0">
                  <input type="text" class="form-control" v-model="details.client_email" :class="{ 'is-invalid': errors.client_email }" placeholder="Введите email клиента..." aria-label="Имя получателя" aria-describedby="basic-addon2">
                  <div class="input-group-append pr-0">
                    <button @click="editOrder" :disabled="details.client_email === order.client_email" class="btn btn-primary" type="button"><b-icon class="icon" icon="arrow-counterclockwise"></b-icon></button>
                    <button class="btn btn-secondary" @click="getEmailClientInput">X</button>
                  </div>
                </div>
                <div class="invalid-feedback" v-if="errors.client_email">{{ errors.client_email[0] }}</div>
              </div>
            </div>
          </td>
          <td class="text-center">
            <span class="spanHover" v-if="!showEmailClientInput && order.status !== 20" @click="getEmailClientInput"><b-icon class="icon" icon="pencil" variant="primary"></b-icon></span>
          </td>
        </tr>
        <tr>
          <th>Состав заказа:</th>
          <td>
            <div class="row mb-2" v-for="(op, indexOp) in order.order_products">
              <div class="col-md-10">{{indexOp + 1}}.
                <span v-if="indexOp === indexP" v-for="(p, indexP) in order.products">
                    {{p.name}}; Количество: {{op.quantity}}; Цена: {{p.price}}; Общая стоимость: {{op.quantity * p.price}}
                  </span>
              </div>
              <div class="col-md-2 text-right" v-if="order.status !== 20">
                <span class="spanHover mr-2" @click="getModalEditQuantityItem(indexOp)"><b-icon class="icon" icon="pencil" variant="primary"></b-icon></span>
                <span class="spanHover mr-2" @click="destroyItemFromOrder(indexOp)"><b-icon class="icon" icon="trash-fill" variant="danger"></b-icon></span>
              </div>
            </div>
          </td>
          <td class="text-center font-weight-bold">
            <span v-if="order.status !== 20" class="spanHover" @click="getProductsModal"><b-icon class="icon" icon="plus-circle-fill" variant="success"></b-icon></span>
          </td>
        </tr>
        <tr>
          <th>Сумма заказа:</th>
          <td>{{ Sum }}</td>
          <td></td>
        </tr>
        </tbody>
      </table>
      <transition name="modal" v-if="showModalCalendar">
        <div class="modal-mask">
          <div class="modal-wrapper fixed-overlay">
            <div class="modal-dialog" role="document">
              <div class="modal-content class-w400px">
                <div class="modal-header">
                  <h5 class="modal-title">Дата доставки</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" @click="getModalCalendar">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <p class="p-0 pb-2">
                    Вы уверены, что хотите изменить дату доставки, для данного заказа?
                    Если да, выберите новую дату и сохраните изменения...
                  </p>
                  <div class="mb-3">
                    <b-row>
                      <div class="invalid-feedback" v-if="errors.delivery_dt"> {{ errors.delivery_dt[0] }}</div>
                      <b-col>
                        <b-calendar
                            :class="{ 'border border-danger': errors.delivery_dt }"
                            block
                            hide-header
                            label-prev-year="Предыдущий год"
                            label-prev-month="Предыдущий месяц"
                            label-current-month="Текущий месяц"
                            label-next-month="Следующий месяц"
                            label-next-year="Следующий год"
                            v-model="details.delivery_dt" @context="onContext" locale="ru"
                        ></b-calendar>
                      </b-col>
                    </b-row>
                  </div>
                  <button class="btn btn-primary w-100 mt-3" :disabled="details.delivery_dt === order.delivery_dt" @click="editOrder">Сохранить изменения</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </transition>
      <transition name="modal" v-if="showModalOrderProducts">
        <div class="modal-mask">
          <div class="modal-wrapper fixed-overlay">
            <div class="modal-dialog" role="document">
              <div class="modal-content class-w400px">
                <div class="modal-header">
                  <h5 class="modal-title">Добавление товара</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" @click="getProductsModal">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <p class="p-0 pb-2">
                    Вы уверены, что хотите добавить товар в данный заказ?
                    Если да, то введите наименование товара.
                    Сделайте выбор, затем введите количество, к добавлению
                    и сохраните изменения...
                  </p>
                  <div class="mb-3">
                    <div v-if="this.Products.length > 0 && !loadingProcess">
                      <input v-model="productChoice" type="text" list="inputProducts" class="form-control" placeholder="Наименование товара...">
                      <dataList id="inputProducts">
                        <option :value="prompt.name" v-for="(prompt, index) in Products" :key="index"></option>
                      </dataList>
                    </div>
                    <div v-else-if="this.Products.length === 0" class="text-center">
                      {{ loadingProcess ? 'Подгружаю товары...' : 'Нет товаров к добавлению...' }}
                    </div>
                  </div>
                  <div v-if="product && product.length === 1">
                    <span class="mb-2">Ваш выбор...</span>
                    <table class="table table-bordered small">
                      <tbody>
                      <tr>
                        <th>Товар:</th>
                        <td>{{ product[0].name }}</td>
                      </tr>
                      <tr>
                        <th>Цена за ед.:</th>
                        <td>{{ product[0].price }}</td>
                      </tr>
                      </tbody>
                    </table>
                    <input v-model="quantity" type="number" min="1" class="form-control" :class="{ 'is-invalid': errors.quantity }" placeholder="Введите количество к добавлению...">
                    <div class="invalid-feedback" v-if="errors.quantity"> {{ errors.quantity[0] }}</div>
                    <button :disabled="!quantity" class="btn btn-primary w-100 mt-3" @click="addItemOrder">Добавить товар</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </transition>
      <transition name="modal" v-if="showModalEditQuantityItem">
        <div class="modal-mask">
          <div class="modal-wrapper fixed-overlay">
            <div class="modal-dialog" role="document">
              <div class="modal-content class-w400px">
                <div class="modal-header">
                  <h5 class="modal-title">Изменение количества товара</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" @click="getModalEditQuantityItem(null)">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <p class="p-0 pb-2">
                    Измените количество товара и сохраните изменения...
                  </p>
                  <div>
                    <table class="table table-bordered small">
                      <tbody>
                      <tr>
                        <th>Товар:</th>
                        <td>{{ editProduct.name }}</td>
                      </tr>
                      <tr>
                        <th>Цена за ед.:</th>
                        <td>{{ editProduct.price }}</td>
                      </tr>
                      </tbody>
                    </table>
                    <label for="quantity" class="form-check-label">Количество товара:</label>
                    <input v-model="quantity" type="number" min="1" id="quantity" class="form-control text-center" :class="{ 'is-invalid': errors.quantity }" placeholder="Введите количество к добавлению...">
                    <div class="invalid-feedback" v-if="errors.quantity"> {{ errors.quantity[0] }}</div>
                    <button :disabled="!quantity" class="btn btn-primary w-100 mt-3" @click="editQuantityItem">Сохранить изменения</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </div>
  </div>
</template>

<script>
import {mapActions, mapGetters} from "vuex";

export default {
  data () {
    return {
      order_id: this.$route.params.id,
      order: null,
      showStatusSelect: false,
      showEmailClientInput: false,
      showModalCalendar: false,
      statuses: [
        {value: 0, text: "НОВЫЙ"},
        {value: 10, text: "ПОДТВЕРЖДЁН"},
        {value: 20, text: "ЗАВЕРШЁН"},
      ],
      details: {
        status: null,
        client_email: null,
        delivery_dt: null,
      },
      loadingProcess: true,
      //partners:
      showPartnersModal: false,
      partners: [],
      partnerChoice: null,
      //order_products:
      productsOrderIds: [],
      showModalOrderProducts: false,
      products: [],
      productChoice: null,
      quantity: null,
      indexOrderProduct: null,
      showModalEditQuantityItem: false,
      editProduct: null
    }
  },
  watch: {
    showStatusSelect (newVal) {
      if (newVal) {
        this.showEmailClientInput = false;
        this.showModalCalendar = false;
      }
    },
    showEmailClientInput (newVal) {
      if (newVal) {
        this.showStatusSelect = false;
        this.showModalCalendar = false;
      }
    },
    showModalCalendar (newVal) {
      if (newVal) {
        this.showStatusSelect = false;
        this.showEmailClientInput = false;
      }
    }
  },
  computed: {
    ...mapGetters(["errors"]),
    Sum () {
      if (this.order) {
        let sum = 0;
        return this.order.order_products.map(i => sum += i.price).reverse()[0];
      }
    },
    Partners () {
      return this.partners.length > 0 ? this.partners.filter(p => {
        return p.id !== this.order.partner.id;
      }) : []
    },
    partner () {
      return this.Partners.length > 0 && this.partnerChoice ? this.Partners.filter(p => {
        return p.name === this.partnerChoice;
      }) : null
    },
    Products () {
      return this.products.length > 0 ? this.products.filter(p => {
        return !this.productsOrderIds.includes(p.id);
      }) : []
    },
    product () {
      return this.Products.length > 0 && this.productChoice ? this.Products.filter(p => {
        return p.name === this.productChoice;
      }) : null
    },
  },
  methods: {
    back() {
      this.$router.push({name: 'orders.list'});
    },
    setDataToDetails() {
      this.details.status = this.order.status;
      this.details.client_email = this.order.client_email;
      this.details.delivery_dt = this.order.delivery_dt;
    },
    ...mapActions("orders", ["getOrderById"]),
    getOrder() {
      this.getOrderById(this.order_id)
          .then(() => {
            this.order = this.$store.getters["orders/order"];
            this.setDataToDetails();
            this.order.products.forEach(item => {
              this.productsOrderIds.push(item.id);
            })
          })
    },
    getPartnersModal() {
      this.showPartnersModal = !this.showPartnersModal;
      this.partnerChoice = null;
      if (this.partners.length === 0) {
        this.getPartners();
      }
    },
    ...mapActions("data", ["getPartnersList"]),
    getPartners() {
      this.loadingProcess = true;
      this.getPartnersList()
          .then(() => {
            this.partners = this.$store.getters["data/partners"];
            this.loadingProcess = false;
          })
    },
    ...mapActions("orders", ["setNewPartner"]),
    changePartner() {
      this.setNewPartner({order_id: this.order_id, partner_id: this.partner[0].id})
          .then(() => {
            if (this.$store.getters["messageError"]) {
              this.$toastr.e(this.$store.getters["messageError"]);
            } else if (this.$store.getters["messageSuccess"]) {
              this.order.partner = this.partner[0];
              this.getPartnersModal();
              this.$toastr.s(this.$store.getters["messageSuccess"]);
            }
          })
    },
    getStatusSelect () {
      this.showStatusSelect = !this.showStatusSelect;
      if (!this.showStatusSelect) this.setDataToDetails();
    },
    getEmailClientInput () {
      this.showEmailClientInput = !this.showEmailClientInput;
      if (!this.showEmailClientInput) this.setDataToDetails();
    },
    getModalCalendar () {
      this.showModalCalendar = !this.showModalCalendar;
      if (!this.showModalCalendar) this.setDataToDetails();
    },
    onContext(ctx) {
      this.context = ctx
    },
    ...mapActions("orders", ["setChangesOrder"]),
    ...mapActions("orders", ["sentMailOrderCompleted"]),
    editOrder() {
      let details = {};
      details['order_id'] = this.order_id;
      details['status'] = this.showStatusSelect ? this.details.status : null;
      details['client_email'] = this.showEmailClientInput ? this.details.client_email : null;
      details['delivery_dt'] = this.showModalCalendar ? this.details.delivery_dt : null;
      this.setChangesOrder(details)
          .then(() => {
            if (this.$store.getters["messageError"]) {
              this.$toastr.e(this.$store.getters["messageError"]);
            } else if (this.$store.getters["messageSuccess"]) {
              this.order.status = this.details.status;
              this.order.client_email = this.details.client_email;
              this.order.delivery_dt = this.details.delivery_dt;
              this.showStatusSelect = false;
              this.showEmailClientInput = false;
              this.showModalCalendar = false;
              this.setDataToDetails();
              this.$toastr.s(this.$store.getters["messageSuccess"]);
              if (this.order.status === 20) {
                this.sentMailOrderCompleted(this.order_id);
              }
            }
          })
    },
    ...mapActions("data", ["getProductsList"]),
    getProducts() {
      this.loadingProcess = true;
      this.getProductsList()
          .then(() => {
            this.products = this.$store.getters["data/products"];
            this.loadingProcess = false;
          })
    },
    getProductsModal() {
      if (this.products.length === 0) {
        this.getProducts();
      }
      this.showModalOrderProducts = !this.showModalOrderProducts;
      this.productChoice = null;
      this.quantity = null;
    },
    ...mapActions("orders", ["setItemInOrder"]),
    addItemOrder() {
      let details = {};
      details['order_id'] = this.order_id;
      details['product_id'] = this.product[0].id;
      details['quantity'] = this.quantity;
      this.setItemInOrder(details)
          .then(() => {
            if (this.$store.getters["messageError"]) {
              this.$toastr.e(this.$store.getters["messageError"]);
            } else if (this.$store.getters["messageSuccess"]) {
              this.order.order_products.push(this.$store.getters["orders/order_product"]);
              this.order.products.push(this.product[0]);
              this.productsOrderIds.push(this.product[0].id)
              this.getProductsModal();
              this.$toastr.s(this.$store.getters["messageSuccess"]);
            }
          })
    },
    ...mapActions("orders", ["destroyItemOrder"]),
    destroyItemFromOrder(indexOrderProduct) {
      let result = confirm('Вы действительно хотите удалить позицию из заказа? Если да, подтвердите действие...')
      if (result) {
        this.destroyItemOrder({order_id: this.order_id, order_product_id: this.order.order_products[indexOrderProduct].id})
            .then(() => {
              if (this.$store.getters["messageError"]) {
                this.$toastr.e(this.$store.getters["messageError"]);
              } else if (this.$store.getters["messageSuccess"]) {
                let index = this.productsOrderIds.indexOf(this.order.order_products[indexOrderProduct].product_id);
                if (index !== -1) {
                  this.productsOrderIds.splice(index, 1);
                }
                this.order.order_products.splice(indexOrderProduct, 1);
                this.$toastr.s(this.$store.getters["messageSuccess"]);
              }
            });
      }
    },
    getModalEditQuantityItem(indexOrderProduct) {
      let order_product = indexOrderProduct !== null ? this.order.order_products[indexOrderProduct] : null;
      this.indexOrderProduct = indexOrderProduct !== null ? indexOrderProduct : null;
      this.quantity = indexOrderProduct !== null ? order_product.quantity : null;
      this.editProduct = indexOrderProduct !== null
          ? this.order.products[this.order.products.findIndex(el => el.id === order_product.product_id)]
          : null;
      this.showModalEditQuantityItem = !this.showModalEditQuantityItem;
    },
    ...mapActions("orders", ["editQuantityItemInOrder"]),
    editQuantityItem() {
      let details = {};
      details['order_id'] = this.order_id;
      details['order_product_id'] = this.order.order_products[this.indexOrderProduct].id;
      details['product_id'] = this.editProduct.id;
      details['quantity'] = this.quantity;
      this.editQuantityItemInOrder(details)
          .then(() => {
            if (this.$store.getters["messageError"]) {
              this.$toastr.e(this.$store.getters["messageError"]);
            } else if (this.$store.getters["messageSuccess"]) {
              this.order.order_products[this.indexOrderProduct].quantity = this.quantity;
              this.order.order_products[this.indexOrderProduct].price = this.quantity * this.editProduct.price;
              this.getModalEditQuantityItem(null);
              this.$toastr.s(this.$store.getters["messageSuccess"]);
            }
          })
    }
  },
  created() {
    this.getOrder();
  }
}
</script>
