<style>
.qr{
  text-align: center;
  margin-top: 50px;
}
.qr img{
  display: inline !important;
}
.user_errors {
    color: red;
    margin-bottom: 20px;
}
.error_message {
  display: none;
}
.error_field {
  color: red;
}
.error_field input{
  border: 1px solid red;
  box-shadow: 0px 0px 36px rgba(255, 0, 0, 0.08);
}
.error_field .error_message {
  display: block;
  margin-bottom: 10px;
}
</style>

<template>

<div>

<h4>Вас пригласил:</h4>
<div class='buy__item'>
  {{ parent_user.id }} - {{ parent_user.name }} - {{ parent_user.phone }} - {{ parent_user.email }}
</div>

<h4>Личные данные:</h4>

<div class='buy__item'>            
              <div class="account__group" id="user_data">

                <div class='user_errors' v-if="Object.keys(errors).length">
                    <span>Пожалуйста исправьте указанные ошибки:</span>
                </div>

                <label class="uk-form-label" :class="{'error_field' : errors.name }">
                  <span>Ваше имя</span>
                  <input class="uk-input" type="text" placeholder="Имя" v-model="user_model.name">
                  <span class="error_message">Требуется указать Имя.</span>
                </label>

                <label class="uk-form-label" :class="{'error_field' : errors.phone }">
                  <span>Ваш телефон</span>
                  <input class="uk-input" type="text" placeholder="Телефон" v-model="user_model.phone" />
                  <span class="error_message">Требуется указать корректный Телефон.</span>
                </label>
                <label class="uk-form-label" :class="{'error_field' : errors.email }">
                  <span>Ваш email</span>
                  <input class="uk-input" type="email" placeholder="Email" v-model="user_model.email">
                  <span class="error_message">Требуется указать корректный Email.</span>
                </label>
                <label class="uk-form-label">
                  <span>Instagram</span>
                  <input class="uk-input" type="text" placeholder="Instagram" v-model="user_model.property.instagram">
                </label>
                <label class="uk-form-label">
                  <span>VK</span>
                  <input class="uk-input" type="text" placeholder="VK" v-model="user_model.property.vk">
                </label>
                <label class="uk-form-label">
                  <span>WhatsAPP</span>
                  <input class="uk-input" type="text" placeholder="WhatsAPP" v-model="user_model.property.whatsapp">
                </label>
                <label class="uk-form-label">
                  <span>Telegram</span>
                  <input class="uk-input" type="text" placeholder="Telegram" v-model="user_model.property.telegram">
                </label>
                <label class="uk-form-label">
                  <span>Viber</span>
                  <input class="uk-input" type="text" placeholder="Viber" v-model="user_model.property.viber">
                </label>
              </div>
</div>

<a class="uk-button uk-button-primary" @click="submit">Сохранить</a>



</div>

</template>

<script>

  export default {
    name: "account-data",
    data() {
      return {
        errors: {},
        user_model: {}
      }
    },

    props: {
      user: {},
      parent_user: {}
    },
    computed: {

    },

    methods: {

      clearIncompletedField() {
        if (this.user_model.phone.length < 18) {
          this.user_model.phone = '';
        }
      },

      checkForm() {
        this.errors = {};

        let regex_email = /\S+@\S+\.\S+/;
        let regex_phone = /^(\+7|7|8)?[\s\-]?\(?[489][0-9]{2}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}$/;
    
        if (!this.user_model.name) this.errors.name = true;
        if (!regex_email.test(this.user_model.email)) this.errors.email = true;
        if (!regex_phone.test(this.user_model.phone)) this.errors.phone = true;
      },
      submit() {

        this.checkForm()

        if (Object.keys(this.errors).length) {
          document.getElementById('user_data').scrollIntoView();
          return true
        };


        let update_data = {
          'name': this.user_model.name,
          'phone': this.user_model.phone,
          'email': this.user_model.email,
          'property': this.user_model.property
        }
          let app = this;
          this.$root.wait = true;
          $.request('onUserUpdate', {
            data: update_data,
            success: function (data) {
                swal('Успешно', {
                    icon: 'success',
                    buttons: false,
                    timer: 1000,
                });
              document.location.reload();
            },
            error: function (err) {
                swal('Ошибка', {
                    icon: 'error',
                    buttons: false,
                    timer: 1000,
                });
            }
          });

        }
  
    },

    created() {
      this.user_model = this.user;

      if (!this.user_model.property) {
        this.user_model.property = {
          'instagram' : '',
          'vk' : '',
          'whatsapp' : '',
          'telegram' : '',
          'viber' : ''
        }
      };
    }
  }
</script>

<style>

</style>