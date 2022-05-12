<template>
	<div>
		<a href="#" v-if="!product.pivot" class="uk-button uk-button-primary" @click.prevent="buyItem">КУПИТЬ СЕЙЧАС</a>
		<a href="" v-if="product.pivot && product.pivot.paid == 0" class="uk-button uk-button-text" @click.prevent="checkStatus">Уточнить статус платежа</a>
	</div>
</template>

<script>
  import { bus } from './bus.js'
  import wait from './wait'

  export default {
    name: "buy-product",
    data() {
      return {}
    },
    props: {
      product: '',
      del: ''
    },
    methods: {
      buyItem() {
        bus.$emit('wait', true);
        let app = this;

        $.request('onBuyProduct', {
          data: {
            product_id: this.product.id
          },
          success: function (data) {
            console.log(data);
            bus.$emit('wait', false);
            let redirect = data.payUrl;
            window.location.href = redirect;
            //window.location.reload();
            //this.success(data);
          },
          error: function (err) {
            console.log(err);
          }
        });
      },
      deleteItem() {
        bus.$emit('wait', true);
        let app = this;

        $.request('onDeleteProduct', {
          data: {
            product_id: this.product.id
          },
          success: function (data) {
            console.log(data);
            bus.$emit('wait', false);
            window.location.reload();
          },
          error: function (err) {
            console.log(err);
          }
        });
      },
      checkStatus() {
        bus.$emit('wait', true);
        let app = this;

        $.request('onCheckQiwiPaymentStatus', {
          data: {
            product_id: this.product.id,
            payment_id: this.product.pivot.payment_id
          },
          success: function (data) {
            console.log(data);
            bus.$emit('wait', false);
            window.location.reload();
            //window.location.reload();
          },
          error: function (err) {
            console.log(err);
          }
        });
      }
    }
  }
</script>