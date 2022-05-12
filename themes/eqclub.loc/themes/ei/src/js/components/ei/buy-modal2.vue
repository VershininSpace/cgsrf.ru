<template>
	<div id="buymodal2" class="uk-flex-top buymodal" uk-modal="">
		<div class="uk-modal-dialog uk-margin-auto-vertical uk-modal-body">
			<button class="uk-modal-close-default" type="button"><span uk-icon="close"></span></button>
			<h2 class="uk-modal-title">{{ buymodal.title }}</h2>
			<div class="buymodal__block" v-if="metki.ref.value">
				<div class="buymodal__podp" v-if="buymodal.pay == 1 && selectedTime">Выберите дату, заполните поля и вас перенаправят на платежную форму для покупки Вашего билета</div>
				<div class="buymodal__subtitle" v-if="product && product.title">{{ product.title }}</div>
				<div class="buymodal__price" v-if="product && product.price">
					Стоимость: <span>{{ product.price }} руб</span>
					<div v-if="promocode_value && product.price > promocode_value" class="promocode">Скидка:
						<span class="code">{{ (product.price / 100) * promocode_value }}</span> руб
					</div>
					<div v-if="promocode_value && product.price < promocode_value" class="promocode promocode--attent">Данный промокод не распространяется на выбранный продукт</div>
					<!--<p style="color: red;font-size: 0.8em;line-height: 1.2;">При покупке билета вы имеете право взять с собой + 1 человека бесплатно!</p>-->
				</div>
				<div class="buymodal__mest" v-if="selectedTime">
					<div v-if="selectedTime.is_active == 0">
						<p>Билетов куплено: <span>{{ selectedTime.tickets }}</span></p>
						<p>Осталось мест: <span>{{ selectedTime.places }}</span></p>
					</div>
					<div v-else>
						<p class="noactive">
							Все билеты на выбранную дату проданы. Мест нет. Выберите другую дату.
						</p>
					</div>
				</div>
				<form class="js_form myform uk-form" v-on:submit.prevent="buyProduct">
					<transition name="fade">
						<div class="account__errors" v-if="errors">{{ errors }}</div>
					</transition>
					<input type="hidden" name="ref" :value="metki.ref.value" />
					<input v-if="product && product.code" type="hidden" name="product" :value="product.code" />
					<input type="hidden" name="pay" :value="buymodal.pay" />
					<input type="hidden" name="current_date" :value="selectedTime.time" />
					<!--<h4>Выберите способ оплаты</h4>
					<ul class="buymodal__sposob">
						<li>
							<label>
								<input type="radio" class="uk-radio" name="type" checked>
								<span>Банковской картой</span>
							</label>
						</li>
						<li>
							<label>
								<input type="radio" class="uk-radio" name="type">
								<span>Криптовалютой Prizm</span>
							</label>
						</li>
					</ul>-->
					<input class="uk-input" placeholder="Как к вам обращаться*" type="text" required name="name" v-model="userName"/>
					<input class="uk-input" placeholder="Email* " required type="email" name="email" v-model="userEmail"/>
					<input class="uk-input" placeholder="Телефон* " required type="text" name="phone" v-model="userPhone" v-mask="'+7 (###) ###-##-##'"  @blur="clearIncompletedField()"/>
					<!--<input class="uk-input" placeholder="Промокод" type="text" name="promocode" v-model="promocode" @change="checkPromocode"/>-->
					<div class="buymodal__link">
						<input class="uk-input" placeholder="Введите промокод" type="text" name="promocode" v-model="promocode" />
						<button class="" @click.prevent="checkPromocode">Применить</button>
					</div>
					<div v-if="times">
						<select name="day" class="uk-select" required v-model="selectedTime">
							<option value="" selected disabled>Выберите дату</option>
							<option v-for="time in times" :value="time">{{ time.time }}</option>
						</select>
					</div>
					<div class="confid">
						<label>
							<input type="checkbox" v-model="$parent.confid" /><span>Я согласен с</span><a uk-toggle="target: #politicamodal">политикой конфиденциальности</a>
						</label>
					</div>
					<!--<button class="uk-button uk-button-primary uk-width-1-1" type="submit" :disabled="!$parent.confid && disableButton">Отправить</button>-->
					<button class="uk-button uk-button-primary uk-width-1-1" type="submit" :disabled="disableButton">ПЕРЕЙТИ К ОПЛАТЕ</button>
				</form>
			</div>
			<div class="buymodal__block" v-else>
				Пройти дальше можно только по рекомендации члена клуба. Не знаете где её взять?
				<a href="/send" class="uk-button uk-button-text">Напишите нам</a>
			</div>
		</div>
		<wait></wait>
	</div>
</template>

<script>
  import { bus } from './bus.js'
  import { account } from "../mixins/account";
  import { modals } from "../mixins/modals";
  import wait from './wait';
  import swal from "sweetalert";
  import { v4 as uuidv4 } from 'uuid';

  export default {
    name: "buy-modal2",
    data() {
      return {
        userName: '',
        userEmail: '',
        userPhone: '',
        metki: {
          ref: {
            value: '',
            show: true
          },
          email: {
            value: '',
            show: true
          },
          ref_email: {
            value: '',
            show: true
          },
          name: {
            value: '',
            show: true
          },
        },
        selectedTime: ''
      }
    },
    props: {
      items: '',
      buymodal: '',
      times: '',
      product: ''
    },
    components: { wait },
    mixins: [account, modals],
    computed: {
      disableButton() {
        if ((this.times && !this.selectedTime) || (this.selectedTime && this.selectedTime.is_active == 1)) {
          //console.log('true');
          return true;
        } else {
         // console.log('false');
          return false;
        }
      }
    },
    methods: {
      clearIncompletedField() {
        if (this.userPhone.length < 18) {
          this.userPhone = '';
        }
      },
		buyProduct(e){
			let eventForm = e;
			let price = this.product.price
			if (this.$data.promocode_value) price = (this.product.price / 100) * this.$data.promocode_value;
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
              visible: (this.product.price > 3000 && this.product.price < 500000) ? true:false,
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
                    var uuid = uuidv4();
                    var t_link = tinkoff.createLink({
                                      orderNumber: uuid,
                                      sum: parseInt(price),
                                      items: [{name: 'Оплата на сайте Eqclub.ru', price: parseInt(price), quantity: 1}],
                                      promoCode: choise,
                                      shopId: 'bfd3eb56-937e-4c2c-aae2-07cc4068032c',
                                      showcaseId: '7c52d178-29b6-47b4-bb41-32a65abf9a5b',
                                    });
                    var self_promocode = this.$data.promocode;
                    var self_promocode_value = this.$data.promocode_value;
                    var self_email = this.$data.userEmail;
                    var self_name = this.$data.userName;
                    var self_phone = this.$data.userPhone;
                    var self_metki = this.metki;
                    var self_buymodal = this.buymodal;
                    var self_product = this.product;


                    t_link.then(function(url) {
                        var data = {
                          'name': self_name,
                          'email': self_email,
                          'phone': self_phone,
                          'promocode': self_promocode,
                          'url': url,
                          'uuid': uuid,
                          'ref': self_metki.ref.value,
                          'pay': self_buymodal.pay,
                          'product': self_product.code
                        };
                        $.request('onModalBuyProduct', {
                          data: data,
                          success: function (data) {
                            window.open(url, '_blank').focus();
                          },
                          error: function (err) {
                            console.log(err);
                          }
                        });
                    });
                  }
                });
                break;
              case 'pay':
                this.sendform(eventForm);
                break;
            }
          } else {
            console.log('Отказ');
          }
        });
    	}
	},
    mounted() {
      this.setParams(this.getMetkiFromStorage());
    }
  }
</script>