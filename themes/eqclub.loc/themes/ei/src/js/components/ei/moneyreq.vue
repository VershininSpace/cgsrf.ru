<template>
	<div class="moneyreq">
		<ul class="moneyreq__items" v-if="rms.length > 0">
			<li v-for="mr in rms" class="moneyreq__item">
				<span><strong>Сумма:</strong> {{ mr.total }} руб</span>
				<span :class="getStatusClass(mr.status)"><strong>Статус:</strong> {{ mr.status }}</span>
				<span><strong>Дата:</strong> {{ mr.created_at }}</span>
			</li>
		</ul>
		<button class="uk-button uk-button-primary" v-if="!showreq" @click="showreq = true">
			<span uk-icon="plus"></span> Новый запрос
		</button>
		<div class="moneyreq__req" v-show="showreq">
			<h4>Выберите сумму, которую хотите вывести и отправьте запрос на рассмотрение.</h4>
			<input id="ranger" type="text" class="uk-input">
			<button class="uk-button uk-button-primary" @click="makeMoneyRequest">Отправить запрос</button>
		</div>
	</div>
</template>

<script>
  import 'ion-rangeslider/css/ion.rangeSlider.min.css'
  import 'ion-rangeslider/js/ion.rangeSlider.min'

  export default {
    name: "moneyreq",
    data() {
      return {
        showreq: false,
        total: 0,
        rms: []
      }
    },
    props: {
      user: ''
    },
    methods: {
      getStatusClass(st) {
        switch (st) {
          case 'Новый':
            return 'new';
            break;
          case 'Исполнен':
            return 'finish';
            break;
        }
      },
      makeRange() {
        let app = this;
        $("#ranger").ionRangeSlider({
          skin: "round",
          min: 0,
          max: +app.user.balance,
          step: 10,
          postfix: ' руб.',
          onFinish: function (data) {
            //console.log(data);
            app.total = data.from;
            // fired on pointer release
          },
        });
      },
      makeMoneyRequest() {
        if (this.total > 0) {
          let app = this;
          this.$root.wait = true;
          //let rms = this.user.reqmoneys;
          //console.log(rms);
          let data = {
            'total': this.total
          };
          $.request('onMakeMoneyreq', {
            data: data,
            success: function (data) {
              console.log(data);
              app.rms.push(data);
              swal("Запрос отправлен", "", "success");
              app.showreq = false;
              app.total = 0;
              app.$root.wait = false;
              //app.user.reqmoneys = rms;
              //console.log(rms);
              //app.transactions = data;
            },
            error: function (err) {
              console.log(err);
            }
          });
        } else {
          swal("Выберите сумму!", "Сумма должна быть больше 0", "warning");
        }

      }
    },
    mounted() {
      this.rms = this.user.reqmoneys;
      this.makeRange();
    }
  }
</script>