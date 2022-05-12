<template>
	<div>
		<!--<a @click="test()">Test</a>-->
		<form v-if="metki1.ref.value" class="account_ajax account__form" @submit.prevent="sendReg">
			<transition name="fade">
				<div class="account__errors" v-if="errors">{{ errors }}</div>
			</transition>

			<input class="uk-input" name="ref" type="hidden" value="" v-model="metki1.ref.value">

			<div class="inp">
				<input class="inp__input" name="name" type="text" v-model="metki1.name.value" required>
				<span class="inp__focus" data-placeholder="Ваше имя"></span>
			</div>

			<div class="inp">
				<input class="inp__input" name="email" type="email" v-model="metki1.email.value" required>
				<span class="inp__focus" data-placeholder="Ваш Email"></span>
			</div>

			<div class="inp">
				<input class="inp__input" name="phone" type="text" required v-model="inputPhoneModel" v-mask="'+7 (###) ###-##-##'"  @blur="clearIncompletedField()">
				<span class="inp__focus" data-placeholder="Ваш телефон"></span>
			</div>

			<div class="inp">
				<input class="inp__input" name="password" type="password">
				<span class="inp__focus" data-placeholder="Ваш пароль"></span>
			</div>

			<div class="inp">
				<input class="inp__input" name="password_confirmation" type="password">
				<span class="inp__focus" data-placeholder="Повторите пароль"></span>
			</div>

			<div class="confid">
				<label>
					<input type="checkbox" class="uk-checkbox" v-model="$root.confid" />
					<div class="confid__content">
						<span>Я согласен с</span><a uk-toggle="target: #politicamodal">политикой конфиденциальности</a></div>
				</label>
			</div>

			<div class="account__buttons">
				<button class="uk-button uk-button-primary" type="submit" :disabled="!$root.confid">Зарегистрироваться</button>
			</div>
		</form>
		<div v-else>
			<div class="account__attent">Регистрация возможно только по рекомендации члена клуба. Не знаете где её взять?
				<a href="/send" class="uk-button uk-button-text">Напишите нам</a>
			</div>
		</div>
		<div class="account__bottom">
			<div class="account__podp">
				Есть аккаунт? <a class="/send" :href="url + '/login'">Войдите</a>
			</div>
		</div>
		<wait></wait>
	</div>
</template>

<script>
  import { bus } from './bus.js'
  import { account } from "../mixins/account";
  import wait from './wait'

  export default {
    name: "account-register",
    mixins: [account],
    components: { wait },
    data() {
      return {
        inputPhoneModel: '',
        metki1: {
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
    props: {},
    methods: {
      test(){
        console.log('test');
        $.request('onTest2', {
          data: '123',
          success: function (data) {
            //... do something ...
            console.log(data);
            //this.success(data);
          },
          error: function (err) {
            console.log(err);
          }
        })
      },
      setParams(parts) {
        //console.log('Fucking Parts!');
        if (parts) {
          let metki = this.metki1;
          for (let key in parts) {
            //console.log(key, parts[key]);
            if (parts[key]) {
              let val = parts[key];
              if(key == 'ref'){
                val = val.substr(2);
              }
              metki[key].value = val;
              metki[key].show = false;
            }
          }
        }
      },
      sendReg() {
        bus.$emit('wait', true);
        let app = this;
        $('.account_ajax').request('Registration::onAjax', {
          success: function (data) {
            app.checkErrors(data);
            bus.$emit('wait', false);
          }
        });
      },

      clearIncompletedField() {
        if (this.inputPhoneModel.length < 18) {
          this.inputPhoneModel = '';
        }
      },

      getMetkiFromStorage() {
        //console.log('Проверяем, есть ли метки');
        return JSON.parse(localStorage.getItem("metki"))
      },
    },
    mounted() {
      this.setParams(this.getMetkiFromStorage());
      //localStorage.clear();
    },
    created() {

      //console.log('Created Reg comp');
    }
  }

</script>
