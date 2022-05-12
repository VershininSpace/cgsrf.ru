<template>
	<div>
		<form class="account_ajax account__form" @submit.prevent="sendReg">
			<transition name="fade">
				<div class="account__errors" v-if="errors">{{ errors }}</div>
			</transition>
			<div class="inp">
				<input class="inp__input" type="email" name="email">
				<span class="inp__focus" data-placeholder="Email"></span>
			</div>
			<div class="inp">
				<input class="inp__input" type="password" name="password">
				<span class="inp__focus" data-placeholder="Пароль"></span>
			</div>

			<!--<div class="confid">
				<label>
					<input type="checkbox" class="uk-checkbox" v-model="$root.confid" />
					<div class="confid__content">
						<span>Я согласен с</span><a uk-toggle="target: #politicamodal">политикой конфиденциальности</a></div>
				</label>
			</div>-->

			<div class="account__buttons">
				<button class="uk-button uk-button-primary" type="submit" :disabled="!$root.confid">Войти на сайт</button>
			</div>
		</form>


		<div class="account__bottom">
			<div class="account__podp">
				У вас нет аккаунта? <a class="uk-button uk-button-text" :href="url + '/register'">Зарегистрируйтесь</a>
				<br><br>
				Забыли пароль? <a class="uk-button uk-button-text" :href="url + '/restore-password'">Восстановить пароль</a>
			</div>
		</div>
		<wait></wait>
	</div>
</template>

<script>
  import { bus } from './bus.js'
  import wait from './wait'
  import { account } from "../mixins/account";
  import request from 'oc-request';

  export default {
    name: "account-login",
    mixins: [account],
    components: {wait},
    data() {
      return {}
    },
    props: {
      items: ''
    },
    methods: {
      sendReg() {
        //bus.$emit('wait', true);
        let app = this;
        $('.account_ajax').request('Login::onAjax', {
        //document.querySelector('.account_ajax').request('Login::onAjax', {
          //data: {'email': $('input[name="email"]').val()},
          success: function (data) {
            //console.log(data);
            //bus.$emit('wait',false);
            app.$root.wait = false;
            app.checkErrors(data);
            //console.log(data);
            /*if(data.status) {
              //Email is available
            } else {
              //Email is not available
            }*/
          }
        });
      }
    }
  }
</script>

<style lang="scss">

</style>