<template>
	<div>
		<div class="buymodal__block buymodal__local" v-if="metki5.ref.value">
			<form class="js_form myform uk-form" v-on:submit.prevent="sendform($event)">
				<transition name="fade">
					<div class="account__errors" v-if="errors">{{ errors }}</div>
				</transition>
				<input type="hidden" name="pay" :value="buymodal.pay" />
        <input type="hidden" name="ref" :value="metki5.ref.value" />
				<input class="uk-input" placeholder="Как к вам обращаться*" type="text" required name="name" />
				<input class="uk-input" placeholder="Email* " required type="email" name="email" />
				<input class="uk-input" placeholder="Телефон* " required type="text" name="phone" v-model="inputPhoneModel" v-mask="'+7 (###) ###-##-##'"  @blur="clearIncompletedField()"/>
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
		<wait></wait>
	</div>
</template>

<script>
  import { bus } from './bus.js'
  import { account } from "../mixins/account";
  import { modals } from "../mixins/modals";
  import wait from './wait'

  export default {
    name: "enter-webinar",
    data() {
      return {
        inputPhoneModel: '',
        metki5: {
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
      clearIncompletedField() {
        if (this.inputPhoneModel.length < 18) {
          this.inputPhoneModel = '';
        }
      }
    },
    mounted() {
      this.setParams(this.getMetkiFromStorage());
    }
  }
</script>