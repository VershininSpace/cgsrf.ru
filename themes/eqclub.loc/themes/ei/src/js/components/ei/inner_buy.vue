<template>
	<div>
		<div class="buy__discount" v-if="promocode_value">
			<small>Скидка:</small>
			{{ (+price/100) * promocode_value }} ₽
		</div>
		<div class="buy__price">
			<small>Стоимость:</small>
			<span v-if="promocode_value"><del>{{ price}}</del></span> {{ +price - ((+price/100) * promocode_value) }} ₽
		</div>
		<div class="buy__button">
			<a href="#" class="uk-button uk-button-primary" @click.prevent="buyProduct">Купить</a>
			<!--<a class="uk-button uk-button-primary" data-request="onBuyProduct"
			   data-request-confirm="Вы хотите купить этот продукт?"
			   data-request-data="id:{{ product.id }}, backurl:'{{ url('/account/check') }}'"
			   data-request-success=""
			>Купить</a>-->
		</div>
		<div class="buy__promocode">
			<!--<small>Промокод:</small>-->
			<!--<input type="text" name="promocode" @change="checkPromocode" v-model="promocode">-->
			<!--<input placeholder="Введите промокод" type="text" name="promocode" v-model="promocode" />
			<div class="buymodal__link">
				<a href="#" class="" @click.prevent="checkPromocode">Применить промокод</a>
			</div>-->
			<div class="buymodal__link">
				<input class="uk-input" placeholder="Введите промокод" type="text" name="promocode" v-model="promocode" />
				<button class="" @click.prevent="checkPromocode">Применить</button>
			</div>
		</div>
	</div>
</template>

<script>
  import swal from "sweetalert";
  import { v4 as uuidv4 } from 'uuid';

  export default {
    name: "inner_buy",
    data() {
      return {
        promocode: '',
        promocode_value: 0
      }
    },
    props: {
      id: '',
      price: '',
      name: ''
    },
    methods: {
      checkPromocode() {
        console.log('CheckPromocode');
        //bus.$emit('wait', true);
        let app = this;
        let promocode = this.promocode;
        $.request('onCheckPromocode', {
          data: { promocode },
          success: function (data) {
            console.log(data);
            //bus.$emit('wait', false);
            app.$root.wait = false;
            if (data.amount) {
              swal("Промокод найден", {
                icon: 'success',
                buttons: false,
                timer: 1000,
              });
              app.promocode_value = data.amount*1;
            } else {
              swal("Промокод не найден", {
                icon: 'error',
                buttons: false,
                timer: 1000,
              });
            }
          },
          error: function (err) {
            console.log(err);
          }
        });
      },
      buyProduct() {
        let app = this;
        let data = {
          'id': this.id,
          'promocode': this.promocode,
          'promocode_value': this.promocode_value
        };
        swal({
          //title: 'Вы хотите купить этот продукт?',
          icon: '/themes/ei/assets/img/logo/pay.png',
          buttons: {
            cancel: {
              text: "Отмена",
              value: null,
              visible: true,
              className: "",
              closeModal: true,
            },
            credit: {
              text: "В рассрочку",
              value: "credit",
              className: "btnTinkoff",
              visible: (this.price > 3000 && this.price < 500000) ? true:false,
            },
            pay: {
              text: "Оплата online",
              className: "roboKassaBtn",
              value: "pay",
            },
          },
          dangerMode: true,
        })
        .then((choise) => {
          if (choise) {
            console.log('Согласен');
            switch (choise) {
              case 'credit':
                swal({
                  title: 'На сколько месяцев?',
                  icon: '/themes/ei/assets/img/logo/tinkoff.png',
                  buttons: {
                    m4: {
                      text: "4",
                      value: "installment_0_0_4_5",
                      className: "btnTinkoff"
                    },
                    m6: {
                      text: "6",
                      value: "installment_0_0_6_6",
                      className: "btnTinkoff"
                    },
                    m10: {
                      text: "10",
                      value: "installment_0_0_10_10",
                      className: "btnTinkoff"
                    },
                    m12: {
                      text: "12",
                      value: "installment_0_0_12_11",
                      className: "btnTinkoff"
                    },
                  },
                  dangerMode: true,
                }).then((choise) => {
                  if (choise) {
                    data.tinkoff = true;
                    $.request('onBuyProduct', {
                      data: data,
                      success: function (data_price) {
                        var uuid = uuidv4();
                        var t_link = tinkoff.createLink({
                                          orderNumber: uuid,
                                          sum: parseInt(data_price.price),
                                          items: [{name: 'Оплата на сайте Eqclub.ru', price: parseInt(data_price.price), quantity: 1}],
                                          promoCode: choise,
                                          shopId: 'bfd3eb56-937e-4c2c-aae2-07cc4068032c',
                                          showcaseId: '7c52d178-29b6-47b4-bb41-32a65abf9a5b',
                                        });
                        t_link.then(function(url) {
                            data.url = url;
                            data.uuid = uuid;
                            delete data.tinkoff;
                            $.request('onBuyProduct', {
                              data: data,
                              success: function (data) {
                                window.open(url, '_blank').focus();
                              },
                              error: function (err) {
                                console.log(err);
                              }
                            });
                        });
                      },
                      error: function (err) {
                        console.log(err);
                      }
                    });
                  }
                });
                break;
              case 'pay':
                $.request('onBuyProduct', {
                  data: data,
                  success: function (data) {
                    console.log(data);
                    if(data.X_OCTOBER_REDIRECT){
                      window.location.href = data.X_OCTOBER_REDIRECT;
                    }
                  },
                  error: function (err) {
                    console.log(err);
                  }
                });
                break;
            }
          } else {
            console.log('Отказ');
          }
        });
      }
    }
  }
</script>