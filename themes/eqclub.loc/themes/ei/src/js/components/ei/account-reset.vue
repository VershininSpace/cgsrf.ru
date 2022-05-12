<template>
	<div>
		<form class="account_ajax account__form" @submit.prevent="sendReg">
			<transition name="fade">
				<div class="account__errors" v-if="errors">{{ errors }}</div>
			</transition>
			<transition name="fade">
				<div class="account__success" v-if="success">{{ success }}</div>
			</transition>
			<div class="inp">
				<input class="inp__input" type="password" name="password">
				<span class="inp__focus" data-placeholder="Пароль"></span>
			</div>
			<div class="inp">
				<input class="inp__input" type="password" name="password_confirmation">
				<span class="inp__focus" data-placeholder="Подтверждение пароля"></span>
			</div>

			<div class="account__buttons">
				<button class="uk-button uk-button-primary" type="submit" :disabled="!$root.confid">Восстановить пароль</button>
			</div>
		</form>

		<wait></wait>
	</div>
</template>

<script>
  import { bus } from './bus.js'
  import wait from './wait'
  import { account } from "../mixins/account";

  export default {
    name: "account-restore",
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
        $('.account_ajax').request('ResetPassword::onAjax', {
          //data: {'email': $('input[name="email"]').val()},
          success: function (data) {
            //console.log(data);
            console.log('Success');
            //bus.$emit('wait',false);
	          app.$root.wait = false;
            app.checkErrors(data);
          },
        });
      }
    }
  }
</script>

<style lang="scss">

</style>