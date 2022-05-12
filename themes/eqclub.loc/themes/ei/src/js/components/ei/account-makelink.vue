<template>
	<div>
		<h3>Создайте ссылку-приглашение и отправьте её пользователю</h3>
		<div class="account__group">
			<label class="uk-form-label">
				<span>Имя пользователя</span>
				<input class="uk-input" name="name" type="text" placeholder="Имя" v-model="datalink.name">
			</label>
			<label class="uk-form-label">
				<span>Email пользователя</span>
				<input class="uk-input" name="email" type="text" placeholder="Email" v-model="datalink.email">
			</label>
			<label class="uk-form-label">
				<span>Организация</span>
				<input class="uk-input" name="organization" type="text" placeholder="Организация" v-model="datalink.organization">
			</label>
			<hr>
			<label class="uk-form-label">
				<span>Ваша ссылка-приглашение</span>
				<input class="uk-input" disabled=disabled name="readylink" id="readylink" type="text" placeholder="Ссылка-приглашение" v-model="link">
			</label>
			<label class="uk-form-label" v-if="link">
				<span>Примечание к письму</span>
				<textarea class="uk-input" name="desc" placeholder="Примечание к письму" v-model="datalink.desc"></textarea>
			</label>
		</div>
		<div class="account__buttons">
			<button class="uk-button uk-button-primary" type="submit" @click="makeLink">Создать ссылку</button>
			<button v-if="link" class="uk-button uk-button-primary" type="submit" @click="sendLetter">Отправить ссылку пользователю</button>
		</div>
		<wait></wait>
	</div>
</template>

<script>
  import { account } from "../mixins/account";
  import { bus } from './bus.js'
  import wait from './wait'
  import Swal from 'sweetalert2/dist/sweetalert2.js'
  export default {
    name: "make-link",
    mixins: [account],
    components: {wait},
    data() {
      return {
        datalink: {
          name: '',
          email: '',
	        desc: '',
          organization: '',
          curator_id: this.data.id
        },
        link: ''
      }
    },
    props: {
      data: '',
      url: ''
    },
    methods: {
      makeLink() {
        let link = this.url + '?curator_id=' + this.datalink.curator_id + '&name=' + this.datalink.name + '&email=' + this.datalink.email + '&organization=' + this.datalink.organization;
        let input = document.getElementById("readylink");
        this.link = link;

        if (input.value = link) {
          this.copyLink(input);
        }
      },
	    copyLink(input){
        input.select();
        document.execCommand("copy");
	    },
      sendLetter() {
        bus.$emit('wait', true);
        let app = this;
        let data = {
          'name': this.datalink.name,
	        'c_name': this.data.name,
	        'email': this.datalink.email,
	        'desc': this.datalink.desc,
	        'link': this.link
        };
        console.log(data);
        $('.account_ajax').request('onSendInviteMail', {
          data: data,
          success: function (data) {
            app.checkErrors(data);
            bus.$emit('wait', false);
            Swal.fire({
              title: 'Успешно!',
              text: 'Ссылка-приглашение отправлена',
              type: 'success',
              confirmButtonText: 'Хорошо'
            });
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
