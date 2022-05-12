<template> 
	<div id="buymodal" class="uk-flex-top buymodal" uk-modal="">
		<div class="uk-modal-dialog uk-margin-auto-vertical uk-modal-body">
			<button class="uk-modal-close-default" type="button"><span uk-icon="close"></span></button>
			<h2 class="uk-modal-title">{{ buymodal.title }}</h2>
			<div class="buymodal__block" v-if="metki2.ref.value">
				<div class="buymodal__podp" v-if="buymodal.pay == 1">jj Заполните поля и вас перенаправят на платежную форму для покупки Вашего билета</div>
				<form class="js_form myform uk-form" v-on:submit.prevent="sendform($event)">
					<transition name="fade">
						<div class="account__errors" v-if="errors">{{ errors }}</div>
					</transition>
					<!--<input type="hidden" name="product2" value="234234" />-->
					<input type="hidden" name="product" :value="buymodal.kurs" />
					<input type="hidden" name="pay" :value="buymodal.pay" />
					<input type="hidden" name="ref" :value="metki2.ref.value" />
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
					<input class="uk-input" placeholder="Как к вам обращаться*" type="text" name="name" required/>
					<input class="uk-input" placeholder="Email* " type="email" name="email" required/>
					<input class="uk-input" placeholder="Телефон* " type="text" name="phone" v-mask="'+7 (###) ###-##-##'" required v-model="inputPhoneModel" @blur="clearIncompletedField()"/>
					<div class="confid">
						<label>
							<input type="checkbox" v-model="$parent.confid" /><span>Я согласен с</span><a uk-toggle="target: #politicamodal">политикой конфиденциальности</a>
						</label>
					</div>
					<button class="uk-button uk-button-primary uk-width-1-1" type="submit" :disabled="!$parent.confid">Отправить</button>
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
    name: "buy-modal",
    data() {
      return {
		inputPhoneModel: '',
        metki2: {
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
        }
      }
    },
    props: {
      items: '',
	    buymodal: ''
    },
    components: { wait },
    mixins: [account, modals],
    methods: {

    },
	methods: {
      clearIncompletedField() {
        if (this.inputPhoneModel.length < 18) {
          this.inputPhoneModel = '';
        }
      }
	},
    mounted() {
      this.setParams(this.getMetkiFromStorage());
	  //this.test();
    }
  }
</script>