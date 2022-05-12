<template>
	<div>
		<transition name="fade">
			<div class="account__errors" v-if="errors">{{ errors }}</div>
		</transition>
		<transition name="fade">
			<div class="account__success" v-if="success">{{ success }}</div>
		</transition>
		<form class="account_ajax account__column" @submit.prevent="sendReg">
			<div class="account__group">
				<h3>Персональные данные</h3>
				<label class="uk-form-label">
					<span>Ваше имя</span>
					<input class="uk-input" name="name" type="text" placeholder="Имя" v-model="data.name">
				</label>
				<label class="uk-form-label">
					<span>Ваш телефон</span>
					<input class="uk-input" name="phone" type="text" placeholder="Телефон" v-model="data.phone">
				</label>
				<label class="uk-form-label">
					<span>Ваш email</span>
					<input class="uk-input" name="email" type="email" placeholder="Email" v-model="data.email">
				</label>
			</div>

			<div class="account__group">
				<h3>Сведения об организации</h3>
				<label class="uk-form-label">
					<span>Название организации</span>
					<input class="uk-input" name="property[organization]" type="text" placeholder="Организация" v-model="data.property.organization">
				</label>
				<label class="uk-form-label">
					<span>ИНН организации</span>
					<input class="uk-input" name="property[inn]" v-model="data.property.inn" type="text" placeholder="ИНН">
				</label>
				<label class="uk-form-label">
					<span>Адрес доставки</span>
					<input class="uk-input" name="property[address]" type="text" placeholder="Адрес" v-model="data.property.address">
				</label>
			</div>

			<div class="account__group">
				<h3>Прочее</h3>

				<div class="uk-margin-bottom" v-if="data.my_curator && data.my_curator.length > 0">
					<div class="account__pretitle">Текущий куратор:</div>
					<label class="uk-form-label small"><input class="uk-radio" type="radio" checked> {{ data.my_curator[0].name }}</label>
				</div>

				<div class="account__pretitle">Показ цены:</div>
				<label class="uk-form-label small"><input class="uk-radio" type="radio" name="property[prepay]" value="1" v-model="data.property.prepay"> Предоплата</label>
				<label class="uk-form-label small"><input class="uk-radio" type="radio" name="property[prepay]" value="0" v-model="data.property.prepay"> Постоплата</label>

			</div>
		</form>
		<div class="account__buttons">
			<button class="uk-button uk-button-primary" type="submit" @click.prevent="sendReg">Обновить данные</button>
		</div>
	</div>
</template>

<script>
  import { account } from "../mixins/account";

  export default {
    name: "account-changedata",
    mixins: [account],
    data() {
      return {}
    },
    props: {
      data: ''
    },
    methods: {
      sendReg() {
        let app = this;
        $('.account_ajax').request('UserPage::onAjax', {
          //data: {'email': $('input[name="email"]').val()},
          success: function (data) {
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
