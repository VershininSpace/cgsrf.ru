<template>
	<div id="buymodal2" class="uk-flex-top buymodal" uk-modal="">
		<div class="uk-modal-dialog uk-margin-auto-vertical uk-modal-body">
			<button class="uk-modal-close-default" type="button"><span uk-icon="close"></span></button>
			<h2 class="uk-modal-title">{{ buymodal.title }}</h2>
			<div class="buymodal__block" v-if="metki4.ref.value">
				<div class="buymodal__podp" v-if="buymodal.pay == 1">Выберите дату, заполните поля и вас перенаправят на платежную форму для покупки Вашего билета</div>
				<div class="buymodal__price">
					Стоимость мастер-класса: <span>{{ product.price }} руб</span>
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
				<form class="js_form myform uk-form" v-on:submit.prevent="sendform($event)">
					<transition name="fade">
						<div class="account__errors" v-if="errors">{{ errors }}</div>
					</transition>
					<input type="hidden" name="ref" :value="metki4.ref.value" />
					<input type="hidden" name="product" :value="product.code" />
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
					<input class="uk-input" placeholder="Как к вам обращаться*" type="text" required="" name="name" />
					<input class="uk-input" placeholder="Email* " required="" type="email" name="email" />
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
					<button class="uk-button uk-button-primary uk-width-1-1" type="submit" :disabled="disableButton">Отправить</button>
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
  import wait from './wait'

  export default {
    name: "buy-modal2",
    data() {
      return {
        metki4: {
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
      disableButton(){
        if(!this.selectedTime || (this.selectedTime && this.selectedTime.is_active == 1)){
          console.log('true');
          return true;
        } else {
          console.log('false');
          return false;
        }
      }
	  },
    methods: {

    },
    mounted() {
      this.setParams(this.getMetkiFromStorage());
    }
  }
</script>